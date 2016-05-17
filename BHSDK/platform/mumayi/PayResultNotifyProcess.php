<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';

$appkey = 'abf2adcc9b88400anJhz2omEYcmKQhJx3VDiYFUSKCJOENzwnFf7DgywjJNy1HI';
$re['uid']            = $_POST['uid'];               //有信开放平台用户授权ID
$re['order_id']       = $_POST['orderID'];           //游戏服务器生成的订单号
$re['productName']    = $_POST['productName'];       //商品名称
$re['money']          = $_POST['productPrice'];      //商品金额
$re['productDesc']    = $_POST['productDesc'];       //商品描述
$re['ordertime']      = $_POST['orderTime'];         //交易时间
$re['result']         = $_POST['tradeState'];        //交易结果 success 为成功,error 为失败
$tradeSign            = $_POST['tradeSign'];         //交易签名,用来开发判断此回调是不是由木蚂蚁真实请求

$verfic = mumayiDecode($tradeSign,$appkey);
if($verfic==$_POST['orderID']){
	$Res = pay_result_notify_process($re);
	//echo 'success';
}else{
	echo 'fail';
}


 function pay_result_notify_process($re)
    {
        $thirdType = 18;
		if($re['result']!='success'){
			die('fail');
		}
		$uid    = $re['uid'];
		$server = $re['productDesc'];
        $newdb = new PlatModel();
        $selectOrderId = $newdb->selectPayOrder($uid,$re['order_id'],'platform_mumayi_pay');
        //验证是否充值过
        if ($re['order_id'] == $selectOrderId)
        {
            die('success');
        }
        else
        {
            $sdkPaytime = time();
            $insert = $newdb->insertMyPay($re,$uid,$server,$sdkPaytime,$channel_type=1);
            if($insert)
            {
            	$area = Ext::getInstance()->getArea($server);
				
                $gameMoney = $re['money']*10;
                $accountId = $newdb->selectThirdUserAccountId('platform_mumayi_user',$area,$uid);
                $username  = $newdb->selectThirdUserWzUsername('platform_mumayi_user',$area,$uid);
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server,$thirdType,$username,$accountId,$gameMoney,$re['order_id']);
				$newdb->insertUserpaylog($uid, $thirdType, $re['order_id'], $sdkPaytime, $area, $server, $re['money'], $accountId);
                die('success');
            }
            else
            {
            	die('插入游戏平台订单失败');
            }
        }
    }

	//木蚂蚁支付验证
	// +----------------------------------------------------------------------+
	// | MUMAYI                                                               |
	// +----------------------------------------------------------------------+
	// | Copyright (c) 2012-2013 Mumayi cui                                   |
	// +----------------------------------------------------------------------+
	// | Authors: mumayi cuitengwei <cuitengwei@mumayi.com>                   |
	// +----------------------------------------------------------------------+
	// $data 密文(返回参数中:tradeSign)
	// $key  密匙(返回参数中:appkey)
	function mumayiDecode($data,$key){
	    $mumayiArr=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9');
	    if(strlen($data)<14)return false;
	    $verity_str=substr($data, 0,8);
	    $data=substr($data, 8);
	    if($verity_str!=substr(md5($data),0,8)){
		return false;
	    }
	    $key_b=substr($data,0,6);
	    $rand_key=$key_b.$key;
	    $rand_key=md5($rand_key);
	    $data = base64_decode(substr($data, 6));
	    $datalen=strlen($data);
	    $verfic="";
	    for($i=0;$i<$datalen;$i++){
		$verfic.=$data{$i}^$rand_key{$i%32};
	    }
	    return $verfic;
	}
?>