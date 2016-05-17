<?php
require_once 'DJoySdk.php';

$area = $_GET['area'];
$mid = $_GET['mid'];
$token = $_GET['token'];
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$Sdk = new DJoySdk();
$Res = $Sdk->check_user_login($area, $mid, $token,$server,$channel_type=2);

?>