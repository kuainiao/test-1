<?php

date_default_timezone_set('Asia/ShangHai');
require_once '../../models/PlatModel.php';
require_once 'ManageRgUser.php';


header("Content-type: text/html; charset=utf-8");


class RgSdk{

	private $Url = "http://210.83.86.176/mpay/index.php/user_center";

    function __construct(){
	}

	public function check_user_login($area,$uid,$session,$server,$channel_type)
    {
    	$merid = '10000018';
    	$act = 4;
		$merchantkey = 'e4559e20725c486f333a6cc3e7cf5735';
		$encstring= MD5($merid.$act.$uid.$session.$merchantkey);
        $param = "MerId=$merid&Act=$act&Uin=$uid&SessionKey=$session&EncString=$encstring";
		$param = trim($param);

		// 发起请求
		$Res = $this->request($this->Url,$param,'get');
		//var_dump($Res);die;
		if(is_null($Res)){
			die("远程返回有误");
		}else{
			if($Res['result']!=false){
				$ResultArray = json_decode($Res["msg"],true);
				// 远程返回的不是 json 格式, 说明返回包有问题
				if (is_null($ResultArray)) {
					die("远程返回有误");
				}
		        //返回给客户端正确值
		        if ($ResultArray['ErrorCode'] == 1)
		        {
		            $new = new ManageRg();
		            $new->manageRgUser($area,$uid,$server,$channel_type);
		        }
		        else
		        {
		            echo $ResultArray['ErrorCode'];die;
		        }
			}
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
?>
