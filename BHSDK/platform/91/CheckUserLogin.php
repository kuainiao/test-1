<?php

require_once 'NineOneSdk.php';

$Uin = $_GET['Uin'];
$SessionId = $_GET['SessionId'];
$area = $_GET['area'];
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new NineOneSdk();
$Sdk->check_user_login($area, $Uin, $SessionId,$server);
?>