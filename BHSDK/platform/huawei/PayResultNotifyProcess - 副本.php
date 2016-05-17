<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../halo/HaloLog.php';
require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';


ksort($_POST);
$sign = $_POST['sign'];


foreach($_POST as $key=>$value)
{
    $i = 0;
    $param .= ($i == 0 ? '' : '&').$key.'='.$value.'&';
    $i++;
}
$log = new HaloLog();
$log->addLog('huawei', $param);



if(empty($sign))
{
    echo "{\"result\":1}";
    return;
}

$money = $_POST['amount'];
$orderId = $_POST['orderId'];
$extReserved = $_POST['extReserved'];
$string_array = explode("_", $extReserved);
$uid = $string_array[0];
$server = $string_array[1];


$content = "";
$i = 0;
foreach($_POST as $key=>$value)
{
    if($key != "sign" )
    {
        $content .= ($i == 0 ? '' : '&').$key.'='.$value;
    }
    $i++;
}
$filename = dirname(__FILE__)."/payPublicKey.pem";

if(!file_exists($filename))
{
    echo "{\"result\" : 1 }";
    return;
}
$pubKey = @file_get_contents($filename);
$openssl_public_key = @openssl_get_publickey($pubKey);
$ok = @openssl_verify($content,base64_decode($sign), $openssl_public_key);
@openssl_free_key($openssl_public_key);

$result = "";

if($ok)
{
    //支付成功处理业务
    $return = pay_result_notify_process($uid,$money,$orderId,$server);
    echo $res = "{ \"result\": $return} ";

}else
{
    $result = "1";
    $res = "{ \"result\": $result} ";
    echo $res;
}



function pay_result_notify_process($uid,$money,$orderId,$server)
{
    $log = new HaloLog();
    $log->addLog('huawei', '$uid='.$uid);
    $log->addLog('huawei', '$money='.$money);
    $log->addLog('huawei', '$orderId='.$orderId);
    $log->addLog('huawei', '$server='.$server);

    $thirdType = 23;

    $newdb = new PlatModel();
    $selectOrderId = $newdb->selectPay($uid,$orderId,'platform_huawei_pay');
    //验证是否充值过
    if ($orderId == $selectOrderId)
    {
        return $result = "0";
    }
    else
    {
        $sdkPaytime = time();
        $insert = $newdb->insertHwPay($money,$uid,$orderId,$server,time());
        if($insert)
        {
            $area = Ext::getInstance()->getArea($server);
            $gameMoney = $money*10;
            $accountId = $newdb->selectThirdUserAccountId('platform_huawei_user',$area,$uid);
            $username = $newdb->selectThirdUserWzUsername('platform_huawei_user',$area,$uid);
            $socketClass = new ConnectGameServer();
            $socketClass->payToGameServer($server, $thirdType, $username, $accountId, $gameMoney, $orderId);
            $newdb->insertUserpaylog($uid, $thirdType, $orderId, $sdkPaytime, $area, $server, $money, $accountId);
            return $result = "0";
        }
        else
        {
            echo '插入华为订单失败';
        }
    }
}
