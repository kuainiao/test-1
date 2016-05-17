<?php
header("Content-type: text/html; charset=utf-8");

$appKey = '5a67ppQM9H4GDN9t336qClL7dz7RI9Qn';
$app_id = $_GET['app_id'];
$serv_id = $_GET['serv_id'];
$player_id = $_GET['player_id'];
$usr_id = $_GET['usr_id'];
$coin = $_GET['coin'];
$money = $_GET['money'];
$add_time = $_GET['add_time'];
$good_code = $_GET['good_code'];
$sign = $_GET['sign'];

$my_sign = md5($app_id.$serv_id.$player_id.$usr_id.$coin.$money.$add_time.$appKey);
if($my_sign == $sign){
    $order = date("Ymdhis").'-'.$player_id.'-'.$usr_id.'-'.createRandString(6);
    $array = array("success"=>"0", "desc"=>"$order");
    echo json_encode($array);
}
else{
    $array = array("success"=>"1", "desc"=>"参数错误");
    echo json_encode($array);
}

function createRandString($length)
{
    $rand = '';
    $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';  //定义字符池
    for($i=0;$i<$length;$i++)
    {
        $rand .= $pattern{mt_rand(0,35)};  //从a-Z选择生成随机数
    }
    return $rand; // 终止函数的执行和从函数中返回一个值
}