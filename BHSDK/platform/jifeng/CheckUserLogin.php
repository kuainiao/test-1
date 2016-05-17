<?php

require_once 'JfSdk.php';
$area = $_GET['area'];
$token = isset($_GET['token'])? urlencode($_GET['token']) :'';
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new JfSdk();
$Sdk->check_user_login($area,$token,$server);
?>