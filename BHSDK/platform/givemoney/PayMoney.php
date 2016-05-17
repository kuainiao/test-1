<?php
header("Content-type: text/html; charset=utf-8");

require_once('../../models/PayModel.php');
require_once('../../models/PlatModel.php');
require_once '../../socket/ConnectServer.php';
require_once '../../models/UserLoginModel.php';
require_once '../../ext/Ext.php';


$flat = $_GET['flat'];
$server = $_GET['server'];
$uid = $_GET['uid'];
$yb = $_GET['yb'];
$sign = $_GET['sign'];

pay_result_notify_process($flat, $server, $uid, $yb, $sign);

function pay_result_notify_process($flat, $server, $uid, $yb, $sign)
{
    $MyAppKey = '9256jhgbgjioafn2850157';
    $sign_check = md5($MyAppKey.$flat.$server.$uid.$yb);
    $string = substr(md5(time()),8,2);
    $order_id = createRandString(12).$string;

    if($sign_check == $sign)
    {
        if($flat == 'yunding')
        {
            $thirdType = 22;
            $money = $yb/10;
            $newdb = new PayModel();
            $insert = $newdb->insertPay($uid,$thirdType,$server,$yb,$order_id);
            $area = Ext::getInstance()->getArea($server);
            $newPlat = new PlatModel();
            $accountId = $newPlat->selectThirdUserAccountId('platform_yunding_user',$area,$uid);
            $username = $newPlat->selectThirdUserWzUsername('platform_yunding_user',$area,$uid);
            if(!empty($accountId)&&!empty($username))
            {
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server, $thirdType, $username, $accountId, $yb, $order_id);
                $newPlat->insertUserpaylog($uid, $thirdType, $order_id, time(), $area, $server, $money, $accountId);
                $Result["ErrorCode"] =  "1";
                $Result["ErrorDesc"] =  urlencode("pay_ok");
                $Res = json_encode($Result);
                echo urldecode($Res);
            }
            else
            {
                $Result["ErrorCode"] =  "2";
                $Result["ErrorDesc"] =  urlencode("no_user");
                $Res = json_encode($Result);
                echo urldecode($Res);
            }
        }
        else if($flat == 'huawei')
        {
            $thirdType = 23;
            $money = $yb/10;
            $newdb = new PayModel();
            $insert = $newdb->insertPay($uid,$thirdType,$server,$yb,$order_id);
            $area = Ext::getInstance()->getArea($server);
            $newPlat = new PlatModel();
            $accountId = $newPlat->selectThirdUserAccountId('platform_huawei_user',$area,$uid);
            $username = $newPlat->selectThirdUserWzUsername('platform_huawei_user',$area,$uid);
            if(!empty($accountId)&&!empty($username))
            {
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server, $thirdType, $username, $accountId, $yb, $order_id);
                $newPlat->insertUserpaylog($uid, $thirdType, $order_id, time(), $area, $server, $money, $accountId);
                $Result["ErrorCode"] =  "1";
                $Result["ErrorDesc"] =  urlencode("pay_ok");
                $Res = json_encode($Result);
                echo urldecode($Res);
            }
            else
            {
                $Result["ErrorCode"] =  "2";
                $Result["ErrorDesc"] =  urlencode("no_user");
                $Res = json_encode($Result);
                echo urldecode($Res);
            }
        }
        else if($flat == 'vivo')
        {
            $thirdType = 24;
            $money = $yb/10;
            $newdb = new PayModel();
            $insert = $newdb->insertPay($uid,$thirdType,$server,$yb,$order_id);
            $area = Ext::getInstance()->getArea($server);
            $newPlat = new PlatModel();
            $accountId = $newPlat->selectThirdUserAccountId('platform_vivo_user',$area,$uid);
            $username = $newPlat->selectThirdUserWzUsername('platform_vivo_user',$area,$uid);
            if(!empty($accountId)&&!empty($username))
            {
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server, $thirdType, $username, $accountId, $yb, $order_id);
                $newPlat->insertUserpaylog($uid, $thirdType, $order_id, time(), $area, $server, $money, $accountId);
                $Result["ErrorCode"] =  "1";
                $Result["ErrorDesc"] =  urlencode("pay_ok");
                $Res = json_encode($Result);
                echo urldecode($Res);
            }
            else
            {
                $Result["ErrorCode"] =  "2";
                $Result["ErrorDesc"] =  urlencode("no_user");
                $Res = json_encode($Result);
                echo urldecode($Res);
            }
        }
        else if($flat == 'youlong')
        {
            $thirdType = 25;
            $money = $yb/10;
            $newdb = new PayModel();
            $insert = $newdb->insertPay($uid,$thirdType,$server,$yb,$order_id);
            $area = Ext::getInstance()->getArea($server);
            $newPlat = new PlatModel();
            $accountId = $newPlat->selectThirdUserAccountId('platform_youlong_user',$area,$uid);
            $username = $newPlat->selectThirdUserWzUsername('platform_youlong_user',$area,$uid);
            if(!empty($accountId)&&!empty($username))
            {
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server, $thirdType, $username, $accountId, $yb, $order_id);
                $newPlat->insertUserpaylog($uid, $thirdType, $order_id, time(), $area, $server, $money, $accountId);
                $Result["ErrorCode"] =  "1";
                $Result["ErrorDesc"] =  urlencode("pay_ok");
                $Res = json_encode($Result);
                echo urldecode($Res);
            }
            else
            {
                $Result["ErrorCode"] =  "2";
                $Result["ErrorDesc"] =  urlencode("no_user");
                $Res = json_encode($Result);
                echo urldecode($Res);
            }
        }
        else
        {
            $thirdType = 13;
            $money = $yb/10;
            $newdb = new PayModel();
            $insert = $newdb->insertPay($uid,$thirdType,$server,$yb,$order_id);
            $area = Ext::getInstance()->getArea($server);
            $newPlat = new PlatModel();
            $accountId = $newPlat->selectThirdUserAccountId('platform_oppo_user',$area,$uid);
            $username = $newPlat->selectThirdUserWzUsername('platform_oppo_user',$area,$uid);
            if(!empty($accountId)&&!empty($username))
            {
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server, $thirdType, $username, $accountId, $yb, $order_id);
                $newPlat->insertUserpaylog($uid, $thirdType, $order_id, time(), $area, $server, $money, $accountId);
                $Result["ErrorCode"] =  "1";
                $Result["ErrorDesc"] =  urlencode("pay_ok");
                $Res = json_encode($Result);
                echo urldecode($Res);
            }
            else
            {
                $Result["ErrorCode"] =  "2";
                $Result["ErrorDesc"] =  urlencode("no_user");
                $Res = json_encode($Result);
                echo urldecode($Res);
            }
        }
    }
    else
    {
        $Result["ErrorCode"] =  "0";
        $Result["ErrorDesc"] =  urlencode("signerror");
        $Res = json_encode($Result);
        echo urldecode($Res);
	}
}

function createRandString($length)
{
    $rand = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';  //定义字符池
    for($i=0;$i<$length;$i++)
    {
        $rand .= $pattern{mt_rand(0,35)};  //从a-Z选择生成随机数
    }
    return $rand; // 终止函数的执行和从函数中返回一个值
}
