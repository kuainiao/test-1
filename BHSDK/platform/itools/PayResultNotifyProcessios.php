<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';
require_once 'notify.php';

if (!isset($_POST['notify_data']) || !isset($_POST['sign'])) {
	die('fail');
}

//通知数据
$notify_data = $_POST['notify_data'];
//签名
$sign = $_POST['sign'];

$notify = new notify;

//RSA解密
$notify_data = $notify->decrypt($notify_data);

if ($notify->verify($notify_data, $sign)) {
	//逻辑处理, $notify_data: json数据(格式: {"order_id_com":"2013050900000712","user_id":"10010","amount":"0.10","account":"test001","order_id":"2013050900000713","result":"success"})
	$json = json_decode($notify_data, true);
	$request['order_id_com']  = $json['order_id_com'];  	//发起支付时的订单号
    $request['uid']  	  = (int)$json['user_id'];      	    //支付的用户id 
    $request['money']  	  = $json['amount'];       	    //成功支付的金额
    $request['account']   = $json['account'];           //支付帐号 
    $request['order_id']  = $json['order_id']; 			//支付平台的订单号
    $request['result']	  = $json['result'];         	//支付结果,  目前只有成功状态, success
    
    $Res = pay_result_notify_process($request);
} else {
	die("fail");
}

function pay_result_notify_process($request)
    {
        $thirdType = 14;
		//比较解密出的数据中的dealseq和参数中的dealseq是否一致
		if($request['result']=="success"){
			$str = explode('_',$request['order_id_com']);
			if($str&&is_array($str)){
				$server = (int)$str[0];
			}else{
				die("参数错误");
			}
			if($server==''){
				die("参数错误");
			}
			$newdb = new PlatModel();
            $selectOrderId = $newdb->selectPayOrder($request['uid'],$request['order_id'],'platform_itools_pay');
            //验证是否充值过
            if ($request['order_id'] == $selectOrderId)
            {
                echo "success";
            }
            else
            {
                $sdkPaytime = time();
                $insert = $newdb->insertItoolsPay($request,$server,$sdkPaytime,$channel_type=2);
                if($insert)
                {
                	$area = Ext::getInstance()->getArea($server);
                    $gameMoney = $request['money']*10;
                    $accountId = $newdb->selectThirdUserAccountId('platform_itools_user',$area,$request['uid']);
                    $username = $newdb->selectThirdUserWzUsername('platform_itools_user',$area,$request['uid']);
                    $socketClass = new ConnectGameServer();
                    $socketClass->payToGameServer($server,$thirdType,$username,$accountId,$gameMoney,$request['order_id']);
					$newdb->insertUserpaylog($request['uid'], $thirdType, $request['order_id'], $sdkPaytime, $area, $server, $request['money'], $accountId,2);
					echo "success";
                }
                else
                {
                    echo '插入游戏平台订单失败';
                }
            }
		}else{
			die('fail');
		}
    }
?>