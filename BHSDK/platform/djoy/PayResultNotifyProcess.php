<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../models/DJoyModel.php';
require_once '../../socket/ConnectServer.php';
require_once '../../models/UserLoginModel.php';
require_once '../../ext/Ext.php';

$order = $_GET['order'];
$money = $_GET['money'];
$mid = $_GET['mid'];
$time = $_GET['time'];
$result = $_GET['result'];
$ext = $_GET['ext'];
$signature = $_GET['signature'];

payNotifyResult($order, $money, $mid, $time, $result, $ext, $signature);

function payNotifyResult($order, $money, $mid, $time, $result, $ext, $signature)
{
    $thirdType = 2;
    $PaymentKey = 'hXKl7iHBX8ir';

    $param = "order=$order&money=$money&mid=$mid&time=$time&result=$result&ext=$ext&key=$PaymentKey";
    $localSignature = md5($param);

    if ($ext == 'yuanbao')
    {
        $ext = 101;
    }
    if($signature == $localSignature)
    {
        if($result == 1)
        {
            $newdb = new DJoyModel();
            $selectOrder = $newdb->selectPay($mid, $order);
            //验证是否充值过
            if ($order == $selectOrder)
            {
                echo "success";
            }
            else
            {
                $sdkPaytime = time();
                $insert = $newdb->insertPay($money, $order, $mid, $time, $ext, $sdkPaytime);
                if($insert)
                {
                	$area = Ext::getInstance()->getArea($ext);
                    /*if ($ext >= 100 && $ext < 200)
                    {
                        $area = 100;
                    }
                    elseif ($ext >= 200 && $ext < 300)
                    {
                        $area = 200;
                    }
                    elseif ($ext >= 300 && $ext < 400)
                    {
                        $area = 300;
                    }
                    elseif ($ext >= 400 && $ext < 500)
                    {
                        $area = 400;
                    }
                    elseif ($ext >= 500 && $ext < 600)
                    {
                        $area = 500;
                    }
                    elseif ($ext >= 600 && $ext < 700)
                    {
                        $area = 600;
                    }
                    elseif ($ext >= 700 && $ext < 800)
                    {
                        $area = 700;
                    }
                    elseif ($ext >= 800 && $ext < 900)
                    {
                        $area = 800;
                    }
                    elseif ($ext >= 900 && $ext < 1000)
                    {
                        $area = 900;
                    }
					elseif ($ext >= 10000 && $ext < 10100)
                    {
                        $area = 10000;
                    }
                    else
                    {
                        $area = 1000;
                    }*/
                    $gameMoney = $money*10;
                    $accountId = $newdb->selectDJoyUserAccountId($area, $mid);
                    $username = $newdb->selectDJoyUserWzUsername($area, $mid);
//                    echo $area.'<br>';
//                    echo $ext.'<br>';
//                    echo $thirdType.'<br>';
//                    echo $username.'<br>';
//                    echo $accountId.'<br>';
//                    echo $gameMoney.'<br>';
//                    echo $order.'<br>';
//                    die();
                    $socketClass = new ConnectGameServer();
                    $socketClass->payToGameServer($ext, $thirdType, $username, $accountId, $gameMoney, $order);
					$newUser = new UserLoginModel();
					$newUser->insertUserpaylog($mid, $thirdType, $order, $sdkPaytime, $area, $ext, $money, $accountId);
                    echo "success";
                }
                else
                {
                    echo '插入当乐订单失败';
                }
            }
        }
        else
        {
            echo '无效订单';
        }
    }
    else
    {
        echo 'false';
    }
}