<?php

require_once 'YlSdk.php';


$area = $_GET['area'];
$username = $_GET['username'];
$server = isset($_GET['server'])?$_GET['server']:'';

$Sdk = new YlSdk();
$Sdk->check_user_login($area,$username,$server);
?>
