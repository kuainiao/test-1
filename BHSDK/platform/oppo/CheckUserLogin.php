<?php

require_once 'OpSdk.php';
$area = $_GET['area'];
$token = $_GET['oauth_token'];
file_put_contents('/tmp/101',$token);
//$token = str_replace(' ','',$token);
//file_put_contents('/tmp/103',$token);
$token = urlencode($token);
file_put_contents('/tmp/102',$token);

$field = $_GET['ssoid'];
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new OpSdk();
$Sdk->check_user_login($area,$token,$field,$server);
?>