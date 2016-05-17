<?php

date_default_timezone_set('Asia/ShangHai');
require_once '../../models/PlatModel.php';
require_once 'ManageYlUser.php';


header("Content-type: text/html; charset=utf-8");


class YlSdk{

	private $pid  = '103895';
    private $key  = '154320d8cafedcb01588cf19be1aaad8';
	private $url  = "http://api.411game.com/validation.do";

    function __construct(){
	}

	public function check_user_login($area,$username,$server,$channel=1)
    {
        $flag = strtoupper(md5($username.$this->pid.$this->key));
        $param = "UserName=$username&PID=$this->pid&flag=$flag";
		$param = trim($param);

		// 发起请求
		$Res = $this->request($this->url,$param);
		$ResultArray = json_decode($Res, true);

        $ResultArray = json_decode($Res['msg'], true);
        if (is_null($ResultArray)) {
            $ResultArray = array(
                'res' => false,
                'msg' => $Res['msg']
            );
        }

        //返回给客户端正确值
        if ($ResultArray['userState'] == 1)
        {
            $userid = crc32($username);
            if($channel == 1){
                $new = new ManageYl();
                $new->manageYlUser($area,$userid,$server);
            }
            else{
                $new = new ManageYl();
                $new->manageYlUser($area,$userid,$server,$channel=2);
            }
        }
        else
        {
            echo $ResultArray['userState'];
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
