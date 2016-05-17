<?php
header("Content-type: text/html; charset=utf-8");
require_once '../../models/PlatModel.php';

/*Cp-ID：20141202104028232559
Cp-Key：a17c2af7e78b6a9d00416fcd2bb712a1
App-ID：54315b4473b4fb16c38929890133f526*/

$version = '1.0.0';
$signMethod = 'MD5';
$signature = '';
$key = strtolower(md5('a17c2af7e78b6a9d00416fcd2bb712a1'));
$storeId = '20141202104028232559';
$appId = '54315b4473b4fb16c38929890133f526';
$mySign = 'jsdgkjJHHJ35719Glghafh';
$storeOrder = date("Ymdhis").substr(md5(time()),8,2).createRandString(6);
$notifyUrl = 'http://platform.9sky.me/platform/vivo/PayResultNotifyProcess.php';
$orderTime = date("YmdHis");
$sign = $_GET['sign'];
$uid = $_GET['uid'];
$server = $_GET['server'];
$orderAmount = $_GET['orderAmount'];
$orderTitle = $_GET['orderTitle'];
$orderDesc = $_GET['orderDesc'];


if($sign == $mySign)
{
    $Url = 'https://pay.vivo.com.cn/vivoPay/getVivoOrderNum';

    $param = "appId=$appId&notifyUrl=$notifyUrl&orderAmount=$orderAmount&orderDesc=$orderDesc&orderTime=$orderTime&orderTitle=$orderTitle&storeId=$storeId&storeOrder=$storeOrder&version=$version&$key";
    $signature = md5($param);
    $param = "version=$version&signMethod=$signMethod&signature=$signature&storeId=$storeId&appId=$appId&storeOrder=$storeOrder&notifyUrl=$notifyUrl&orderTime=$orderTime&orderAmount=$orderAmount&orderTitle=$orderTitle&orderDesc=$orderDesc";

    // 发起请求
    $Res = vpost($Url,$param);
    $ResultArray = json_decode($Res, true);
}



if($ResultArray['respCode'] == '200')
{
    $newdb = new PlatModel();
    $insert = $newdb->insertVPay($orderAmount,$uid,$ResultArray['vivoOrder'],$server,0);
    echo $ResultArray['vivoOrder'].'_'.$ResultArray['vivoSignature'];
}
else{
    echo $ResultArray['respCode'];
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


function vpost($url,$data){ // 模拟提交数据函数
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno'.curl_error($curl);//捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据
}