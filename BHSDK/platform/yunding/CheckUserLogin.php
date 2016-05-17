<?php

require_once 'YdSdk.php';


$area = $_GET['area'];
$userid = $_GET['userid'];
$usertoken = $_GET['usertoken'];
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';


$Sdk = new YdSdk();
$Sdk->check_user_login($area,$userid,$usertoken,$server);
?>
