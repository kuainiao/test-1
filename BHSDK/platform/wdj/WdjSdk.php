<?php
date_default_timezone_set('Asia/ShangHai');
require_once '../../models/PlatModel.php';
require_once 'ManageWdjUser.php';


header("Content-type: text/html; charset=utf-8");


class WdjSdk{

	private $AppKey_id = '100000461';
	//private $App_secret = 'f7d83e4e1c4661cf87a4bd3bcede6624';
	private $Url = "https://pay.wandoujia.com/api/uid/check";

    function __construct(){
	}

	public function check_user_login($area,$uid,$token,$server)
    {  
		$token = urlencode($token);
		$appkey_id = $this->AppKey_id;
        $param = "uid=$uid&token=$token&appkey_id=$appkey_id";
		// 发起请求
		$Res = $this->request($this->Url,$param,'get');
		//var_dump($Res);
		//$Res["msg"] = str_replace("'",'"',$Res["msg"]);
		//$ResultArray = json_decode($Res["msg"],true);
		
		if (is_null($Res)) {
			die("远程返回有误");
		}else{
			if($Res['result']!=false){
		        //返回给客户端正确值
		        if ($Res['msg']!='false')
		        {
		            $new = new ManageWdj();
		            $new->manageWdjUser($area,$uid,$server);
		        }
		        else
		        {
		            die("远程返回有误");
		        }
		    }else{
		    	die("远程返回有误");
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
		if (substr($Url, 0, 5) == 'https') {
            curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($Curl, CURLOPT_SSL_VERIFYHOST, false);
        }

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
