<?php
header("Content-type: text/html; charset=utf-8");

require_once 'AzSdk.php';
require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';
require_once 'class_desc.php';
//$request = 'cdjjSiOr5sWBUc0kMFoZ6TrGQMAGqmlxo8Svmc121fc5YEaRq3TPKJpiTW/eOPQosDLWFUPt0G1z5Z8SP1EWHoeRFvccRHsq8OYEUvMQswktsySEN5x9o+gtfJB5ZwXPlpxaBlkARGdQymQG7N6s1IFRzSQwWhnpAkXgfEGMHbaxs5aEJhg0rJyoEa3AZvLOsfOy2wDQSX6qTxV2/xwtRARmqV3SndQRx9QlpvJYfUUbfXdWPcZUPsoY+n7lfElmpWPiOYSUejiZkgIstImNktl1Dx3KNLk4o8Svmc121fcWAiUMY2hvKVr3nQaavRwG';
$request = $_POST['data'];
$Res = pay_result_notify_process($request);
//print_r($Res);

 function pay_result_notify_process($request)
    {
        $thirdType = 5;
		$des = new DES();
		$data = $des->decrypt($request);
		//$data = str_replace("'",'"',$data);
		$responseData = json_decode($data,true);
		//var_dump($responseData);die;
		if($responseData!=null){
			$uid     = $responseData['uid'];                 //安智账号id 
			$orderId = $responseData['orderId'];			 //订单号 
			$orderAmount = $responseData['orderAmount'];     //订单金额(单位为分)
			$orderTime   = $responseData['orderTime'];       //支付时间
			$orderAccount = $responseData['orderAccount'];   //支付账号
			$code = $responseData['code'];					 //订单状态 （1 为成功）
			$msg  = $responseData['msg'];					 
			$payAmount = $responseData['payAmount'];		 //实际支付金额(单位为分)
			$cpInfo   = $responseData['cpInfo'];			 //回调信息
			$notifyTime = $responseData['notifyTime'];       //通知时间
			$memo      = $responseData['memo'];				 //备注
		}else{
			die("参数接收错误!");
		}
		$uid = abs(crc32($uid));
		//判断服
        //$cpInfo = Ext::getInstance()->CheckAreaServer($cpInfo);


        if ($code == 1)
        {
            $newdb = new PlatModel();
            $selectOrderId = $newdb->selectAnzhiPay($uid,$orderId,'platform_anzhi_pay');
            //验证是否充值过
            if ($orderId == $selectOrderId)
            {
                echo "success";
            }
            else
            {
            	$sdkPaytime = time();
                $insert = $newdb->insertAnzhiPay($uid,$orderId,$orderAmount/100,$orderTime,$orderAccount,$code,$msg,$payAmount/100,$cpInfo,$notifyTime,$memo,$sdkPaytime);
                if($insert)
                {
                	//获取区
                	$area = Ext::getInstance()->getArea($cpInfo);
                    $gameMoney = $payAmount/10;
                    $accountId = $newdb->selectThirdUserAccountId('platform_anzhi_user',$area,$uid);
                    $username = $newdb->selectThirdUserWzUsername('platform_anzhi_user',$area,$uid);
                    $socketClass = new ConnectGameServer();
                    $socketClass->payToGameServer($cpInfo, $thirdType, $username, $accountId, $gameMoney, $orderId);
					$newdb->insertUserpaylog($uid, $thirdType, $orderId, $sdkPaytime, $area, $cpInfo, $payAmount/100, $accountId);
                    echo "success";
                }
                else
                {
                    echo '插入安智订单失败';
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