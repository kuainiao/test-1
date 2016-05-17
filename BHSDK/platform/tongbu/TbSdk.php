<?php

date_default_timezone_set('Asia/ShangHai');
require_once '../../models/PlatModel.php';
require_once 'ManageTbUser.php';


header("Content-type: text/html; charset=utf-8");


class TbSdk{

	/*private $AppId  = '1627';
	private $AppKey = '558c992f79f42edb414dc767b1786646';
	private $App_secret = 'e046c4fa351d2eabb239cf799bfcf2af';*/
	private $Url = "http://tgi.tongbu.com/checkv2.aspx";

    function __construct(){
	}

	public function check_user_login($area,$uid,$session,$server)
    {
        $param = "k=$session";
		$param = trim($param);

		// 发起请求
		$Res = $this->request($this->Url,$param,'get');
		//var_dump($Res);die;
		//$ResultArray = json_decode($Res['msg'], true);
		// 远程返回的不是 json 格式, 说明返回包有问题
		if (is_null($Res)) {
			die("远程返回有误");;
		}else{
			//返回给客户端正确值
	        if ($Res['msg'] > 1)
	        {
	        	//$uid = $Res['msg'];
	            $new = new ManageTb();
	            $new->manageTbUser($area,$uid,$server,2);
	        }
	        else
	        {
	            echo 'code:'.$Res['msg'];
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
