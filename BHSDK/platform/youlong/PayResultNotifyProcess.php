<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../halo/HaloLog.php';
require_once 'YlSdk.php';
require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';


$orderId = $_POST['orderId'];
$userName = $_POST['userName'];
$amount = $_POST['amount'];
$server = $_POST['extra'];
$flag = $_POST['flag'];

ksort($_POST);
foreach($_POST as $key=>$value)
{
    $i = 0;
    if($key != "signMethod" && $key != "signature")
    {
        $param .= ($i == 0 ? '' : '&').$key.'='.$value.'&';
    }
    $i++;
}
$log = new HaloLog();
$log->addLog('youlong', $param);

pay_result_notify_process($orderId, $userName, $amount, $server, $flag);

function pay_result_notify_process($orderId, $userName, $amount, $server, $flag)
{
    $thirdType = 25;
    $pkey = '154320d8cafedcb01588cf19be1aaad8';

    $my_flag = strtoupper(md5($orderId.$userName.$amount.$server.$pkey));

    $uid = crc32($userName);

    if($my_flag == $flag)
    {
        $newdb = new PlatModel();
        $selectOrderId = $newdb->selectPay($uid,$orderId,'platform_youlong_pay');
        //验证是否充值过
        if ($orderId == $selectOrderId)
        {
            echo 'OK';
        }
        else
        {
            $sdkPaytime = time();
            $insert = $newdb->insertYlPay($amount,$uid,$orderId,$server,time());
            if($insert)
            {
                $area = Ext::getInstance()->getArea($server);
                $gameMoney = $amount*10;
                $accountId = $newdb->selectThirdUserAccountId('platform_youlong_user',$area,$uid);
                $username = $newdb->selectThirdUserWzUsername('platform_youlong_user',$area,$uid);
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server, $thirdType, $username, $accountId, $gameMoney, $orderId);
                $newdb->insertUserpaylog($uid, $thirdType, $orderId, $sdkPaytime, $area, $server, $amount, $accountId);
                echo 'OK';
            }
            else
            {
                echo '插入云顶订单失败';
            }
        }
    }
    else
    {
        echo 'OK';
    }
}
