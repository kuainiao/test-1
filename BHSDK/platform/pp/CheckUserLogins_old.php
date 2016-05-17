<?php
require_once 'PSdk.php';
$area = isset($_GET['area'])?$_GET['area']:null;
$len = isset($_GET['len'])?$_GET['len']:null;//二进制的token_ken的长度
$token_key = isset($_GET['token_key'])?$_GET['token_key']:null;//当前登录用户会话id

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new PSdk();
$Sdk->check_user_login($area,$len,$token_key);
?>