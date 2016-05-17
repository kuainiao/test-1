<?php
require_once 'PSdk.php';
$len = isset($_GET['len'])?$_GET['len']:null;//二进制的token_ken的长度
$token_key = isset($_GET['token_key'])?$_GET['token_key']:null;//当前登录用户会话id
$Sdk = new PSdk();
$Sdk->check_user_login($len,$token_key);	
		

?>
