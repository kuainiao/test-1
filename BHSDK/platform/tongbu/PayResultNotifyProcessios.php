<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';
$source   = $_GET['source'];  	    //数据来源
$order_id = $_GET['trade_no'];      //订单id
$partner  = $_GET['partner'];       //游戏编号----同步游戏联运平台为游戏分配的唯一编号	
$amount   = $_GET['amount'];     	//成功充值金额(分)
$paydes   = $_GET['paydes']; 		//支付说明
$debug	  = $_GET['debug'];         //是否调试模式
$tborder  = $_GET['tborder'];       //同步订单号
$sign	  = $_GET['sign'];          //签名----将以上参数加 key 后得到的签名
$Res = pay_result_notify_process($source,$order_id,$partner,$amount,$paydes,$debug,$tborder,$sign);
print_r($Res);

 function pay_result_notify_process($source,$order_id,$partner,$amount,$paydes,$debug,$tborder,$sign)
    {
        $thirdType = 10;
        $key = 'YNm@I$Vi7vERpeLAYN0yH#ti7EcRp3LA';
		$str = explode('_',$paydes);
		$uid = 0;
		if($str&&is_array($str)){
			$uid = $str[0];
			$area_info = $str[1];
		}else{
			die("参数错误");
		}
        if($uid==0){
        	die("参数错误");
        }
		
		if($area_info==''){
			die("参数错误");
		}
        //按照API规范里的说明，把相应的数据进行拼接加密处理
        $sign_check = md5("source=$source&trade_no=$order_id&amount=$amount&partner=$partner&paydes=$paydes&debug=$debug&tborder=$tborder&key=$key");
		
        if($sign_check == $sign)
        {
            $newdb = new PlatModel();
            $selectOrderId = $newdb->selectTbPay($uid,$order_id,'platform_tb_pay');
            //验证是否充值过
            if ($order_id == $selectOrderId)
            {
                echo '{"status":"success"}';
            }
            else
            {
            	$money = (int)$amount/100;
                $sdkPaytime = time();
                $insert = $newdb->insertTbPay($amount,$money,$source,$uid,$order_id,$area_info,$partner,$debug,$sdkPaytime,$channel_type=2);
                if($insert)
                {
                	$area = Ext::getInstance()->getArea($area_info);

                    $gameMoney = $money*10;
                    $accountId = $newdb->selectThirdUserAccountId('platform_tb_user',$area,$uid);
                    $username = $newdb->selectThirdUserWzUsername('platform_tb_user',$area,$uid);
                    $socketClass = new ConnectGameServer();
                    $socketClass->payToGameServer($area_info,$thirdType,$username,$accountId,$gameMoney,$order_id);
					$newdb->insertUserpaylog($uid, $thirdType, $order_id, $sdkPaytime, $area, $area_info, $money, $accountId,2);
                    echo '{"status":"success"}';
                }
                else
                {
                    echo '插入同步游戏平台订单失败';
                }
            }
        }
        else
        {
        	die("签名错误");
        }
    }
?>