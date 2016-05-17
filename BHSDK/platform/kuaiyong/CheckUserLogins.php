<?php

require_once 'KySdk.php';


$area 		= isset($_GET['area'])    ? $_GET['area']:'';
$tokenkey 	= isset($_GET['tokenkey'])? $_GET['tokenkey']:'';
$channel 	= isset($_GET['channel']) ? $_GET['channel']:2;
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new KySdk();
$Sdk->check_user_login($area,$tokenkey,$server,$channel);
?>