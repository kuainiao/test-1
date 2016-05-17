<?php

date_default_timezone_set('Asia/ShangHai');
require_once '../../models/PlatModel.php';
require_once 'ManageAzUser.php';


header("Content-type: text/html; charset=utf-8");


class AzSdk{

	private $AppKey = '138250006528Rai0DRXdbqzO4Sa4Yi';
	private $App_secret = 'E53mmMz5R1C5KTQXF19Ptf99';
	private $Url = "http://user.anzhi.com/web/api/sdk/third/1/queryislogin";

    function __construct(){
	}

	public function check_user_login($area,$account,$sid,$server)
    {  
		$appkey = $this->AppKey;
		$appsecret = $this->App_secret;
		$sign = base64_encode($appkey.$account.$sid.$appsecret);
		$time = $this->getdates();//精确到毫秒
        $param = array("time"=>$time,"appkey"=>$appkey,"account"=>$account,"sid"=>$sid,"sign"=>$sign);
		// 发起请求
		$Res = $this->request($this->Url,$param);
		$Res["msg"] = str_replace("'",'"',$Res["msg"]);
		$ResultArray = json_decode($Res["msg"],true);
		// 远程返回的不是 json 格式, 说明返回包有问题
		
		if (is_null($ResultArray)) {
			die("远程返回的不是 json格式");
		}else{
	        //返回给客户端正确值
	        $ResultArray['msg'] = base64_decode($ResultArray['msg']);
			$ResultArray['msg'] = str_replace("'",'"',$ResultArray['msg']);
			$msg = json_decode($ResultArray['msg'],true);
	        if ($ResultArray['sc']== 200 || $ResultArray['sc']== 1)
	        {
	        	$uid = $msg['uid'];
	            $new = new ManageAz();
	            $new->manageAzUser($area,$uid,$server);
	        }
	        else
	        {
	            echo $ResultArray['st'];
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
	
	public function getdates(){
        $time = gettimeofday();
        $t = date('YmdHis',$time['sec']);
        $t .= round($time['usec']/1000);
        return $t;
    }
}
?>
