<?php

date_default_timezone_set('Asia/ShangHai');
require_once '../../models/DJoyModel.php';
require_once 'ManageDJoyUser.php';

header("Content-type: text/html; charset=utf-8");

class DJoySdk{
	
	private $AppId  = 690;
	private $AppKey = 'z5CfcOgb';
    private $PaymentKey = 'hXKl7iHBX8ir';
	private $Url = "http://connect.d.cn/open/member/info/";

	function __construct(){
	}

	public function check_user_login($area, $mid, $token,$server,$channel_type=1)
    {
    	if($channel_type==2){
    		$this->AppId = 772;
			$this->AppKey = 'UCByyEu5';
			$this->PaymentKey = 'poyCmgL59n9B';
    	}
        $AppId = $this->AppId;
        $AppKey = $this->AppKey;
        $param = $token."|".$AppKey;
        $sig = md5($param);
        // 发起请求
		$Res = $this->DJoyRequest($this->Url, $AppId, $mid, $token, $sig, 'get');
        //print_r($Res);
        $ResultArray = json_decode($Res['msg'], true);
        // 远程返回的不是 json 格式, 说明返回包有问题

        if (is_null($ResultArray))
        {
            //记录log
            echo '远程返回的不是 json 格式';
        }
        else
        {
            $errorCode = $ResultArray['error_code'];
            if ($errorCode != 0 )
            {
                //记录log[返回的错误代码]
                echo "错误代码".$errorCode;
            }
            else
            {
                $memberId = $ResultArray['memberId'];
                if ($memberId = $mid)
                {
                    $new = new ManageDJoy();
                    $new->manageDJoyUser($area, $mid,$server,$channel_type);
                }
                else
                {
                    echo 'memberId错误';
                }
            }
        }
	}

	public function DJoyRequest($Url, $AppId, $mid, $token, $sig, $Method='post')
    {

		$Curl = curl_init();//初始化curl

		if ('get' == $Method){//以GET方式发送请求
			curl_setopt($Curl, CURLOPT_URL, "$Url?app_id=$AppId&mid=$mid&token=$token&sig=$sig");
		}else{//以POST方式发送请求
			curl_setopt($Curl, CURLOPT_URL, $Url);
			curl_setopt($Curl, CURLOPT_POST, 1);//post提交方式
			curl_setopt($Curl, CURLOPT_POSTFIELDS, $Params);//设置传送的参数
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
