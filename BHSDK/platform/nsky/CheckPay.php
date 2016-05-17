<?php
/*
* Created by PhpStorm.
* User: YANG
* Date: 14-6-9
* Time: 上午11:17
*/

require_once '../../ext/Ext.php';
require_once dirname(__FILE__).'/'.'../../halo/HaloLog.php';
require_once dirname(__FILE__).'/'.'../../models/IosModel.php';
require_once dirname(__FILE__).'/'.'../../models/UserLoginModel.php';
require_once dirname(__FILE__).'/'.'../../socket/ConnectServer.php';

class CheckPay
{
    public function getReceiptData($accountId, $server, $receipt, $isSandbox, $userid, $nickname, $area_server)
    {
        $thirdType = 9;
        if ($isSandbox == 0) {
            $url = 'https://sandbox.itunes.apple.com/verifyReceipt';
        }
        else {
            $url = 'https://buy.itunes.apple.com/verifyReceipt';
        }

        $postData = json_encode(
            array('receipt-data' => $receipt)
        );

        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_POSTFIELDS => $postData
        );
        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        $response = curl_exec($ch);
        curl_close($ch);

        //返回信息json解码
        $data = json_decode($response);

        $status = $data->status;
        $receipt = $data->receipt;

        HaloLog::addLog('iosPay', $response);
        HaloLog::addLog('iosPay', $status);


        if ($status == 0)
        {
            //获取订单数量、钱数、订单号、购买时间(毫秒)
            $quantity = $receipt->quantity;
            $productId = $receipt->product_id;
            $orderId = $receipt->transaction_id;
            $purchaseDateMs = $receipt->purchase_date_ms;
            $purchaseDate = substr($purchaseDateMs, 0,10);

            HaloLog::addLog('iosPay', $quantity);
            HaloLog::addLog('iosPay', $productId);
            HaloLog::addLog('iosPay', $orderId);
            HaloLog::addLog('iosPay', $purchaseDateMs);
            HaloLog::addLog('iosPay', $purchaseDate);


            switch ($productId)
            {
                case 'com.wzsgbh.60':
                    $gameMoney = 60*$quantity;
                    break;
                case 'com.wzsgbh.300':
                    $gameMoney = 300*$quantity;
                    break;
                case 'com.wzsgbh.680':
                    $gameMoney = 680*$quantity;
                    break;
                case 'com.wzsgbh.1980':
                    $gameMoney = 1980*$quantity;
                    break;
                case 'com.wzsgbh.3280':
                    $gameMoney = 3280*$quantity;
                    break;
                case 'com.wzsgbh.6480':
                    $gameMoney = 6480*$quantity;
                    break;
            }

            HaloLog::addLog('iosPay', $gameMoney);

            $newIos = new IosModel();
            $selectOrder = $newIos->selectIosPay($orderId);
            if($selectOrder == $orderId)
            {
                echo 'Already Pay';
                exit();
            }
            else
            {
                $area = Ext::getInstance()->getArea($server);
                //给玩家充值、插入订单
                $username = $newIos->selectIosWzUsername($area, $accountId);
                $uid = $newIos->selectIosUid($area, $accountId);
                HaloLog::addLog('iosPay', $username);
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server, $thirdType, $username, $accountId, $gameMoney, $orderId);
                $insertPay = $newIos->insertIosPay($accountId , $server, $orderId, $quantity, $gameMoney, $purchaseDate, time(),$area);
                //写入充值log
                $newUserPay = new UserLoginModel();
                $newUserPay->insertUserpaylog($uid, $thirdType, $orderId, time(), $area, $server, $gameMoney/10, $accountId, $channel_type=2);

                $payUrl = 'http://ins.app-fame.com/gact.aspx';
                $appid = '6061';
				$paytime = time();
                $apple_order = $orderId;
                $price = floatval($gameMoney/10);
                $param = "gact=120&appid=$appid&apple_order=$apple_order&userid=$userid&nickname=$nickname&area_server=$area_server&currency=1&paystatus=1&price=$price&paytime=$paytime";

                file_get_contents($payUrl.'?'.$param);
                HaloLog::addLog('iosPay', 'submit ok');
            }
        }
        else
        {
            return false;
        }
    }

    public function request($Url,$param,$Method='post')
    {
        $Curl = curl_init();//初始化curl

        if ('get' == $Method){//以GET方式发送请求
            curl_setopt($Curl, CURLOPT_URL, "$Url?$param");
        }else{//以POST方式发送请求
            curl_setopt($Curl, CURLOPT_URL, $Url);
            curl_setopt($Curl, CURLOPT_POST, 1);//post提交方式
            curl_setopt($Curl, CURLOPT_POSTFIELDS,$param);//设置传送的参数
        }

        curl_setopt($Curl, CURLOPT_HEADER, false);//设置header
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
        curl_setopt($Curl, CURLOPT_CONNECTTIMEOUT, 3);//设置等待时间

        $Res = curl_exec($Curl);//运行curl
        $Err = curl_error($Curl);

        if (false === $Res || !empty($Err)){
            $Errno = curl_errno($Curl);
            $Info = curl_getinfo($Curl);
            curl_close($Curl);

            return array(
                'result' => false,
                'errno' => $Errno,
                'msg' => $Err,
                'info' => $Info,
            );
        }
        curl_close($Curl);//关闭curl
        return array(
            'result' => true,
            'msg' => $Res,
        );

    }
}

$receipt   = $_REQUEST['receipt'];
$isSandbox = $_REQUEST['sandbox'];
$accountId = $_REQUEST['accountId'];
$server = $_REQUEST['server'];
$nickname = urlencode($_REQUEST['nickname']);
$userid = $_REQUEST['userid'];
$area_server = urlencode($_REQUEST['area_server']);

HaloLog::addLog('iosPay', $receipt);
HaloLog::addLog('iosPay', $isSandbox);
HaloLog::addLog('iosPay', $accountId);
HaloLog::addLog('iosPay', $server);

CheckPay::getReceiptData($accountId, $server, $receipt, $isSandbox, $userid, $nickname, $area_server);


