<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';
$request['merid']   	= $_GET['MerId'];  	    //商户ID
$request['order_id'] 	= $_GET['OrderId'];     //订单id
$request['money']  		= $_GET['Money'];       //订单金额	
$request['trancode']    = $_GET['TranCode'];    //交易码
$request['encstring']   = $_GET['EncString']; 	//通知加密字符串
$request['paymentFee']	= $_GET['PaymentFee'];  //支付金额
$request['payresult']   = $_GET['PaymentStatusCode'];       //支付状态码 (0为支付成功,只有支付成功才会通知游戏服务器)
$request['note']	  	= $_GET['Note'];        //即支付注释
$Res = pay_result_notify_process($request);


 function pay_result_notify_process($request)
    {
        $thirdType = 11;
        $merchantkey = 'e4559e20725c486f333a6cc3e7cf5735';
		
		$encstring = md5($request['merid'].$request['order_id'].$request['money'].$merchantkey);
		if($request['encstring']!=$encstring){
			die("验证加密失败");
		}
		if($request['payresult']!=0){
			die("支付未成功");
		}
		$str = explode('_',$request['note']);
		$uid = 0;
		if($str&&is_array($str)){
			$uin = $str[0];
			if($uin)
			$uid = abs(crc32($uin));//因为其uid为字符串所以转码
			$server = $str[1];
		}else{
			die("参数错误");
		}
        if($uid==0){
        	die("参数错误");
        }
		
		if($server==''){
			die("参数错误");
		}
        $newdb = new PlatModel();
        $selectOrderId = $newdb->selectRgPay($uid,$request['order_id'],'platform_rg_pay');
        //验证是否充值过
        if ($request['order_id'] == $selectOrderId)
        {
            echo "success";
        }
        else
        {
            $sdkPaytime = time();
            $insert = $newdb->insertRgPay($request,$uid,$uin,$server,$sdkPaytime,$channel_type=1);
            if($insert)
            {
            	$area = Ext::getInstance()->getArea($server);

                $gameMoney = $request['paymentFee']*10;
                $accountId = $newdb->selectThirdUserAccountId('platform_rg_user',$area,$uid);
                $username = $newdb->selectThirdUserWzUsername('platform_rg_user',$area,$uid);
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server,$thirdType,$username,$accountId,$gameMoney,$request['order_id']);
				$newdb->insertUserpaylog($uid, $thirdType, $request['order_id'], $sdkPaytime, $area, $server, $request['paymentFee'], $accountId);
                echo "success";
            }
            else
            {
                echo '插入游戏平台订单失败';
            }
        }
    }
?>