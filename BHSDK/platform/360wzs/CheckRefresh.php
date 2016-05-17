<?php
require_once 'QhSdk.php';

$refresh = isset($_GET['refresh_token']) ? $_GET['refresh_token'] : null;
$Refreshurl = "https://openapi.360.cn/oauth2/access_token";
$Appkey      = "63599aa6eb4b6a971f6b307385215eaf";
$Appsecret = "9eea1483d3cda7f8f11f3a1c74b09167";
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