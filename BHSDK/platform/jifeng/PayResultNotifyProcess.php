<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';
 
$re['time'] = $_REQUEST['time'];      //用于加密的时间，此为时间戳 
$sign       = $_REQUEST['sign'];      //加密的MD5标示 
$userId     = '24787017';

 //正确接收到相应的数据 
if(!empty($re['time'])&&!empty($sign)){
	   // 
   $encodeString=md5($userId.$re['time']);
   //通过签名
   if($sign==$encodeString){

	    //获取post过来的数据 
		$response= file_get_contents("php://input");
		
		//创建解析器
		$parser = xml_parser_create();                        
		//解析到数组
		xml_parse_into_struct($parser, $response, $values, $index);    
		//释放解析器 
		xml_parser_free($parser);
		//   
		$re['order_id']=$values[1]['value'];    //订单号，要求不大于64位
		//
		$appkey=$values[2]['value'];
		//
		$re['cost']=$values[3]['value'];        //机锋卷（1元=10机锋券）
		//
		$re['create_time']=$values[4]['value']; //订单创建时间，此为时间戳
		
		if(!empty($re['order_id'])&&!empty($appkey)&&!empty($re['cost'])&&!empty($re['create_time'])){
			$Res = pay_result_notify_process($re);
	    }
	}
}


 function pay_result_notify_process($re)
    {
	    $result='<response><ErrorCode>1</ErrorCode><ErrorDesc>Success</ErrorDesc></response>';
       
	    $thirdType = 17;
		if($re['order_id']==''){
			die('<response><ErrorCode>0</ErrorCode><ErrorDesc>订单号错误</ErrorDesc></response>');
		}
		$str = explode('_',$re['order_id']);
		$uid = 0;
		if($str&&is_array($str)){
			$uid = $str[0];
			$server = $str[1];
		}else{
			die('参数错误');
		}
        if($uid==0){
        	die('参数错误');
        }
		if($server==''){
			die('参数错误');
		}
        $newdb = new PlatModel();
        $selectOrderId = $newdb->selectPayOrder($uid,$re['order_id'],'platform_jifeng_pay');
        //验证是否充值过
        if ($re['order_id'] == $selectOrderId)
        {
            die($result);
        }
        else
        {
            $sdkPaytime = time();
            $insert = $newdb->insertJfPay($re,$uid,$server,$sdkPaytime,$channel_type=1);
            if($insert)
            {
            	$area = Ext::getInstance()->getArea($server);
				
                $gameMoney = $re['cost']/1;
                $accountId = $newdb->selectThirdUserAccountId('platform_jifeng_user',$area,$uid);
                $username = $newdb->selectThirdUserWzUsername('platform_jifeng_user',$area,$uid);
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server,$thirdType,$username,$accountId,$gameMoney,$re['order_id']);
				$newdb->insertUserpaylog($uid, $thirdType, $re['order_id'], $sdkPaytime, $area, $server, $re['cost']/10, $accountId);
                die($result);
            }
            else
            {
            	$fail = '<response><ErrorCode>0</ErrorCode><ErrorDesc>插入订单失败</ErrorDesc></response>';
            	die($fail);
            }
        }
    }
?>