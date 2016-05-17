<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';
require_once 'IappDecrypt.php';

$trans_data = $_POST['transdata'];//同步过来的transdata 数据
$private_key = 'QzJFQUY5NjgzNzRGNkUzMDFDNjVFREUwNTAxODJEOUU2Mzc3NDk2NU1UYzNOVEl3TmpJeE16QXhOVGMwT0RNMU9EY3JNVGczT0Rjd05qQXlNelV3TkRnMU5qQTROemd4TXpnNE1qazVOalUzTmpBek9EUTJOamt4';
$sign = $_POST['sign'];//同步过来的sign 数据

$tools = new IappDecrypt();
$result = $tools->validsign($trans_data,$sign,$private_key);
if($result != 0){
	//验签名失败
	die('FAILED');
}
$data = json_decode($trans_data,true);
//var_dump($data);die;
$request['order_id']    = $data['exorderno'];    //商户订单号
$request['transid']     = $data['transid'];      //计费支付平台的交易流水号
$request['waresid']   	= $data['waresid']; 	 //平台为应用内需计费商品分配的编号
$request['appid']		= $data['appid'];  		 //平台为商户应用分配的唯一编号
$request['feetype']     = $data['feetype'];      //计费类型
$request['money']	  	= $data['money'];        //本次交易的金额，单位：分
$request['count']	  	= $data['count'];        //本次购买的商品数量 
$request['result']	  	= $data['result'];       //交易结果
$request['transtype']	= $data['transtype'];    //交易类型
$request['transtime']	= $data['transtime'];    //交易时间
$request['cpprivate']	= $data['cpprivate'];    //商户私有信息 

$Res = pay_result_notify_process($request);


 function pay_result_notify_process($request)
    {
        $thirdType = 15;
		if($request['result']!=0){
			die('FAILED');
		}
		$str = explode('_',$request['cpprivate']);
		$uid = 0;
		if($str&&is_array($str)){
			$uid = $str[0];
			$server = $str[1];
		}else{
			die('参数错误');
		}
        if($uid==0){
        	die('参数错误');
        }
		if($server==''){
			die('参数错误');
		}
        $newdb = new PlatModel();
        $selectOrderId = $newdb->selectPayOrder($uid,$request['order_id'],'platform_lenovo_pay');
        //验证是否充值过
        if ($request['order_id'] == $selectOrderId)
        {
            die('SUCCESS');
        }
        else
        {
            $sdkPaytime = time();
            $insert = $newdb->insertLxPay($request,$uid,$server,$sdkPaytime,$channel_type=1);
            if($insert)
            {
            	$area = Ext::getInstance()->getArea($server);
				
                $gameMoney = $request['money']/10;
                $accountId = $newdb->selectThirdUserAccountId('platform_lenovo_user',$area,$uid);
                $username = $newdb->selectThirdUserWzUsername('platform_lenovo_user',$area,$uid);
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server,$thirdType,$username,$accountId,$gameMoney,$request['order_id']);
				$newdb->insertUserpaylog($uid, $thirdType, $request['order_id'], $sdkPaytime, $area, $server, $request['money']/100, $accountId);
                die('SUCCESS');
            }
            else
            {
            	die('插入游戏平台订单失败');
            }
        }
    }
?>