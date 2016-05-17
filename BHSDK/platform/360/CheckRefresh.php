<?php
require_once 'QhSdk.php';

$refresh = isset($_GET['refresh_token']) ? $_GET['refresh_token'] : null;
$Refreshurl = "https://openapi.360.cn/oauth2/access_token";
$Appkey      = "32fa6b2b94983437648323bbb7231ac6";
$Appsecret = "cbbd37d0317211a24f414be276f53f9e";
$data = array(
			'grant_type'=>'refresh_token',
			'refresh_token'=>$refresh,
			'client_id'=>$Appkey,
			'client_secret'=>$Appsecret,
			'scope'=>'basic'
		);
if($refresh != null){
	$Qh = new QhSdk();
	$Res = $Qh->request($Refreshurl,$data,'get');
	$ResultArray = json_decode($Res['msg'],true);
	if (is_null($ResultArray)) {
		die("远程返回的不是 json格式");
	}else{
		echo $ResultArray['access_token'].'_'.$ResultArray['refresh_token'];
	}
}
?>