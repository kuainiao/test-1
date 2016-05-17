<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';
require_once '../../ext/Ext.php';

$request['oid']    =  $_GET['oid'];   //订单号
$request['uid']    =  $_GET['uid'];  
$request['money']  =  $_GET['amount'];     
$request['amount'] =  $_GET['amount'];   //订单金额，两位小数
$request['coins']  =  $_GET['coins'];     //订单金币
$request['dtime']  =  $_GET['dtime'];   //订单完成时间
$ext			   = json_decode($_GET['ext'],true);

if(strrpos($ext['partnersorderid'],'_')===false){
	$request['server'] =  $ext['partnersorderid'];
	$request['channel_type'] = 1;   
}else{
	$extstr = explode('_',$ext['partnersorderid']);
	$request['server']       =  $extstr[0]; 
	$request['channel_type'] =  $extstr[1]; 
} 
			
$Res = pay_result_notify_process($request);
print_r($Res);

 function pay_result_notify_process($request)
    {
        $thirdType = 9;
        $key       = "32d67a4932d5801a6fff0a220f377f27";
		if($request['channel_type']==4){
			$key   = "c27bde45b1cf926549475622c811bda5";
		}
		$sign      =  $_GET['sign'] ;
		
		if(empty($request['server'])){
			exit('参数错误!');
		}
		
		//验证签名
		$signflag = md5($request['oid'].$request['uid'].$request['amount'].$request['coins'].$request['dtime'].$key);
        if($sign != $signflag){
            die("参数错误！");
        }
		
        $newdb = new PlatModel();
        $selectOrderId = $newdb->selectKLPay($request['uid'],$request['oid'],'platform_kunlun_pay');
		//返回值
		$backarray = array('retcode'=>0,'retmsg'=>'success');
		
        //验证是否充值过
        if ($request['oid'] == $selectOrderId)
        {
            exit(json_encode($backarray));
        }
        else
        {
        	$sdkPaytime = time();
            $insert = $newdb->insertKLPay($request,$request['server'],$sdkPaytime,$request['channel_type']);
            if($insert)
            {
            	$area = Ext::getInstance()->getArea($request['server']);
               
                $accountId = $newdb->selectThirdUserAccountId('platform_kunlun_user',$area,$request['uid']);
                $username = $newdb->selectThirdUserWzUsername('platform_kunlun_user',$area,$request['uid']);
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($request['server'], $thirdType, $username, $accountId, $request['coins'], $request['oid']);
				$newdb->insertUserpaylog($request['uid'], $thirdType, $request['oid'], $sdkPaytime, $area, $request['server'], $request['money'], $accountId,$request['channel_type']);
                exit(json_encode($backarray));
            }
            else
            {
                $backarray['retcode'] = -1;
				$backarray['retmsg']  = '支付失败';
				exit(json_encode($backarray));
            }
        }
        
    }
?>