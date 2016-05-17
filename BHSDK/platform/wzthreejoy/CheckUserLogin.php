<?php
/**
 * Created by PhpStorm.
 * User: YANG
 * Date: 14-5-27
 * Time: 下午5:30
 */

require_once 'WZThreeJoySdk.php';

$systemType = $_GET['systemType'];//1=android,2=ios
$uid = $_GET['uid'];
$session = $_GET['session'];
$area = $_GET['area'];
$server = isset($_GET['server'])?$_GET['server']:'';

$sdk = new WZThreeJoySdk();
$sdk->check_user_login($area, $uid, $session,$server,$systemType=1);
