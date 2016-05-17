<?php
/**
 * PHP SDK for  OpenAPI
 *
 */
date_default_timezone_set('Asia/ShangHai');
require_once '../../models/AiyouxiModel.php';
require_once 'ManageAiyouxiUser.php';
require_once '../../halo/HaloLog.php';

class AiyouxiSdk
{
	private $url = "https://open.play.cn/oauth/token";
	private $url2 = "https://open.play.cn/oauth/token/validator";

	function __construct(){
	}

	public function check_user_login($area,$code,$server,$systemType=1)
    {
        $client_id = "78361211";
		$sign_method = "MD5";
		$version = "1.0";
		$timestamp = round(microtime(true)*1000);
		$client_secret = "77dbbfe227674f449792da8ac9faf945";
		$sign_sort = urlencode("client_id&sign_method&version&timestamp&client_secret");
        $grant_type = "authorization_code";
        $signature = md5($client_id.$sign_method.$version.$timestamp.$client_secret);
        $Params = "code=".$code."&client_secret=".$client_secret."&grant_type=".$grant_type."&client_id=".$client_id."&sign_method=".$sign_method."&version=".$version."&timestamp=".$timestamp."&signature=".$signature."&sign_sort=".$sign_sort;

		// 发起请求
		$Res = $this->request($this->url, $Params);
		
		$log = new HaloLog();
        $log->addLog('aiyouxi', $Res['msg']);

		$ResultArray = json_decode($Res['msg'], true); 
		

        //返回给客户端正确值
        if (!empty($ResultArray['user_id']))
        {
			$uid = $ResultArray['user_id'];
            $log->addLog('aiyouxi', $uid);
			
			$access_token = $ResultArray['access_token'];
			$Params2 = "client_id=".$client_id."$access_token=".$access_token."&sign_method=".$sign_method."&version=".$version."&timestamp=".$timestamp."&signature=".$signature."&sign_sort=".$sign_sort;
			//访问令牌鉴权
			$Res2 = $this->request($this->url2, $Params2);
			
            $new = new ManageAiyouxi();
            $new->manageAiyouxiUser($area, $uid,$server,$systemType);
        }
        else
        {
            echo $ResultArray['error_description'];
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
		
		$this_header = "Content-type:application/x-www-form-urlencoded";
		curl_setopt($Curl, CURLOPT_HEADER, $this_header);//设置header
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
