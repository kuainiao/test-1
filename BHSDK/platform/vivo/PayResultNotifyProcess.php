<?php
require_once '../../halo/HaloLog.php';
require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';


$response = $_POST;



if(!empty($response))
{
    ksort($response);
    foreach($response as $key=>$value)
    {
        $i = 0;
        if($key != "signMethod" && $key != "signature")
        {
            $param .= ($i == 0 ? '' : '&').$key.'='.$value.'&';
        }
        $i++;
    }
    $log = new HaloLog();
    $log->addLog('Vivo', $param);
    $respCode = $response['respCode'];
    $respMsg = $response['respMsg'];
    $signMethod = $response['signMethod'];
    $signature = $response['signature'];
    $storeId = $response['storeId'];
    $storeOrder = $response['storeOrder'];
    $vivoOrder = $response['vivoOrder'];
    $orderAmount = $response['orderAmount'];
    $channel = $response['channel'];
    $channelFee = $response['channelFee'];
    pay_result_notify_process($param,$respCode,$respMsg,$signMethod,$signature,$storeId,$storeOrder,$vivoOrder,$orderAmount,$channel,$channelFee);
}
else
{
    echo 'post null';
}


function pay_result_notify_process($param,$respCode,$respMsg,$signMethod,$signature,$storeId,$storeOrder,$vivoOrder,$orderAmount,$channel,$channelFee)
{
    $thirdType = 24;
    $key = strtolower(md5('a17c2af7e78b6a9d00416fcd2bb712a1'));
    $finalParam = "$param$key";
    $my_signMethod = md5($finalParam);
    $log = new HaloLog();
    $log->addLog('Vivo', $finalParam);

    if($respCode == '0000')
    {
        if($my_signMethod == $signature)
        {
            $newdb = new PlatModel();
            $selectOrderId = $newdb->selectVAlreadyPay($vivoOrder,'platform_vivo_pay','NS_orderid');
            //验证是否充值过
            if ($vivoOrder == $selectOrderId)
            {
                echo 'okok';
                $log = new HaloLog();
                $log->addLog('Vivo', 'okok');
            }
            else
            {
                $sdkPaytime = time();
                $update = $newdb->updateVPay($vivoOrder,'platform_vivo_pay');
                if($update)
                {
                    $uid = $newdb->selectVPay($vivoOrder,$sdkPaytime,'platform_vivo_pay','NS_uid');
                    $server = $newdb->selectVPay($vivoOrder,$sdkPaytime,'platform_vivo_pay','NS_server');
                    $money = $newdb->selectVPay($vivoOrder,$sdkPaytime,'platform_vivo_pay','NS_money');
                    $area = Ext::getInstance()->getArea($server);
                    $gameMoney = $money*10;
                    $accountId = $newdb->selectThirdUserAccountId('platform_vivo_user',$area,$uid);
                    $username = $newdb->selectThirdUserWzUsername('platform_vivo_user',$area,$uid);
                    $socketClass = new ConnectGameServer();
                    $socketClass->payToGameServer($server, $thirdType, $username, $accountId, $gameMoney, $vivoOrder);
                    $newdb->insertUserpaylog($uid, $thirdType, $vivoOrder, $sdkPaytime, $area, $server, $money, $accountId);
                    echo 'ok';
                }
                else
                {
                    echo '更新VIVO订单失败';
                    $log = new HaloLog();
                    $log->addLog('Vivo', '更新VIVO订单失败');
                }
            }
        }
        else
        {
            echo 'signature error';
            $log = new HaloLog();
            $log->addLog('Vivo', 'signature error');
        }
    }
    else
    {
        echo $respCode;
        $log = new HaloLog();
        $log->addLog('Vivo', $respCode);
    }
}
