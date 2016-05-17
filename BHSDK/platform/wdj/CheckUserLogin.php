<?php
require_once 'WdjSdk.php';
$area = isset($_GET['area'])?$_GET['area']:null;
$uid = isset($_GET['uid'])?$_GET['uid']:null;//用户登录账号
$token = isset($_GET['token'])?$_GET['token']:null;//当前登录用户会话id
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new WdjSdk();
$Sdk->check_user_login($area,$uid,$token,$server);
?>