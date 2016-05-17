<?php

require_once 'MySdk.php';
$area = $_GET['area'];
$uid = isset($_GET['uid'])? $_GET['uid'] :'';
$server = isset($_GET['server'])?$_GET['server']:'';
$token = isset($_GET['token'])?$_GET['token']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new MySdk();
$Sdk->check_user_login($area,$uid,$token,$server);
?>