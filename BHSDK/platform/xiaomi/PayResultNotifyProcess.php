<?php
header("Content-type: text/html; charset=utf-8");

require_once 'XmSdk.php';
require_once '../../models/XmModel.php';
require_once '../../socket/ConnectServer.php';

$appId = $_GET['appId'];
$cpOrderId = $_GET['cpOrderId'];
$cpUserInfo = $_GET['cpUserInfo'];
$uid = $_GET['uid'];
$orderId = $_GET['orderId'];
$orderStatus = $_GET['orderStatus'];
$payFee = $_GET['payFee'];
$productCode = $_GET['productCode'];
$productName = $_GET['productName'];
$productCount = $_GET['productCount'];
$payTime = $_GET['payTime'];
$signature = $_GET['signature'];
$orderConsumeType = $_GET['orderConsumeType'];
foreach($_REQUEST as $key=>$value)
{
    $i = 0;
    $param .= ($i == 0 ? '' : '&').$key.'='.$value.'&';
    $i++;
}
$log = new HaloLog();
$log->addLog('xiaomkkekwke', $param);

XmSdk::pay_result_notify_process($appId, $cpOrderId, $cpUserInfo, $uid, $orderId, $orderStatus, $payFee, $productCode, $productName, $productCount, $payTime,$orderConsumeType , $signature);

?>