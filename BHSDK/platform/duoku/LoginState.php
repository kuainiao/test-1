<?php
/**
 * 调用Sdk查询订单
 * 
 */
require_once 'Sdk.php';
require_once 'ManageDkUser.php';

//客户端SDK返回的登陆令牌
$accessToken = $_GET['accessToken'];//
$area = $_GET['area'];
$server = isset($_GET['server'])?$_GET['server']:'';

$sdk = new Sdk();
$Res = $sdk->login_state_result($accessToken);


if($Res['ResultCode']=="1"&&$Res['Sign']==$sdk->SignMd5($Res['ResultCode'],urldecode($Res['Content']))){
	//Content参数需要urldecode后再进行base64解码
	$result=base64_decode(urldecode($Res['Content']));
	//json解析
	$Item=extract(json_decode($result,true));
	//根据获取的信息，执行业务处理


	//打印$Item信息
    $new = new ManageDk();
    $new->manageDkUser($area,$UID,$server);
}
?>