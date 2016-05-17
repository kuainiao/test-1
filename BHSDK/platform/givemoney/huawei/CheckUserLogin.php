<?php

require_once 'HwSdk.php';


$area = $_GET['area'];
$access_token = urlencode($_GET['access_token']);
$access_token = str_replace('+','%2B',$access_token);
$server = isset($_GET['server'])?$_GET['server']:'';

$Sdk = new HwSdk();
$Sdk->check_user_login($area,$access_token,$server);
