<?php

require_once 'XmSdk.php';

$area = $_GET['area'];
$uid = $_GET['uid'];
$SessionId = $_GET['SessionId'];
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new XmSdk();
$Sdk->check_user_login($area,$uid,$SessionId,$server);
?>