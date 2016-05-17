<?php

header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__).'/'.'pay/PayEntry.php';


$partner = $_GET['partner'];
$payType = $_GET['payType'];//szpay->手机充值卡,alipay,coin
$gameId = $_GET['gameId'];
$uid = $_GET['uid'];
$orderMoney = $_GET['orderMoney'];
$itemName = $_GET['itemName'];
$cpInfo = $_GET['cpInfo'];
$cardTypeCombine = $_GET['cardTypeCombine'];
$cardMoney = $_GET['cardMoney'];
$cardNum = $_GET['cardNum'];
$cardPwd = $_GET['cardPwd'];



if (!empty($payType)) {
    $newPay = new PayEntry();
    if($payType == 'szpay')
    {
        $newPay->szPay($partner,$gameId, $uid, $orderMoney, $itemName, $cpInfo, $cardTypeCombine, $cardMoney, $cardNum, $cardPwd);
    }
    else if($payType == 'alipay')
    {
        $newPay->aliPay($partner,$gameId, $uid, $orderMoney, $itemName, $cpInfo);
    }
    else
    {
        $newPay->coinPay($partner,$gameId, $uid, $orderMoney, $itemName, $cpInfo);
    }
}

