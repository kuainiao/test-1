<?php
header("Content-type: text/html; charset=utf-8");

require_once('../../models/NineOneModel.php');
require_once '../../socket/ConnectServer.php';
require_once '../../models/UserLoginModel.php';
require_once '../../ext/Ext.php';


$MyAppId = 105743;
$MyAppKey = 'db5ddfa8bfda35d6f29f91ef3d944d49c319b953be263242';

$Res = pay_result_notify_process($MyAppId,$MyAppKey);

print_r($Res);


function pay_result_notify_process($MyAppId,$MyAppKey)
{
    $thirdType = 0;
	$Result = array();//存放结果数组
	
	if(empty($_GET)||!isset($_GET['AppId'])||!isset($_GET['Act'])||!isset($_GET['ProductName'])||!isset($_GET['ConsumeStreamId'])
		||!isset($_GET['CooOrderSerial'])||!isset($_GET['Uin'])||!isset($_GET['GoodsId'])||!isset($_GET['GoodsInfo'])||!isset($_GET['GoodsCount'])||!isset($_GET['OriginalMoney'])
		||!isset($_GET['OrderMoney'])||!isset($_GET['Note'])||!isset($_GET['PayStatus'])||!isset($_GET['CreateTime'])||!isset($_GET['Sign']))
    {
		$Result["ErrorCode"] =  "0";//注意这里的错误码一定要是字符串格式
		$Result["ErrorDesc"] =  urlencode("接收失败");
		$Res = json_encode($Result);
		return urldecode($Res);
	}
	
	$AppId 				= $_GET['AppId'];//应用ID
	$Act	 			= $_GET['Act'];//操作
	$ProductName		= $_GET['ProductName'];//应用名称
	$ConsumeStreamId	= $_GET['ConsumeStreamId'];//消费流水号
	$CooOrderSerial	 	= $_GET['CooOrderSerial'];//商户订单号
	$Uin			 	= $_GET['Uin'];//91帐号ID
	$GoodsId		 	= $_GET['GoodsId'];//商品ID
    $GoodsInfo		 	= $_GET['GoodsInfo'];//商品名称
    $GoodsCount		 	= $_GET['GoodsCount'];//商品数量
    $OriginalMoney	 	= $_GET['OriginalMoney'];//原始总价（格式：0.00）
    $OrderMoney		 	= $_GET['OrderMoney'];//实际总价（格式：0.00）
    $Note			 	= $_GET['Note'];//支付描述
    $PayStatus		 	= $_GET['PayStatus'];//支付状态：0=失败，1=成功
    $CreateTime		 	= $_GET['CreateTime'];//创建时间
    $Sign		 		= $_GET['Sign'];//91服务器直接传过来的sign
	
	if($Act != 1)
    {
		$Result["ErrorCode"] =  "3";//注意这里的错误码一定要是字符串格式
		$Result["ErrorDesc"] =  urlencode("Act无效");
		$Res = json_encode($Result);
		return urldecode($Res);
	}
	
	if($MyAppId != $AppId)
    {
		$Result["ErrorCode"] =  "2";//注意这里的错误码一定要是字符串格式
		$Result["ErrorDesc"] =  urlencode("AppId无效");
		$Res = json_encode($Result);
		return urldecode($Res);
	}
	
    $sign_check = md5($MyAppId.$Act.$ProductName.$ConsumeStreamId.$CooOrderSerial.$Uin.$GoodsId.$GoodsInfo.$GoodsCount.$OriginalMoney.$OrderMoney.$Note.$PayStatus.$CreateTime.$MyAppKey);

    if ($Note == '元宝充值')
    {
        $Note = 101;
    }

    if($sign_check == $Sign)
    {
        if ($PayStatus == 1)
        {
            $newdb = new NineOneModel();
            $selectCooOrderSerial = $newdb->selectPay($Uin, $CooOrderSerial);
            //验证是否充值过
            if ($CooOrderSerial == $selectCooOrderSerial)
            {
                echo "ErrorCode=1";
            }
            else
            {
                $sdkPaytime = time();
                $insert = $newdb->insertPay($Uin, $ConsumeStreamId, $CooOrderSerial, $GoodsId, $GoodsInfo, $GoodsCount, $OriginalMoney, $OrderMoney, $Note, $PayStatus, $CreateTime, $sdkPaytime,$channel_type=2);
                if($insert)
                {
                        $area = Ext::getInstance()->getArea($Note);
                        $gameMoney = $OrderMoney*10;
                        $accountId = $newdb->selectNineOneUserAccountId($area, $Uin);
                        $username = $newdb->selectNineOneUserWzUsername($area, $Uin);
                        $socketClass = new ConnectGameServer();
                        $socketClass->payToGameServer($Note, $thirdType, $username, $accountId, $gameMoney, $CooOrderSerial);
                        $newUser = new UserLoginModel();
                        $newUser->insertUserpaylog($Uin, $thirdType, $CooOrderSerial, $sdkPaytime, $area, $Note, $OrderMoney, $accountId,2);
                        $Result["ErrorCode"] =  "1";//注意这里的错误码一定要是字符串格式
                        $Result["ErrorDesc"] =  urlencode("接收成功");
                        $Res = json_encode($Result);
                        return urldecode($Res);
                }
                else
                {
                    echo '插入91订单失败';
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