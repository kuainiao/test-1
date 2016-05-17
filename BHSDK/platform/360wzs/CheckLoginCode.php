<?php
require_once 'QhSdk.php';

$code          = isset($_GET['code']) ? $_GET['code']  : null;
$Codehurl   = "https://openapi.360.cn/oauth2/access_token";
$Appkey      = "63599aa6eb4b6a971f6b307385215eaf";
$Appsecret = "9eea1483d3cda7f8f11f3a1c74b09167";
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
    //var_dump($ResultArray);die();
	if (is_null($ResultArray)) {
		die("远程返回的不是 json格式");
	}else{
		echo $ResultArray['refresh_token'];
	}
}	
?>