<?php
header("Content-type: text/html; charset=utf-8");

$app_key = '4Sxhrj7nJ04nRrei8pi86dCM02l81StE';
$app_id = 6061;
$serv_id = $_GET['serv_id'];
$player_id = $_GET['player_id'];
$usr_id = $_GET['usr_id'];
$order_id = $_GET['order_id'];
$coin = $_GET['coin'];
$money = $_GET['money'];
$create_time = $_GET['create_time'];
$good_code = $_GET['good_code'];
$sign = $_GET['sign'];

$my_sign = md5($app_id.$serv_id.$usr_id.$player_id.$order_id.$coin.$money.$create_time.$app_key);

if($my_sign == $sign){
    $order = date("Ymdhis").'-'.$player_id.'-'.$usr_id.'-'.createRandString(6);
    $array = array("err_code"=>"0", "desc"=>"$order");
    echo json_encode($array);
}
else{
    $array = array("err_code"=>"1", "desc"=>"参数错误");
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