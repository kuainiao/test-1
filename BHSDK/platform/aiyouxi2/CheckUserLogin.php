<?php
/**
 * Created by PhpStorm.
 * User: YANG
 * Date: 14-5-27
 * Time: 下午5:30
 */

require_once 'AiyouxiSdk.php';

$systemType = $_GET['systemType'];//1=android,2=ios
$code = $_GET['uid'];
$area = $_GET['area'];
$server = isset($_GET['server'])?$_GET['server']:'';

$sdk = new AiyouxiSdk();
$sdk->check_user_login($area,$code,$server,$channel_type=1);
