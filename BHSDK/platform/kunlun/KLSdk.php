<?php
header("Content-type: text/html; Charset=utf-8");
date_default_timezone_set('Asia/ShangHai');
require_once 'ManageKLUser.php';

class KLSdk{

	private $Url = "http://sdk.api.kunlun.com/verifyklsso.php";

    function __construct(){
	}

	/**
	 *  获取用户信息
	 */
	public function check_user_login($area,$klsso,$server,$type=3)
    {
		// 发起请求
		$url = $this->Url;
        $param = "klsso=".$klsso;
		$Res = $this->request($url,$param,'get');
		$Result = json_decode($Res['msg'],true);
		// 远程返回的不是 json 格式, 说明返回包有问题
		
		if (is_null($Result)) {
			die("远程返回的不是 json格式");
		}else{
	        if($Result['retcode'] == 0){
                $uid = $Result['data']['uid'];
                $new = new ManageKL();
			    $new->manageKLUser($area,$uid,$server,$type);
            }else{
                echo $Result['retmsg'];
            }        
		}      			
	}
    
    /**
     * 发送请求
     */
	public function request($Url,$param,$Method='post')
    {
		$Curl = curl_init();//初始化curl

		if ('get' == $Method){//以GET方式发送请求
			if (is_array($param)) {
                $Url .= (strpos($Url, '?') === false ? '?' : '&') . http_build_query($param);
            } else {
                $Url .= (strpos($Url, '?') === false ? '?' : '&') . $param;
            }
			curl_setopt($Curl, CURLOPT_URL, $Url);
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
