<?php

date_default_timezone_set('Asia/ShangHai');
require_once '../../models/PlatModel.php';
require_once 'ManageLxUser.php';

header("Content-type: text/html; charset=utf-8");


class LxSdk{
	private $Url = "http://passport.lenovo.com/interserver/authen/1.2/getaccountid";
	
	public function check_user_login($area,$lpsust,$server,$channel_type=1)
     {  
    	$realm = "10000623.realm.lenovoidapps.com";
        $param = "lpsust=$lpsust&realm=$realm";
		$param = trim($param);	
		// 发起请求
		$Res = $this->request($this->Url,$param,'get');

		if(is_null($Res)){
			die("远程返回有误");
		}else{
			if($Res['result']!=false){
		        //返回给客户端正确值
		        if($Res["msg"])
		        {
		        	$xml = simplexml_load_string($Res["msg"]);
					$uid = $xml->AccountID;
					if($uid==''){
						die("参数错误!");
					}
		            $new = new ManageLx();
		            $new->manageLxUser($area,(string)$uid,$server,$channel_type);
		        }
		        else
		        {
		            echo $Res["msg"];die;
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
