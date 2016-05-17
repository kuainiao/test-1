<?php
require_once 'QhSdk.php';

$area                = isset($_GET['area'])                ? $_GET['area']                : null;
$access_token  = isset($_GET['access_token']) ? $_GET['access_token']  : null;
$server  = isset($_GET['server']) ? $_GET['server']  : '';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new QhSdk();
$Sdk->check_login($area,$access_token,$server);
?>