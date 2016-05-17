<?php
/**
 * PHP for  BaiDuSDK
 *
 * @version 1.0
 * @author 91
 */

header("Content-type: text/html; charset=utf-8");

if (!function_exists('json_decode')){
	exit('您的PHP不支持JSON，请升级您的PHP版本。');
}
require_once '../../halo/HaloLog.php';
require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php'; 
/**
 * 应用服务器接收服务器端发过来发货通知的接口DEMO
 * 当然这个DEMO只是个参考，具体的操作和业务逻辑处理开发者可以自由操作
 */
/*
 * 这里的AppId和Secretkey是我们自己做测试的
 * 开发者可以自己根据自己在平台上创建的具体应用信息进行修改
 */
$AppId = 5699297; //应用开发者appid
$Secretkey = '89Bq3QtwAsBuOyFhyqd8c9dLjIgijGQC';//应用开发者apKey

$Res = notify_process($AppId,$Secretkey);


/**
 * 此函数就是接收服务器那边传过来传后进行各种验证操作处理代码
 * @param int $AppId 应用Id
 * @param string $Secretkey 应用Secretkey
 * @return json 结果信息
 */
function notify_process($AppId,$Secretkey){
	
	$Result = array();//存放结果数组
	$OrderSerial='';
	$CooperatorOrderSerial='';
	$Sign='';
	$Content='';

	//获取参数  提供两种获取参数方式
	//1.Request方式获取请求参数
	//if(isset($_REQUEST['OrderSerial']))
		//$OrderSerial= $_REQUEST['OrderSerial'];
	//if(isset($_REQUEST['CooperatorOrderSerial']))
		//$CooperatorOrderSerial= $_REQUEST['CooperatorOrderSerial'];
	//if(isset($_REQUEST['Sign']))
		//$Sign= $_REQUEST['Sign'];
	//if(isset($_REQUEST['Content']))
		//$Content= $_REQUEST['Content'];//Content通过Request读取的数据已经自动解码

	//2.读取POST流方式获取请求参数
	$inputParams = file_get_contents('php://input');
	$connectorParam = "&";
	$spiltParam="=";
	if(!empty($inputParams)){
		if(strpos($inputParams,$connectorParam) && strpos($inputParams,$spiltParam)){
			$list=explode($connectorParam,$inputParams);
			//print(count($list));
			for($i=0;$i<count($list);$i++){
				$kv=explode($spiltParam,$list[$i]);
				if(count($kv)>1){
					if($kv[0]=="OrderSerial"){
						$OrderSerial=$kv[1];
					}else if($kv[0]=="CooperatorOrderSerial"){
						$CooperatorOrderSerial=$kv[1];
					}else if($kv[0]=="Sign"){
						$Sign=$kv[1];
					}else if($kv[0]=="Content"){
						$Content=urldecode($kv[1]);	//读取POST流的方式需要进行HttpUtility.UrlDecode解码操作
						//print($Content);
					}
				}
			}
		}
	}
	//参数检测
	if(empty($OrderSerial)||empty($CooperatorOrderSerial)||empty($Sign)
		||empty($Content)){
		$Result["AppID"] =  $AppId;
		$Result["ResultCode"] =  1000;
		$Result["ResultMsg"] =  urlencode("接收参数失败");
		$Result["Sign"] =  md5($AppId.$Result["ResultCode"].$Secretkey);
		$Result["Content"] =  "";
		$Res = json_encode($Result);
		return urldecode($Res);
	}
	
	//检测请求数据签名是否合法
	if($Sign != md5($AppId.$OrderSerial.$CooperatorOrderSerial.$Content.$Secretkey)){
		$Result["AppID"] =  $AppId;
		$Result["ResultCode"] =  1001;
		$Result["ResultMsg"] =  urlencode("签名错误");
		$Result["Sign"] =  md5($AppId.$Result["ResultCode"].$Secretkey);
		$Result["Content"] =  "";
		$Res = json_encode($Result);
		return urldecode($Res);
	}

	//base64解码
	$Content=base64_decode($Content);
	//json解析
	//$Item=extract(json_decode($Content,true));
    $Item=json_decode($Content,true);
	//$UID $MerchandiseName $OrderMoney $StartDateTime $BankDateTime $OrderStatus $StatusMsg $ExtInfo 
	//根据获取到的数据，执行业务处理

    $StartDateTime = $Item['StartDateTime'];
    $UID = $Item['UID'];
    $OrderStatus = $Item['OrderStatus'];
    $OrderMoney = $Item['OrderMoney'];
    $ExtInfo = $Item['ExtInfo'];
    $ExtInfo = explode('_',$ExtInfo );
    $ExtInfo = $ExtInfo[1];

    HaloLog::addLog('nineone2015', '$UID='.$UID);
    HaloLog::addLog('nineone2015', '$OrderStatus='.$OrderStatus);
    HaloLog::addLog('nineone2015', '$OrderMoney='.$OrderMoney);
    HaloLog::addLog('nineone2015', '$ExtInfo='.$ExtInfo);
	
	

    $thirdType = 0;

    if ($OrderStatus == 1)
    {
        $newdb = new PlatModel();
        $selectOrderId = $newdb->selectNineOnePay($UID,$CooperatorOrderSerial,'platform_nineonenew_pay');
        //验证是否充值过
        if ($CooperatorOrderSerial == $selectOrderId)
        {
			//返回成功结果
			$Result["AppID"] = $AppId;
			$Result["ResultCode"] =  1;
			$Result["ResultMsg"] =  urlencode("成功");
			$Result["Sign"] =  md5($AppId.$Result["ResultCode"].$Secretkey);
			$Result["Content"] = "";
			$Res = json_encode($Result);
			echo urldecode($Res);
            //echo "ResultCode=1";
        }
        else
        {
            $sdkPaytime = time();
            $insert = $newdb->insertNineOnePay($OrderMoney,'nineone2015',$UID,$CooperatorOrderSerial,$ExtInfo,$OrderStatus,$StartDateTime,$sdkPaytime);
            if($insert)
            {  
                $area = Ext::getInstance()->getArea($ExtInfo);
                //echo file_put_contents("/tmp/test1.txt", "1");//把内容写入到一个文件
                $gameMoney = $OrderMoney*10;
                $accountId = $newdb->selectThirdUserAccountId91('platform_nineone_user',$area,$UID);
				//echo file_put_contents("/tmp/test1.txt",sprintf("NS_area = '%s';", $accountId));
                $username = $newdb->selectThirdUserWzUsername91('platform_nineone_user',$area,$UID);
				//echo file_put_contents("/tmp/test2.txt",sprintf("NS_area = '%s';", $username));
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($ExtInfo, $thirdType, $username, $accountId, $gameMoney, $CooperatorOrderSerial);
                $newdb->insertUserpaylog($UID, $thirdType, $CooperatorOrderSerial, $sdkPaytime, $area, $ExtInfo, $OrderMoney, $accountId);
				
				//返回成功结果
				$Result["AppID"] = $AppId;
				$Result["ResultCode"] =  1;
				$Result["ResultMsg"] =  urlencode("成功");
				$Result["Sign"] =  md5($AppId.$Result["ResultCode"].$Secretkey);
				$Result["Content"] = "";
				$Res = json_encode($Result);
				echo urldecode($Res);
                //echo "ResultCode=1";
            }
            else
            {
                echo '插入91订单失败';
            }
        }
    }
    else
    {
        echo "无效订单";
    }
}
