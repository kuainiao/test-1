<?php

require_once 'VSdk.php';


$area = $_GET['area'];
$access_token = urlencode($_GET['access_token']);
$server = isset($_GET['server'])?$_GET['server']:'';

$Sdk = new VSdk();
$Sdk->check_user_login($area,$access_token,$server);
