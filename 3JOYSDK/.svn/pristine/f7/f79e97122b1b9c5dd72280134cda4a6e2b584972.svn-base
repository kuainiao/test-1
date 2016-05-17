<?php
/**
 * Created by PhpStorm.
 * User: YANG
 * Date: 14-6-17
 * Time: 下午7:58
 */

header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__).'/'.'sdk/response.php';
require_once dirname(__FILE__).'/'.'sdk/TxUserRegister.php';


$gameId  = $_GET['gameId'];
$systemType = $_GET['systemType'];
$userpass = $_GET['userpass'];

if(isset($_GET['uuid']))//是否存在"id"的参数
{
    $uuid = $_GET['uuid'];//存在
}
else
{
    $uuid = 1;
}

if(isset($_GET['partner']))//是否存在"id"的参数
{
    $partner = $_GET['partner'];//存在
}
else
{
    $partner = 1;
}
if(isset($gameId)&&isset($systemType))
{
    $username = mt_rand('100000000','999999999');
    $new = new Register();
    $return = $new->UserRegister($oldUsername='', $oldUserpass='', $username, $userpass, $gameId, $systemType, $partner, $uuid, 'oneKey');
}
else
{
    echo constant('NO_GAMEID_OR_SYSTYPE');
    exit;
}