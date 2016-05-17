<?php
/**
 * Created by PhpStorm.
 * User: YANG
 * Date: 14-6-17
 * Time: 下午7:58
 */

header("Content-type: text/html; charset=utf-8");
require_once dirname(__FILE__).'/'.'sdk/response.php';
require_once dirname(__FILE__).'/'.'sdk/UserRegister.php';


$gameId  = $_GET['gameId'];
$systemType = $_GET['systemType'];

if(isset($_GET['partner']))//是否存在"id"的参数
{
    $partner = $_GET['partner'];//存在
}
else
{
    $partner = 0;
}
if(isset($gameId)&&isset($systemType))
{
    $username = 'wzsg'.createRand(6);
    $userpass = md5(createRand(8));
}
else
{
    echo constant('NO_GAMEID_OR_SYSTYPE');
    exit;
}

function createRand($length)
{
    $rand = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';  //定义字符池
    for($i=0;$i<$length;$i++)
    {
        $rand .= $pattern{mt_rand(0,35)};  //从a-Z选择生成随机数
    }
    return $rand; // 终止函数的执行和从函数中返回一个值
}

$new = new Register();
$return = $new->UserRegister($oldUsername='', $oldUserpass='', $username, $userpass, $gameId, $systemType, $partner, 'oneKey');

if($return == '116')
{
    $return = $new->UserRegister($oldUsername='', $oldUserpass='', $username, $userpass, $gameId, $systemType, $partner, 'oneKey');
}
