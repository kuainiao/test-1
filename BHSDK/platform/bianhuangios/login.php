<?php
/**
 * Created by PhpStorm.
 * User: YANG
 * Date: 14-5-15
 * Time: 上午11:41
 */
require_once dirname(__FILE__).'/'.'sdk/UserEntry.php';
//获取SDK用户名密码
$username = $_GET['username'];
$userpass = $_GET['userpass'];
$gameId = $_GET['gameId'];
$systemType = $_GET['systemType'];
if(isset($_GET['partner']))
{
    $partner = $_GET['partner'];
}
else
{
    $partner = 0;
}



$pfSdk = new UserEntry();
$pfSdk->checkUserLogin($username,$userpass,$gameId,$systemType,$partner);