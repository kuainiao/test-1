<?php

require_once 'YxSdk.php';
$area = $_GET['area'];
$uid = isset($_GET['openid'])? $_GET['openid'] :'';
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new YxSdk();
$Sdk->check_user_login($area,$uid,$server);
?>