<?php

header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__).'/'.'pay/CoinPayEntry.php';


$payType = $_GET['payType'];//szpay->手机充值卡,alipay
$uid = $_GET['uid'];
$orderMoney = $_GET['orderMoney'];
$cardTypeCombine = $_GET['cardTypeCombine'];
$cardMoney = $_GET['cardMoney'];
$cardNum = $_GET['cardNum'];
$cardPwd = $_GET['cardPwd'];


if (!empty($payType)) {
    $newPay = new PayEntry();
    if($payType == 'szpay')
    {
        $newPay->szPay($uid, $orderMoney, $cardTypeCombine, $cardMoney, $cardNum, $cardPwd);
    }
    else
    {
        $newPay->aliPay($uid, $orderMoney);
    }
}

