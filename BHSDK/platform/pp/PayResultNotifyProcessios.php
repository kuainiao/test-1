<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';
require_once 'Rsa.php';
require_once 'MyRsa.php';
$request = $_POST;
$Res = pay_result_notify_process($request);
print_r($Res);

 function pay_result_notify_process($request)
    {
        $thirdType = 8;
		$privatedata = $request['sign'];
		//$privatedata = 'WRfX+taw8rxJbt5o+/tsWKbH0AnO0V3uUE19ldSMKxTva6cC0bt4v5KVJ2AE/88ZCDa5/krWhpmtekEGbtLqNoNHXur1PKAzwLh+3XijovbI7FzzLTaZmAvs3YIDrX8Dm12em75B5RNKXoGMqmBs7ztfrYknwcN/wJscbMSlr94sw/LjbBg5IlJUim/el/4Y2xy38fy6iFKa2lzSi2hiA1wNuHS9X6O52RKzZxBhpWkB0JjTjfbza2JRJedsUfuCV6YsTATJNh55t4l53I/kg/PgzM6m91fifWKRT1/HcPg+cmlrZDGKBBuYBw55EbLOXQV0pgeTNn9TOJtMOrLRuw==';
		//var_dump(Rsa::public_key);die;
		
		$privatebackdata = base64_decode($privatedata);
        $MyRsa = new MyRsa;
        $data = $MyRsa->publickey_decodeing($privatebackdata, MyRsa::public_key);
		if($data!=false)
		$rs = json_decode($data,true);
		if(empty($rs)||empty($request))return false;
		if($rs["billno"] == $request['billno']&&$rs["amount"] == $request['amount']&&$rs["status"] == $request['status']){	
			if($rs!=null){		
				$order_id = $rs['order_id'];     //兑换订单号	
				$billno   = $rs['billno'];		 //厂商订单号(20 个字符以内)	
				$account  = $rs['account'];      //通行证账号	
				$amount   = $rs['amount'];       //兑换 PP 币数量	
				$status   = $rs['status'];       //状态:	0 正常状态	1 已兑换过并成功返回	
				$app_id   = $rs['app_id'];		 //厂商应用 ID	
				$roleid   = $rs['roleid'];	     //厂商应用角色 id				 
				$zone     = $rs['zone'];	     //厂商应用分区 id
				$uuid     = $rs['uuid'];
				$money = $amount;  
				$str = explode('_',$billno);
				$uid = 0;
				if($str&&is_array($str)){
					$uid = $str[0];
					$area_info = $str[1];
				}else{
					die("fail");
				}
		        if($uid==0||$area_info==''){
		        	die("fail");
		        }		 
			}else{
				die("参数接收错误!");
			}
		}else{
			die("参数接收错误!");
		}
        //$out_trade_no = Ext::getInstance()->CheckAreaServer($out_trade_no);

        if ($status == 0 || $status == 1)
        {
            $newdb = new PlatModel();
            $selectOrderId = $newdb->selectPpPay($uid,$order_id,'platform_pp_pay');
            //验证是否充值过
            if ($order_id == $selectOrderId)
            {
                echo "success";
            }
            else
            {
            	$sdkPaytime = time();
                $insert = $newdb->insertPpPay($order_id,$area_info,$uid,$account,$amount,$money,$status,$app_id,$roleid,$uuid,$zone,$sdkPaytime,2);
                if($insert)
                {
                    $area = Ext::getInstance()->getArea($area_info);
                    $gameMoney = $money*10;
                    $accountId = $newdb->selectThirdUserAccountId('platform_pp_user',$area,$uid);
                    $username = $newdb->selectThirdUserWzUsername('platform_pp_user',$area,$uid);
                    $socketClass = new ConnectGameServer();
                    $socketClass->payToGameServer($area_info, $thirdType, $username, $accountId, $gameMoney, $order_id);
					$newdb->insertUserpaylog($uid, $thirdType, $order_id, $sdkPaytime, $area, $area_info, $money, $accountId,2);
                    echo "success";
                }
                else
                {
                    echo '插入pp助手订单失败';
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