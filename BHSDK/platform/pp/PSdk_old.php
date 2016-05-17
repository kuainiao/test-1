<?php
date_default_timezone_set('Asia/ShangHai');
require_once '../../models/PlatModel.php';
require_once 'ManagePpUser.php';


header("Content-type: text/html; charset=utf-8");


class PSdk{

	//private $AppKey_id = '100000461';
	//private $App_secret = 'E53mmMz5R1C5KTQXF19Ptf99';
	private $Url = "http://passport_i.25pp.com:8080/index?tunnel-command=2852126756";

    function __construct(){
	}

	public function check_user_login($area,$len,$token_key)
    {  
		//$commmand = "0xAA000022";
		// 发起请求
		$Res = $this->request($this->Url,$token_key);
		//var_dump($Res);
		if($Res['result']!=false){
		$ResultArray = json_decode($Res["msg"],true);
			// 远程返回的不是 json 格式, 说明返回包有问题
			if (is_null($ResultArray)) {
				$ResultArray = array(
					'res' => false,
					'msg' => $Res['msg']
				);
			}
	        //返回给客户端正确值
	        if ($ResultArray['status'] == 0)
	        {
	            $new = new ManageP();
	            $new->managePUser($area,$ResultArray['userid'],2);
	        }
	        else
	        {
	            echo $ResultArray['status'];
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
		curl_setopt($Curl, CURLOPT_HTTPHEADER,array('Host: passport_i.25pp.com', 'Content-Length: '.strlen($param).'\''));

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
            'msg' => '{'.$Res.'}',
		);
		 
	}
}
?>
