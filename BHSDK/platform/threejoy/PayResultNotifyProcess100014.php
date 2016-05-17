<?php
header("Content-type: text/html; charset=utf-8");

require_once('../../models/TJModel.php');
require_once '../../socket/ConnectServer.php';
require_once '../../models/UserLoginModel.php';
require_once '../../ext/Ext.php';
require_once "../../halo/HaloLog.php";

$myGameId = 100014;
$myGameKey = 'fg2dynhuy3ihc5rwk5uo3sqhy71zhruz4wzfnrm2';

/*var_dump($_GET);
die();*/

echo $Res = pay_result_notify_process($myGameId,$myGameKey);


function pay_result_notify_process($myGameId,$myGameKey)
{
    $thirdType = 27;
	$Result = array();//存放结果数组
	
	if(empty($_GET)||!isset($_GET['gameId'])||!isset($_GET['uid'])||!isset($_GET['cpInfo'])||!isset($_GET['itemName'])
        ||!isset($_GET['orderMoney'])||!isset($_GET['orderId'])||!isset($_GET['orderCreateTime'])||!isset($_GET['payTime'])
        ||!isset($_GET['payStatus'])||!isset($_GET['sign']))
    {
		$Result["ErrorCode"] =  "0";//注意这里的错误码一定要是字符串格式
		$Result["ErrorDesc"] =  urlencode("接收失败");
		$Res = json_encode($Result);
		return urldecode($Res);
	}

    $gameId             = $_GET['gameId'];
    $uid                = $_GET['uid'];
    $cpInfo             = $_GET['cpInfo'];
    $itemName           = $_GET['itemName'];//产品名称
    $orderMoney         = $_GET['orderMoney'];//订单钱数
    $orderId            = $_GET['orderId'];//订单id
    $orderCreateTime    = $_GET['orderCreateTime'];//订单创建时间
    $payTime            = $_GET['payTime'];//订单返回成功给CP的时间
    $payStatus          = $_GET['payStatus'];//订单状态
    $sign               = $_GET['sign'];//加密串


	if($myGameId != $gameId)
    {
		$Result["ErrorCode"] =  "2";//注意这里的错误码一定要是字符串格式
		$Result["ErrorDesc"] =  urlencode("gameId无效");
		$Res = json_encode($Result);
		return urldecode($Res);
	}
	
    $sign_check = md5($gameId.$itemName.$orderMoney.$orderId.$cpInfo.$orderCreateTime.$payTime.$myGameKey);


    if($sign_check == $sign)
    {
        if ($payStatus == 1)
        {
            $newDb = new TJModel();
            $selectCooOrderSerial = $newDb->selectPay($uid, $orderId);
            //验证是否充值过
            if ($orderId == $selectCooOrderSerial)
            {
                $Result["ErrorCode"] =  "1";//注意这里的错误码一定要是字符串格式
                $Result["ErrorDesc"] =  urlencode("接收成功");
                $Res = json_encode($Result);
                return urldecode($Res);
            }
            else
            {
                $sdkPayTime = time();
                $insert = $newDb->insertPay($uid, $cpInfo, $itemName, $orderMoney, $orderId, $orderCreateTime, $payTime, $sdkPayTime);
                if($insert)
                {
                	$area = Ext::getInstance()->getArea($cpInfo);
                    $gameMoney = $orderMoney*10;
                    $accountId = $newDb->selectThreeJoyUserAccountId($area, $uid);
                    $username = $newDb->selectThreeJoyUserWzUsername($area, $uid);
                    $socketClass = new ConnectGameServer();
                    $socketClass->payToGameServer($cpInfo, $thirdType, $username, $accountId, $gameMoney, $orderId);
					$newUser = new UserLoginModel();
					$newUser->insertUserpaylog($uid, $thirdType, $orderId, $sdkPayTime, $area, $cpInfo, $orderId, $accountId);
                    $Result["ErrorCode"] =  "1";//注意这里的错误码一定要是字符串格式
                    $Result["ErrorDesc"] =  urlencode("接收成功");
                    $Res = json_encode($Result);
                    return urldecode($Res);
                }
                else
                {
                    echo '插入nsky订单失败';
                }
            }
        }
		else
        {
            $Result["ErrorCode"] =  "4";//注意这里的错误码一定要是字符串格式
            $Result["ErrorDesc"] =  urlencode("参数无效");
            $Res = json_encode($Result);
            return urldecode($Res);
        }
	}
    else
    {
		$Result["ErrorCode"] =  "5";//注意这里的错误码一定要是字符串格式
		$Result["ErrorDesc"] =  urlencode("Sign无效");
		$Res = json_encode($Result);
		return urldecode($Res);
	}
}
?>