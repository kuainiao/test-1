<?php
require_once 'AzSdk.php';

$area = isset($_GET['area'])?$_GET['area']:null;
$account = isset($_GET['account'])?$_GET['account']:null;//用户登录账号
$sid = isset($_GET['sid'])?$_GET['sid']:null;//当前登录用户会话id
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new AzSdk();
$Sdk->check_user_login($area,$account,$sid,$server);
?>