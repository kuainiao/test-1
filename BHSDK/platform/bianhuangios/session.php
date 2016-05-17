<?php
/**
 * Created by PhpStorm.
 * User: YANG
 * Date: 14-6-16
 * Time: 下午3:01
 */
header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__).'/'.'sdk/Session.php';

$gameId  = $_GET['gameId'];
$uid     = $_GET['uid'];
$session = $_GET['session'];
$sign    = $_GET['sign'];

if(isset($gameId)&&isset($uid)&&isset($session)&&isset($sign))
{
    $new = new Session();
    $new->checkSession($gameId, $uid, $session, $sign);
}
else
{
    //3=参数无效
    $response = json_encode(
        array("ErrorCode" => "3", "ErrorDesc" => "参数无效")
    );
    echo $response;
}
