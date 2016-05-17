<?php
/**
 * Created by PhpStorm.
 * User: YANG
 * Date: 14-6-17
 * Time: 下午6:07
 */
header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__).'/'.'sdk/response.php';
require_once dirname(__FILE__).'/'.'sdk/UserRegister.php';


if(is_array($_GET)&&count($_GET)>0)//先判断是否通过get传值了
{
    if(isset($_GET['username']))//是否存在"id"的参数
    {
        $username = $_GET['username'];//存在
    }
    else
    {
        echo constant('GET_NO_USERNAME');
        exit;
    }
    if(isset($_GET['userpass']))//是否存在"id"的参数
    {
        $userpass = $_GET['userpass'];//存在
    }
    else
    {
        echo constant('GET_NO_USERPASS');
        exit;
    }
    if(isset($_GET['gameId']))//是否存在"id"的参数
    {
        $gameId = $_GET['gameId'];//存在
    }
    else
    {
        echo constant('GET_NO_GAMEID');
        exit;
    }
    if(isset($_GET['systemType']))//是否存在"id"的参数
    {
        $systemType = $_GET['systemType'];//存在
    }
    else
    {
        echo constant('GET_NO_SYSTEMTYPE');
        exit;
    }
    if(isset($_GET['partner']))//是否存在"id"的参数
    {
        $partner = $_GET['partner'];//存在
    }
    else
    {
        $partner = 0;
    }
}
else
{
    echo constant('NO_GET');
    exit;
}



$pcreUsername = '/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]{6,20}$/u';
if(preg_match($pcreUsername, $username))
{
    if(isset($_GET['userpass']))
    {
        $new = new Register();
        $new->UserRegister($oldUsername='', $oldUserpass='', $username, $userpass, $gameId, $systemType, $partner, '');
    }
    else
    {
        echo constant('GET_NO_USERPASS');
        exit;
    }
}
else
{
    echo constant('USERNAME_ILLEGAL');
    exit;
}