<?php
require_once 'QhSdk.php';

$area                = isset($_GET['area'])                ? $_GET['area']                : null;
$access_token  = isset($_GET['access_token']) ? $_GET['access_token']  : null;
$server  = isset($_GET['server']) ? $_GET['server']  : '';


$Sdk = new QhSdk();
$Sdk->check_login($area,$access_token,$server);
?>