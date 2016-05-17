<?php
require_once 'KLSdk.php';

$area  = isset($_GET['area'])  ? $_GET['area']  : null;
$klsso = isset($_GET['klsso']) ? $_GET['klsso']  : null;
$channel = isset($_GET['channel'])?$_GET['channel']:3;
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}
if(strlen($klsso) != 172){
	exit('klsso error !');
}
$Sdk = new KLSdk();
$Sdk->check_user_login($area,$klsso,$server,$channel);
?>