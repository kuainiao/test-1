<?php
header("Content-type: text/html; charset=utf-8");

require_once('../../models/AiyouxiModel.php');
require_once '../../socket/ConnectServer.php';
require_once '../../models/UserLoginModel.php';
require_once '../../ext/Ext.php';
require_once "../../halo/HaloLog.php";

ksort($_POST);



			
foreach($_POST as $key=>$value)
{
    $i = 0;
    $param .= ($i == 0 ? '' : '&').$key.'='.$value.'&';
    $i++;
}
$log = new HaloLog();
$log->addLog('aiyouxi2', $param);

$AppSecret = "6afae0c0702ff92787d3d32fe4b39c08";
echo $Res = pay_result_notify_process($AppSecret);
    function pay_result_notify_process($AppSecret)
    {
        $thirdType = 31;
		$method = $_POST['method'];
		
		/*if ($method == null)
		{
		   echo 1234567;
		   exit();
		}*/
		
		$method1 = "check";
		$method2 = "callback";
		
		if($method == $method1)
		{
		//IF1
		$OrderId = $_POST['cp_order_id'];
		$SerialNo = $_POST['correlator'];
		$OrderStamp = $_POST['order_time'];
		$Sign = $_POST["sign"];
								
				 if ($OrderId==null || $SerialNo==null || $OrderStamp == null || $Sign == null)
				{
					echo 222222222;
					exit();
				}		 
				
				$mddata = MD5($OrderId.$SerialNo.$OrderStamp.$method.$AppSecret);
				if ($mddata == $Sign) 
				{

				echo htmlspecialchars('<sms_pay_check_resp><cp_order_id>');
				echo $OrderId;
				echo htmlspecialchars('</cp_order_id><correlator>');
				echo $SerialNo;
				echo htmlspecialchars('</correlator><game_account>');
				echo $SerialNo;
				echo htmlspecialchars('</game_account><fee>');
				echo $fee;
				echo htmlspecialchars('</fee><if_pay><if_pay>0</if_pay><order_time>');
				echo $time;
				echo htmlspecialchars('</order_time></sms_pay_check_resp>');
				} 
		}
		elseif($method == $method2)
		{
						//IF2
						$cp_order_id = $_POST['cp_order_id'];
						$cp_arr = explode("_", $cp_order_id);
						$uid = $cp_arr[1];
						$cpInfo = $cp_arr[2];
						$orderCreateTime = $cp_arr[0];
						$OrderId = $_POST['cp_order_id'];
						$SerialNo = $_POST['correlator'];
						$ResultCode = $_POST['result_code'];
						$ResultMsg = $_POST['result_desc'];
						$PayFee = $_POST['fee'];
						$PayType= $_POST['pay_type'];
						$Sign = $_POST['sign'];
						$itemName = "元宝";
						
					
						//验证签名
							$mddata2 = MD5($OrderId.$SerialNo.$ResultCode.$PayFee.$PayType.$method.$AppSecret);
							if ($mddata2 == $Sign) 
							{
							
										$newDb = new AiyouxiModel();
										$selectCooOrderSerial = $newDb->selectPay($uid, $OrderId);
										
										$sdkPayTime = time();
										
										$insert = $newDb->insertPay($uid, $cpInfo, $itemName,$PayFee, $OrderId, $sdkPayTime, $sdkPayTime, $sdkPayTime);
										if($insert)
										{
											$area = Ext::getInstance()->getArea($cpInfo);
											$gameMoney = $PayFee*10;
											$accountId = $newDb->selectAiyouxiUserAccountId($area, $uid);
											$username = $newDb->selectAiyouxiUserWzUsername($area, $uid);
											$socketClass = new ConnectGameServer();
											$socketClass->payToGameServer($cpInfo, $thirdType, $username, $accountId, $gameMoney, $OrderId);
											$newUser = new UserLoginModel();
											$newUser->insertUserpaylog($uid, $thirdType, $OrderId, $sdkPayTime, $area, $cpInfo, $gameMoney, $accountId);
											$Result["ErrorCode"] =  "1";//注意这里的错误码一定要是字符串格式
											$Result["ErrorDesc"] =  urlencode("接收成功");
											$Res = json_encode($Result);
											echo htmlspecialchars('<cp_notify_resp><h_ret>0</h_ret><cp_order_id>');
											echo $OrderId;
											echo htmlspecialchars('</cp_order_id></cp_notify_resp>');
										}
										else
										{
											//echo '插入nsky订单失败';
											echo htmlspecialchars('<cp_notify_resp><h_ret>1</h_ret><cp_order_id>');
											echo $OrderId;
											echo htmlspecialchars('</cp_order_id></cp_notify_resp>');
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
	
	
	
	
	/*else
    {
		$Result["ErrorCode"] =  "5";//注意这里的错误码一定要是字符串格式
		$Result["ErrorDesc"] =  urlencode("Sign无效");
		$Res = json_encode($Result);
		return urldecode($Res);
	}*/
	
				

			

            
?>