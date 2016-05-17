<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yang
 * Date: 15-2-6
 * Time: 10:31
 * To change this template use File | Settings | File Templates.
 */
require_once './models/PayModel.php';

$myPass = 'sfjklipognnfdjnjflgsndllgndsjh';
$pass = $_GET['pass'];
$type = $_GET['type'];


if($pass == $myPass){
    $partner = $_GET['partner'];
    $gameId = 115;
    $uid = $_GET['uid'];
    $orderId = $_GET['orderId'];
    $orderMoney = $_GET['orderMoney'];
    $itemName = '元宝';
    $cpInfo = $_GET['cpInfo'];
    $orderStatus = 2;
    $createTime = $_GET['createTime'];
    $payTime = $_GET['payTime'];

    $pay = new PayModel();
    if($type == 'alipay'){
        $pay->insertAliPayOrder($partner,$gameId,$uid,$orderId,$orderMoney,$itemName,$cpInfo,$orderStatus,$createTime,$payTime);
    }
    else if($type == 'szpay'){
        $cardType = $_GET['cardType'];
        $cardMoney = $_GET['cardMoney'];
        $privateField = $_GET['privateField'];
        $pay->insertSZPayOrder($partner,$gameId,$uid,$orderId,$orderMoney,$itemName,$cpInfo,$cardType,$cardMoney,$privateField,$orderStatus,$createTime,$payTime);
        echo 'ok';
    }
    else{
        $pay->insertAliPayOrder($partner,$gameId,$uid,$orderId,$orderMoney,$itemName,$cpInfo,1,$createTime,$payTime);
        echo 'ok';
    }
}
