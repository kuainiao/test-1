<?php

date_default_timezone_set('Asia/ShangHai');
require_once '../../models/XmModel.php';
require_once '../../models/UserLoginModel.php';
require_once 'ManageXmUser.php';
require_once '../../ext/Ext.php';


header("Content-type: text/html; charset=utf-8");


class XmSdk{

	private $AppId  = '2882303761517427811';
	private $AppKey = '5281742767811';
	private $Url = "http://mis.migc.xiaomi.com/api/biz/service/verifySession.do";
	private $AppSecret = 'rMNAvBKTyEdv/msgEBo1YA==';

    function __construct(){
	}

	public function check_user_login($area, $uid, $session,$server)
    {
        $appId = $this->AppId;
        $param = "appId=$appId&session=$session&uid=$uid";
        $signature = hash_hmac('sha1',$param,$this->AppSecret,FALSE);
		// 发起请求
		$Res = $this->request($this->Url,$appId, $session, $uid, $signature, 'get');

		$ResultArray = json_decode($Res['msg'], true);
		// 远程返回的不是 json 格式, 说明返回包有问题
		if (is_null($ResultArray)) {
			$ResultArray = array(
				'res' => false,
				'msg' => $Res['msg']
			);
		}
        //返回给客户端正确值
        if ($ResultArray['errcode'] == 200)
        {
            $new = new ManageXm();
            $new->manageXmUser($area, $uid,$server);
        }
        else
        {
            echo $ResultArray['errcode'];
        }
	}
	/**
     * url decode 函数
     * @param type $item 数组或者字符串类型
     * @return type
     */
    public function urlDecode($item){
  
        return rawurldecode($item);
    }

    public function pay_result_notify_process($appId, $cpOrderId, $cpUserInfo, $uid, $orderId, $orderStatus, $payFee, $productCode, $productName, $productCount, $payTime,$orderConsumeType , $signature)
    {
        $thirdType = 3;
        $MyAppKey = "5281742767811";
		$AppSecret1 = 'rMNAvBKTyEdv/msgEBo1YA==';
		
		$appId = rawurldecode($appId);
		$cpOrderId = rawurldecode($cpOrderId);
		$cpUserInfo = rawurldecode($cpUserInfo);
		$uid = rawurldecode($uid);
		$orderId = rawurldecode($orderId);
		$orderStatus = rawurldecode($orderStatus);
		$payFee = rawurldecode($payFee);
		$productCode = rawurldecode($productCode);
		$productName = rawurldecode($productName);
		$productCount = rawurldecode($productCount);
		$payTime = rawurldecode($payTime);
		
		//echo file_put_contents("/tmp/test301.txt", "222222222222222");
        //按照API规范里的说明，把相应的数据进行拼接加密处理
        $param = "appId=$appId&cpOrderId=$cpOrderId&cpUserInfo=$cpUserInfo&orderId=$orderId&orderStatus=$orderStatus&payFee=$payFee&payTime=$payTime&productCode=$productCode&productCount=$productCount&productName=$productName&uid=$uid";
        $sign_check = hash_hmac('sha1',$param,$AppSecret1,FALSE);
		
		
		//echo file_put_contents("/tmp/test300.txt", "222222222222222");
		//判断服
        //$cpUserInfo = Ext::getInstance()->CheckAreaServer($cpUserInfo);
		
        if($sign_check == $signature)
        {
			//echo file_put_contents("/tmp/test88.txt", "222222222222222");
            if ($orderStatus == 'TRADE_SUCCESS')
            {
                $newdb = new XmModel();
                $selectOrderId = $newdb->selectPay($uid, $orderId);
                //验证是否充值过
                if ($orderId == $selectOrderId)
                {
                    echo '{"errcode":200}';
                }
                else
                {
                    $sdkPaytime = time();
                    $insert = $newdb->insertPay($cpOrderId, $cpUserInfo, $uid, $orderId, $orderStatus, $payFee, $productCode, $productName, $productCount, $payTime, $sdkPaytime);
                    if($insert)
                    {
                    	$area = Ext::getInstance()->getArea($cpUserInfo);
                        /*if ($cpUserInfo >= 100 && $cpUserInfo < 200)
                        {
                            $area = 100;
                        }
                        elseif ($cpUserInfo >= 200 && $cpUserInfo < 300)
                        {
                            $area = 200;
                        }
                        elseif ($cpUserInfo >= 300 && $cpUserInfo < 400)
                        {
                            $area = 300;
                        }
                        elseif ($cpUserInfo >= 400 && $cpUserInfo < 500)
                        {
                            $area = 400;
                        }
                        elseif ($cpUserInfo >= 500 && $cpUserInfo < 600)
                        {
                            $area = 500;
                        }
                        elseif ($cpUserInfo >= 600 && $cpUserInfo < 700)
                        {
                            $area = 600;
                        }
                        elseif ($cpUserInfo >= 700 && $cpUserInfo < 800)
                        {
                            $area = 700;
                        }
                        elseif ($cpUserInfo >= 800 && $cpUserInfo < 900)
                        {
                            $area = 800;
                        }
                        elseif ($cpUserInfo >= 900 && $cpUserInfo < 1000)
                        {
                            $area = 900;
                        }
						elseif ($cpUserInfo >= 10000 && $cpUserInfo < 10100)
                        {
                            $area = 10000;
                        }
                        else
                        {
                            $area = 1000;
                        }*/
                        $gameMoney = $payFee/10;
                        $accountId = $newdb->selectXmUserAccountId($area, $uid);
                        $username = $newdb->selectXmUserWzUsername($area, $uid);
                        $socketClass = new ConnectGameServer();
                        $socketClass->payToGameServer($cpUserInfo, $thirdType, $username, $accountId, $gameMoney, $orderId);
						$newUser = new UserLoginModel();
						$newUser->insertUserpaylog($uid, $thirdType, $orderId, $sdkPaytime, $area, $cpUserInfo, $payFee/100, $accountId);
                        echo '{"errcode":200}';
                    }
                    else
                    {
                        echo '插入xm订单失败';
                    }
                }
            }
            else
            {
                //订单错误
                echo 'orderStatus='.$orderStatus;
            }
        }
        else
        {
            echo "errcode=1525";
        }
    }

    public function hmacSha($param)
    {
        return $signature = hash_hmac('sha1', $param, $this->AppKey, $AppSecret);
    }


	public function request($Url, $appId, $session, $uid, $signature, $Method='post')
    {
		$Curl = curl_init();//初始化curl

		if ('get' == $Method){//以GET方式发送请求
			curl_setopt($Curl, CURLOPT_URL, "$Url?appId=$appId&session=$session&uid=$uid&signature=$signature");
		}else{//以POST方式发送请求
			curl_setopt($Curl, CURLOPT_URL, $Url);
			curl_setopt($Curl, CURLOPT_POST, 1);//post提交方式
			curl_setopt($Curl, CURLOPT_POSTFIELDS, "appId=$appId&session=$session&uid=$uid&signature=$signature");//设置传送的参数
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
?>
