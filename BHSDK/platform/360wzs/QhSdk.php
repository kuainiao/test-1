<?php

date_default_timezone_set('Asia/ShangHai');
require_once 'ManageQhUser.php';


header("Content-type: text/html; charset=utf-8");


class QhSdk{

	private $Url         = "https://openapi.360.cn/oauth2/get_token_info.json";
	private $Urlcode     = "https://openapi.360.cn/oauth2/access_token";
	private $Appkey      = "63599aa6eb4b6a971f6b307385215eaf";
	private $Appsecret   = "9eea1483d3cda7f8f11f3a1c74b09167";

    function __construct(){
	}

	/**
	 *  通过code登录
	 */
	public function check_user_login($area,$code)
    {
		// 发起请求
		$urlcode = $this->Urlcode;
		$data = array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>$this->Appkey,
			'client_secret'=>$this->Appsecret,
			'redirect_uri'=>'oob'
		);
		$Rescode = $this->request($urlcode,$data,'get');
		$ResultArraycode = json_decode($Rescode['msg'],true);
		// 远程返回的不是 json 格式, 说明返回包有问题
		
		if (is_null($ResultArraycode)) {
			die("远程返回的不是 json格式");
		}else{
	        //判断是否过期			
	        if (isset($ResultArraycode['expires_in'])&&$ResultArraycode['expires_in'] > 0)
	        {
	        	$url = $this->Url;
				$params= "access_token=".$ResultArraycode['access_token'];
				$Res = $this->request($url,$params,'get');
				$ResultArray = json_decode($Res['msg'],true);
				if (is_null($ResultArray)) {
					die("返回的不是 json格式");
				}else{
					//判断是否过期			
			        if (isset($ResultArray['expires_in'])&&$ResultArray['expires_in'] > 0){
			        	$uid = $ResultArray['user_id'];
			            $new = new ManageQh();
			            $new->manageQhUser($area,$uid,$ResultArraycode['refresh_token']);
			        }else{
			        	echo "参数错误!";
			        }
					
				}
	        	
	        }
	        else
	        {
	            echo '已过期';
	        }
		}      			
	}

	/**
	 *  通过access_token登录
	 */
	public function check_login($area,$access_token,$server)
    {
    	// 发起请求
    	$url = $this->Url;
		$params= "access_token=".$access_token;
		$Res = $this->request($url,$params,'get');
		$ResultArray = json_decode($Res['msg'],true);
		if (is_null($ResultArray)) {
			die("返回的不是 json格式");
		}else{
			//判断是否过期			
	        if (isset($ResultArray['expires_in'])&&$ResultArray['expires_in'] > 0){
	        	$uid = $ResultArray['user_id'];
	            $new = new ManageQh();
	            $new->manageQhUser($area,$uid,$refresh='',$server);
	        }else{
	        	echo "参数错误!";
	        }
		}
		
	}

    

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
