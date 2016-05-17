<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';

$app_id = 6061;
$app_order_id = $_GET['apporderid'];
$app_money = $_GET['appmoney'];
$money = $_GET['money'];
$create_time = $_GET['createtime'];
$usr_id = $_GET['uid'];
$server = $_GET['serv_id'];
$sign = $_GET['sign'];
$account_id = $_GET['player_id'];

pay_result_notify_process($app_id,$app_order_id,$app_money,$money,$create_time,$usr_id,$server,$sign,$account_id);

function pay_result_notify_process($app_id,$app_order_id,$app_money,$money,$create_time,$usr_id,$server,$sign,$account_id)
{
    //$thirdType = 22;
    $app_key = '4Sxhrj7nJ04nRrei8pi86dCM02l81StE';

    //按照API规范里的说明，把相应的数据进行拼接加密处理
    $my_sign = md5($app_id.$app_order_id.$usr_id.$app_money.$money.$create_time.$app_key);

    if($my_sign == $sign)
    {
        $newdb = new PlatModel();
        $selectOrderId = $newdb->selectPay($usr_id,$app_order_id,'platform_yunding_pay');
        //验证是否充值过
        if ($app_order_id == $selectOrderId)
        {
            $array = array("success"=>"0", "desc"=>"$app_order_id");
            echo json_encode($array);
        }
		
        else
        {	
        	$area = Ext::getInstance()->getArea($server);
        	$accountId = $newdb->selectThirdUserAccountId('platform_yunding_user',$area,$usr_id);
			if($accountId == $account_id)
			{
            	$sdkPaytime = time();
            	$insert = $newdb->insertYdPay($money,$usr_id,$app_order_id,$server,time());
            	if($insert)
            	{
                	$area = Ext::getInstance()->getArea($server);
                	$gameMoney = $app_money;
					$thirdType = 22;
                	//$accountId = $newdb->selectThirdUserAccountId('platform_yunding_user',$area,$usr_id);
                	$username = $newdb->selectThirdUserWzUsername('platform_yunding_user',$area,$usr_id);
                	$socketClass = new ConnectGameServer();
                	$socketClass->payToGameServer($server, $thirdType, $username, $account_id, $gameMoney, $app_order_id);
                	$newdb->insertUserpaylog($usr_id, $thirdType, $app_order_id, $sdkPaytime, $area, $server, $money, $account_id);
                	$array = array("success"=>"1", "desc"=>"充值成功");
                	echo json_encode($array);
            	}
            	else
            	{
                	echo '插入云顶订单失败';
            	}
            }
           else
           {	file_put_contents('/tmp/teste','ssssss');
            	$sdkPaytime = time();
            	$insert = $newdb->insertchuanqibayePay($money,$usr_id,$app_order_id,$server,time());
            	if($insert)
            	{
                	$area = Ext::getInstance()->getArea($server);
                	$gameMoney = $app_money;
					$thirdType = 30;
                	$accountId = $newdb->selectThirdUserAccountId('platform_chuanqibaye_user',$area,$usr_id);
                	$username = $newdb->selectThirdUserWzUsername('platform_chuanqibaye_user',$area,$usr_id);
                	$socketClass = new ConnectGameServer();
                	$socketClass->payToGameServer($server, $thirdType, $username, $accountId, $gameMoney, $app_order_id);
                	$newdb->insertUserpaylog($usr_id, $thirdType, $app_order_id, $sdkPaytime, $area, $server, $money, $accountId);
                	$array = array("success"=>"1", "desc"=>"充值成功");
                	echo json_encode($array);
            	}
            	else
            	{
                	echo '插入云顶订单失败';
            	}

            }
            
        }
    }
    else
    {
        $array = array("success"=>"0", "desc"=>"sign错误");
        echo json_encode($array);
    }
}
