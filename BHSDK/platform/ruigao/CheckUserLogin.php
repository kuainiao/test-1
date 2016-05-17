<?php

require_once 'RgSdk.php';


$area 		= isset($_GET['area'])    ? $_GET['area']:'';
$uid 	= isset($_GET['uin'])? $_GET['uin']:'';
$session 	= isset($_GET['sessionkey'])? $_GET['sessionkey']:'';
$channel 	= isset($_GET['channel']) ? $_GET['channel']:1;
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new RgSdk();
$Sdk->check_user_login($area,$uid,$session,$server,$channel);
?>