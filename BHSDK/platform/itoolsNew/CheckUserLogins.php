<?php

require_once 'IsSdk.php';


$area 		= isset($_GET['area'])    ? $_GET['area']:'';
$uid 		= isset($_GET['uid'])     ? (int)$_GET['uid']:'';
$sessionid  	= isset($_GET['sessionid'])   ? $_GET['sessionid']:'';
$channel 	= isset($_GET['channel']) ? $_GET['channel']:2;
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new IsSdk();
$Sdk->check_user_login($area,$sessionid,$uid,$server,$channel);
?>