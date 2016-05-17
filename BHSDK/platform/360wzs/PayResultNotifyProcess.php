<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';
require_once '../../ext/Ext.php';

$request['app_key']         =  $_GET['app_key'];
$request['product_id']     =  $_GET['product_id'];  
$request['amount']          =  $_GET['amount'];       //总价，以分为单位
$request['app_uid']         =  $_GET['app_uid'];     //应用内用户id
$request['app_ext1']       =  $_GET['app_ext1'];   //应用扩展信息
$request['user_id']          =  $_GET['user_id'];      //360账号id
$request['order_id']         =  $_GET['order_id'];   //360返回订单号
$request['gateway_flag'] =  $_GET['gateway_flag'];  //支付成功，返回success
$request['sign_type']       =  $_GET['sign_type'];     
$request['app_order_id'] =  $_GET['app_order_id'];  //应用订单号

			
$Res = pay_result_notify_process($request);
print_r($Res);

 function pay_result_notify_process($request)
    {
        $thirdType = 21;
		$sign_return  =  $_GET['sign_return'];
		$sign              =  $_GET['sign'];
        $app_key        = '63599aa6eb4b6a971f6b307385215eaf';
		$app_secret   = '9eea1483d3cda7f8f11f3a1c74b09167';

		
		//判断appkey
		if($request['app_key'] != $app_key){
			die('应用key值不一致！');
		}

		if($request['gateway_flag'] != 'success'){
			die("支付失败!");
		}
		
		//验证签名
		foreach($request as $k=>$v){
			if(empty($v)){
				unset($request[$k]);
			}
		}
		ksort($request);
		$sign_str = implode('#', $request);
		$sign_str .= '#'.$app_secret;
		$sign_str = strtolower(md5($sign_str));
		if($sign != $sign_str){
			die('请求不合法!');
		}
		
        //判断服
		//$app_ext = Ext::getInstance()->CheckAreaServer($request['app_ext1']);
		$app_ext = $request['app_ext1'];
        $newdb = new PlatModel();
        $selectOrderId = $newdb->selectQhPay($request['user_id'],$request['order_id'],'platform_qhwzs_pay');
        //验证是否充值过
        if ($request['order_id'] == $selectOrderId)
        {
            echo "ok";
        }
        else
        {
        	$sdkPaytime = time();
            $insert = $newdb->insertQhWzsPay($request,$app_ext,$sign_return,$sign,$sdkPaytime);
            if($insert)
            {
            	$area = Ext::getInstance()->getArea($app_ext);
                $gameMoney = $request['amount'] /10;
                $accountId = $newdb->selectThirdUserAccountId('platform_qhwzs_user',$area,$request['user_id']);
                $username = $newdb->selectThirdUserWzUsername('platform_qhwzs_user',$area,$request['user_id']);
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($app_ext, $thirdType, $username, $accountId, $gameMoney, $request['order_id']);
				$newdb->insertUserpaylog($request['user_id'], $thirdType, $request['order_id'], $sdkPaytime, $area, $app_ext, $request['amount'] /100, $accountId);
                echo "ok";
            }
            else
            {
                echo '插入360奇虎订单失败';
            }
        }
        
    }
?>