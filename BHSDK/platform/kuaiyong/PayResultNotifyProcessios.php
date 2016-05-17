<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';
require_once 'Rsa.php';
$request['notify_data']   = $_POST['notify_data'];  	    //RSA加密的关键数据
$request['orderid']  = $_POST['orderid'];      				//快用平台订单号
$request['dealseq']  = $_POST['dealseq'];       			//游戏订单号
$request['uid']     = $_POST['uid']; 
$request['subject']  = $_POST['subject']; 					//购买物品名
$request['v']	  = $_POST['v'];         					//版本号
$request['sign']  = $_POST['sign'];       					//RSA签名

$Res = pay_result_notify_process($request);

function pay_result_notify_process($request)
    {
        $thirdType = 12;
		if($request['sign']==""){
			die("failed");
		}
		$uid = abs(crc32($request['uid']));//因为其uid为字符串所以转码
		//对签名做base64解码
		$post_sign = base64_decode($request['sign']);
		
		/*$notify_data = $request['notify_data'];
		$orderid=$request['orderid'];
		$dealseq=$request['dealseq'];
		$uid   = $request['uid'];
		$subject = $request['subject'];
		$v = $request['v'];
        $signstr = "dealseq=$dealseq&notify_data=$notify_data&orderid=$orderid&subject=$subject&uid=$uid&v=$v";*/
        //var_dump($signstr);die;
		
		ksort($request);
		$signstr="";
		foreach ($request as $key => $val) {
			if($key!='sign'){
				$signstr==""?$signstr=$key."=".$val:$signstr.="&".$key."=".$val;
			}
		}
		$public_key = Rsa::instance()->convert_publicKey(Rsa::public_key);
		$check = Rsa::instance()->verify($signstr,$post_sign,$public_key);
		//var_dump($check);die;
		if($check!=1){
			die("failed");
		}
		//对加密的notify_data进行解密
		$post_notify_data = base64_decode($request['notify_data']);
	    $notify_data = Rsa::instance()->publickey_decodeing($post_notify_data,$public_key);
		parse_str($notify_data,$parse);
		$dealseq= $parse['dealseq'];
		$fee= $parse['fee'];//实际支付金额，注意：卡支付时可能出现成功支付额与订单金额不一致的情况，请根据自身业务决定是否按比例做充值。
		$payresult= $parse['payresult'];

		//比较解密出的数据中的dealseq和参数中的dealseq是否一致
		if($dealseq==$request['dealseq']){
			$str = explode('_',$request['dealseq']);
			if($str&&is_array($str)){
				$server = (int)$str[0];
			}else{
				die("参数错误");
			}
			if($server==''){
				die("参数错误");
			}
			if($payresult!=0){
				die("success");
			}
			$newdb = new PlatModel();
            $selectOrderId = $newdb->selectKyPay($uid,$request['orderid'],'platform_ky_pay');
            //验证是否充值过
            if ($request['orderid'] == $selectOrderId)
            {
                echo "success";
            }
            else
            {
                $sdkPaytime = time();
                $insert = $newdb->insertKyPay($request,$fee,$payresult,$uid,$server,$sdkPaytime,$channel_type=2);
                if($insert)
                {
                	$area = Ext::getInstance()->getArea($server);
                    $gameMoney = $fee*10;
                    $accountId = $newdb->selectThirdUserAccountId('platform_ky_user',$area,$uid);
                    $username = $newdb->selectThirdUserWzUsername('platform_ky_user',$area,$uid);
                    $socketClass = new ConnectGameServer();
                    $socketClass->payToGameServer($server,$thirdType,$username,$accountId,$gameMoney,$request['orderid']);
					$newdb->insertUserpaylog($uid, $thirdType, $request['orderid'], $sdkPaytime, $area, $server, $fee, $accountId,2);
					echo "success";
                }
                else
                {
                    echo '插入游戏平台订单失败';
                }
            }
		}else{
			die("failed");
		}
    }
?>