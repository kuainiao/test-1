<?php
require_once 'QhSdk.php';

$code          = isset($_GET['code']) ? $_GET['code']  : null;
$Codehurl   = "https://openapi.360.cn/oauth2/access_token";
$Appkey      = "32fa6b2b94983437648323bbb7231ac6";
$Appsecret = "cbbd37d0317211a24f414be276f53f9e";
$data = array(
			'grant_type'=>'authorization_code',
			'code'=>$code,
			'client_id'=>$Appkey,
			'client_secret'=>$Appsecret,
			'redirect_uri'=>'oob'
		);
if($code != null){
	$Qh = new QhSdk();
	$Res = $Qh->request($Codehurl,$data,'get');
	$ResultArray = json_decode($Res['msg'],true);
	if (is_null($ResultArray)) {
		die("远程返回的不是 json格式");
	}else{
		echo $ResultArray['refresh_token'];
	}
}	
?>