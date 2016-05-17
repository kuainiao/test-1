<?php
/**
 * PHP SDK for  OpenAPI
 *
 */
date_default_timezone_set('Asia/ShangHai');
require_once '../../models/NSkyModel.php';
require_once 'ManageNSkyUser.php';

header("Content-type: text/html; charset=utf-8");

if (!function_exists('curl_init')){
	exit('您的PHP没有安装 配置cURL扩展，请先安装配置cURL，具体方法可以上网查。');
}

if (!function_exists('json_decode')){
	exit('您的PHP不支持JSON，请升级您的PHP版本。');
}

class NSkySdk
{
	private $gameId  = 20000;
	private $gameKey = 'fg2dynhuy3ihc5rwk5uo3sqhy71zhruz4wzfnrm2';
	private $url = "http://bianhuang.9sky.me/platform/bianhuangios/session.php";

	function __construct(){
	}

	public function check_user_login($area, $uid, $session,$server,$systemType=1)
    {
    	if($systemType==2){
    		$this->gameId = 20000;
			$this->gameKey = 'fg2dynhuy3ihc5rwk5uo3sqhy71zhruz4wzfnrm2';
    	}
		$sign = md5($this->gameId.$uid.$session.$this->gameKey);
		$sourceString = "gameId=".$this->gameId."&uid=".$uid."&session=".$session."&sign=".$sign;
		$Params = trim($sourceString);

		// 发起请求
		$Res = $this->request($this->url, $Params, 'get');

		if (false === $Res['result'])
        {
			$ResultArray = array(
				'res' => $Res['errno'],
				'msg' => $Res['msg'],
			);
		}

		$ResultArray = json_decode($Res['msg'], true);

		// 远程返回的不是 json 格式, 说明返回包有问题
		if (is_null($ResultArray)) {
			$ResultArray = array(
				'res' => false,
				'msg' => $Res['msg']
			);
		}
        //返回给客户端正确值
        if ($ResultArray['ErrorCode'] == 1)
        {
            $new = new ManageNSky();
            $new->manageNSkyUser($area, $uid,$server,$systemType);
        }
        else
        {
            echo $ResultArray['ErrorCode'];
        }
	}

	/**
	 * 执行一个 HTTP 请求
	 *
	 * @param string 	$Url 	执行请求的Url
	 * @param mixed	$Params 表单参数
	 * @param string	$Method 请求方法 post / get
	 * @return array 结果数组
	 */
	public function request($Url, $Params, $Method='post'){

		$Curl = curl_init();//初始化curl

		if ('get' == $Method){//以GET方式发送请求
			curl_setopt($Curl, CURLOPT_URL, "$Url?$Params");
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
