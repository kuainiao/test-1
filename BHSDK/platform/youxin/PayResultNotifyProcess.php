<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';

$appkey = '097561E4A1C0EE278F1C242C728ADDA5';
$re['appid'] = $_GET['appid'];//游戏接入APPID
$re['openid'] = $_GET['openid'];//有信开放平台用户授权ID
$re['order_id'] = $_GET['orderid'];//游戏服务器生成的订单号
$re['money'] = $_GET['ordermoney'];//支付金额(分)
$re['ordertime'] = urldecode($_GET['ordertime']);//游戏服务器生成订单的时间
$re['ext1'] = $_GET['ext1'];//游戏服务器提供，有信开放平台会原样返回，urlencode编码之后的值，编码类型: utf-8
$re['result'] = $_GET['orderstatus'];//订单成功标志（1表示成功，其他均代表失败）
$re['notifytime'] = urldecode($_GET['notifytime']);//支付结果通知时间   例：2013-08-17 00:00:00
$sign = $_GET['sign'];//消息签名
$sign1 = md5($re['appid'].$re['ext1'].$re['notifytime'].$re['openid'].$re['order_id'].$re['money'].$re['result'].$re['ordertime'].$appkey);

if($sign != $sign1){
	//验签名失败
	die('fail');
}

$Res = pay_result_notify_process($re);


 function pay_result_notify_process($re)
    {
        $thirdType = 16;
		if($re['result']!=1){
			die('fail');
		}
		$uid = $re['openid'];
		if($uid>9223372036854775807){
			$uid = abs(crc32($uid));
		}
		$server = $re['ext1'];
        $newdb = new PlatModel();
        $selectOrderId = $newdb->selectPayOrder($uid,$re['order_id'],'platform_youxin_pay');
        //验证是否充值过
        if ($re['order_id'] == $selectOrderId)
        {
            die('success');
        }
        else
        {
            $sdkPaytime = time();
            $insert = $newdb->insertYxPay($re,$uid,$server,$sdkPaytime,$channel_type=1);
            if($insert)
            {
            	$area = Ext::getInstance()->getArea($server);
				
                $gameMoney = $re['money']/10;
                $accountId = $newdb->selectThirdUserAccountId('platform_youxin_user',$area,$uid);
                $username = $newdb->selectThirdUserWzUsername('platform_youxin_user',$area,$uid);
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server,$thirdType,$username,$accountId,$gameMoney,$re['order_id']);
				$newdb->insertUserpaylog($uid, $thirdType, $re['order_id'], $sdkPaytime, $area, $server, $re['money']/100, $accountId);
                die('success');
            }
            else
            {
            	die('插入游戏平台订单失败');
            }
        }
    }
?>