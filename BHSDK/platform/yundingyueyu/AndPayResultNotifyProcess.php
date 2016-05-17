<?php
header("Content-type: text/html; charset=utf-8");

require_once 'YdSdk.php';
require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';


$serv_id = $_GET['serv_id'];
$usr_id = $_GET['usr_id'];
$player_id = $_GET['player_id'];
$app_order_id = $_GET['app_order_id'];
$coin = $_GET['coin'];
$money = $_GET['money'];
$add_time = $_GET['add_time'];
$sign = $_GET['sign'];
$server = $_GET['payExpandData'];

pay_result_notify_process($serv_id,$usr_id,$player_id,$app_order_id,$coin,$money,$add_time,$sign,$server);

function pay_result_notify_process($serv_id,$usr_id,$player_id,$app_order_id,$coin,$money,$add_time,$sign,$server)
{
    $thirdType = 29;
    $app_id = '3063';
    $app_key = 'H9V8D3EXmc3340V26x1Fzy3iHCFo7GA8';

    //按照API规范里的说明，把相应的数据进行拼接加密处理
    $my_sign = md5($app_id.$serv_id.$usr_id.$player_id.$app_order_id.$coin.$money.$add_time.$app_key);


    if($my_sign == $sign)
    {
        $newdb = new PlatModel();
        $selectOrderId = $newdb->selectPay($usr_id,$app_order_id,'platform_yundingyueyu_pay');
        //验证是否充值过
        if ($app_order_id == $selectOrderId)
        {
            $array = array("success"=>"0", "desc"=>"$app_order_id");
            echo json_encode($array);
        }
        else
        {
            $sdkPaytime = time();
            $insert = $newdb->insertYdyueyuPay($money,$usr_id,$app_order_id,$serv_id,time());
            if($insert)
            {
                $area = Ext::getInstance()->getArea($serv_id);
                $gameMoney = $money*10;
                $accountId = $newdb->selectThirdUserAccountId('platform_yundingyueyu_user',$area,$usr_id);
                $username = $newdb->selectThirdUserWzUsername('platform_yundingyueyu_user',$area,$usr_id);
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($serv_id, $thirdType, $username, $accountId, $gameMoney, $app_order_id);
                $newdb->insertUserpaylog($usr_id, $thirdType, $app_order_id, $sdkPaytime, $area, $serv_id, $money, $accountId);
                $array = array("success"=>"0", "desc"=>"$app_order_id");
                echo json_encode($array);
            }
            else
            {
                echo '插入云顶订单失败';
            }
        }
    }
    else
    {
        $array = array("success"=>"1", "desc"=>"sign错误");
        echo json_encode($array);
    }
}
