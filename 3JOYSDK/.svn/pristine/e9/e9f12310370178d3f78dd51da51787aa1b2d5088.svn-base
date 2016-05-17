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
if(isset($_GET['partner']))//是否存在"id"的参数
{
    $partner = $_GET['partner'];//存在
}
else
{
    $partner = 1;
}



$pfSdk = new UserEntry();
$pfSdk->checkUserLogin($partner, $username,$userpass,$gameId,$systemType);