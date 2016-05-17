<?php
require_once '../../models/UCModel.php';
require_once '../../socket/ConnectServer.php';
require_once '../../models/UserLoginModel.php';
require_once '../../halo/HaloLog.php';
require_once '../../ext/Ext.php';

$config = include "config.inc.php";

$request = $GLOBALS['HTTP_RAW_POST_DATA'];

$payCall = new payCallBack($config);
$payCall->payCallBackMethod($request);

class payCallBack
{
    public $channelId;
    public $cpId;
    public $gameId;
    public $serverUrl;

    public function __construct($config){
        $this->config 		= $config;
        if(is_array($this->config)&& $this->config!=null){
            if(array_key_exists("serverUrl", $this->config))
                $this->serverUrl 	= $this->config['serverUrl'];
            //if(array_key_exists("cpId", $this->config))
                //$this->cpId 		= intval($this->config['cpId']);
            if(array_key_exists("gameId", $this->config))
                $this->gameId		= intval($this->config['gameId']);
            //if(array_key_exists("channelId", $this->config))
                //$this->channelId 	= intval($this->config['channelId']);
            //if(array_key_exists("serverId", $this->config))
                //$this->serverId 	= intval($this->config['serverId']);
            if(array_key_exists("apiKey", $this->config))
                $this->apiKey 		= $this->config['apiKey'];
        }else{
            throw new exception('配置为空');
        }
    }

    public function payCallBackMethod($request)
    {
        $thirdType = 1;
        $response = json_decode($request,true);
        $remoteSign = $response['sign'];
        $responseData = $response['data'];
		//echo file_put_contents("/tmp/test88.txt", $responseData);//把内容写入到一个文件
        if($responseData!=null)
        {
            $amount = $responseData['amount'];
            $callbackInfo = $responseData['callbackInfo'];
            $failedDesc = $responseData['failedDesc'];
            $gameId = $responseData['gameId'];
            $orderId = $responseData['orderId'];
            $orderStatus = $responseData['orderStatus'];
            $payWay = $responseData['payWay'];
            //$serverId = $responseData['serverId'];
            $ucaccountId = $responseData['accountId'];
			$creator = $responseData['creator'];
			 
            $signSource = "accountId=".$ucaccountId."amount=".$amount."callbackInfo=".$callbackInfo."creator=".$creator."failedDesc=".$failedDesc."gameId=".$gameId."orderId=".$orderId."orderStatus=".$orderStatus."payWay=".$payWay.$this->apiKey;
			
            $sign = md5($signSource);

            if ($callbackInfo == 'callback')
            {
                $callbackInfo = 101;
            }
            if($sign == $remoteSign)
            {
                if ($orderStatus == 'S')
                {	
					$ucid = abs(crc32($ucaccountId));
                    $newdb = new UCModel();
                    $selectOrderId = $newdb->selectPay($ucid, $orderId);
                    //验证是否充值过
                    if ($orderId == $selectOrderId)
                    {
                        echo "SUCCESS";
                    }
                    else
                    {
                        $sdkPaytime = time();
                        $insert = $newdb->insertPay($ucid, $ucaccountId, $orderId, $payWay, $amount, $orderStatus, $callbackInfo, $sdkPaytime);
                        if($insert)
                        {
                        	$area = Ext::getInstance()->getArea($callbackInfo);
                            /*if ($callbackInfo >= 100 && $callbackInfo < 200)
                            {
                                $area = 100;
                            }
                            elseif ($callbackInfo >= 200 && $callbackInfo < 300)
                            {
                                $area = 200;
                            }
                            elseif ($callbackInfo >= 300 && $callbackInfo < 400)
                            {
                                $area = 300;
                            }
                            elseif ($callbackInfo >= 400 && $callbackInfo < 500)
                            {
                                $area = 400;
                            }
                            elseif ($callbackInfo >= 500 && $callbackInfo < 600)
                            {
                                $area = 500;
                            }
                            elseif ($callbackInfo >= 600 && $callbackInfo < 700)
                            {
                                $area = 600;
                            }
                            elseif ($callbackInfo >= 700 && $callbackInfo < 800)
                            {
                                $area = 700;
                            }
                            elseif ($callbackInfo >= 800 && $callbackInfo < 900)
                            {
                                $area = 800;
                            }
                            elseif ($callbackInfo >= 900 && $callbackInfo < 1000)
                            {
                                $area = 900;
                            }
							elseif ($callbackInfo >= 10000 && $callbackInfo < 10100)
                            {
                                $area = 10000;
                            }
                            else
                            {
                                $area = 1000;
                            }*/
                            $gameMoney = $amount*10;
                            $accountId = $newdb->selectUcUserAccountId($area, $ucid);
                            $username = $newdb->selectUcUserWzUsername($area, $ucid);
                            $socketClass = new ConnectGameServer();
                            $socketClass->payToGameServer($callbackInfo, $thirdType, $username, $accountId, $gameMoney, $orderId);
							$newUser = new UserLoginModel();
							$newUser->insertUserpaylog($ucid, $thirdType, $orderId, $sdkPaytime, $area, $callbackInfo, $amount, $accountId);
                            echo 'SUCCESS';
                        }
                        else
                        {
                            echo 'SUCCESS';
                        }
                    }
                }
                else
                {
                    echo 'SUCCESS';
                }
            }
            else
            {
                echo 'SUCCESS';
            }
        }
        else
        {
            echo 'SUCCESS';
        }
    }
}