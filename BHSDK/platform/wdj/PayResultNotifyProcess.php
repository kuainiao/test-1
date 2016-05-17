<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';
require_once 'rsa.php';
$request['signType'] = $_POST['signType'];
$request['sign']     = $_POST['sign'];
$request['content']  = $_POST['content'];
/*$newdb = new PlatModel();
$insert1 = $newdb->insertAnzhiPays($request['signType'].'++++'.$request['sign'].'+xxx+'.$request['content']);*/
$Res = pay_result_notify_process($request);
print_r($Res);

 function pay_result_notify_process($request)
    {
        $thirdType = 6;
		if($request!=array()){
			$signType = $request['signType'];
			$sign     =     $request['sign'];
			$responseData = json_decode($request['content'],true);
			if($responseData!=null){		
				$timeStamp     = $responseData['timeStamp'];     //时间戳 
				$orderId = $responseData['orderId'];			 //豌豆荚订单id
				$money = $responseData['money'];     			 //支付金额(单位为分)
				$chargeType   = $responseData['chargeType'];     //支付类型ALIPAY：支付宝SHENZHOUPAY：充值卡BALANCEPAY：余额CREDITCARD : 信用卡DEBITCARD：借记卡
				$appKeyId = $responseData['appKeyId'];           //appKeyId
				$buyerId = $responseData['buyerId'];		     //购买人的账户id
				$out_trade_no  = $responseData['out_trade_no'];	 //开发者订单号			 
				$cardNo = $responseData['cardNo'];		         //充值卡id
				$code = 'success';
			}else{
				die("参数接收错误!");
			}
		}else{
			die("参数接收错误!");
		}
		$rsa=new Rsa;
		$check=$rsa->check($request['content'],$sign);
		if($check==false){
			die("验证签名失败!");			
		}
        //$out_trade_no = Ext::getInstance()->CheckAreaServer($out_trade_no);

        if ($code == 'success')
        {
            $newdb = new PlatModel();
            $selectOrderId = $newdb->selectWdjPay($buyerId,$orderId,'platform_wdj_pay');
            //验证是否充值过
            if ($orderId == $selectOrderId)
            {
                echo "success";
            }
            else
            {
            	$sdkPaytime = time();
				$moneys = $money/100;
                $insert = $newdb->insertWdjPay($buyerId,$orderId,$moneys,$timeStamp,$chargeType,$code,$out_trade_no,$cardNo,$sdkPaytime);
                if($insert)
                {
                    $area = Ext::getInstance()->getArea($out_trade_no);
                    $gameMoney = $money/10;
                    $accountId = $newdb->selectThirdUserAccountId('platform_wdj_user',$area,$buyerId);
                    $username = $newdb->selectThirdUserWzUsername('platform_wdj_user',$area,$buyerId);
                    $socketClass = new ConnectGameServer();
                    $socketClass->payToGameServer($out_trade_no, $thirdType, $username, $accountId, $gameMoney, $orderId);
					$newdb->insertUserpaylog($buyerId, $thirdType, $orderId, $sdkPaytime, $area, $out_trade_no, $money/100, $accountId);
                    echo "success";
                }
                else
                {
                    echo '插入豌豆荚订单失败';
                }
            }
        }
        else
        {
            //
            echo "无效订单";
        }
    }
?>