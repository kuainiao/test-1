<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title></title>
    <META http-equiv=Content-Type content="text/html; charset=utf-8">
</head>
<?php
date_default_timezone_set('Asia/ShangHai');
require_once dirname(__FILE__).'/'.'../../models/PayModel.php';
require_once dirname(__FILE__).'/'.'../../halo/HaloLog.php';

$privateKey="ksgjihhGDGLJGHACZGHL"; //密钥
$certFile = "./ShenzhoufuPay.cer"; //神州付证书文件

//获得服务器返回数据
$version = $_REQUEST['version']; //版本号
$merId = $_REQUEST['merId']; //商户ID
$payMoney =$_REQUEST['payMoney']; //订单金额
$orderId = $_REQUEST['orderId']; //订单号
$payResult = $_REQUEST['payResult']; //订单结果 1：成功 0：失败
$privateField = $_REQUEST['privateField']; //商户私有数据
$payDetails = $_REQUEST['payDetails']; //详情
$md5String = $_REQUEST['md5String']; //MD5校验串
$signString = $_REQUEST['signString']; //证书签名
$myCombineString=$version.$merId.$payMoney.$orderId.$payResult.$privateField.$payDetails.$privateKey;
$myMd5String=md5($myCombineString);


if($myMd5String==$md5String)
{
    //校验证书签名
    $fp = fopen($certFile, "r");
    $cert = fread($fp, 8192);
    fclose($fp);
    $pubkeyid = openssl_get_publickey($cert);

    if(openssl_verify($md5String,base64_decode($signString),$pubkeyid,OPENSSL_ALGO_MD5)==1)
    {
        if($payResult==1)
        {
            $newDb = new PayModel();
            $privateFieldInDb = $newDb->selectSZPayOrderInfo($orderId, 'privateField');
            if($privateField == $privateFieldInDb)
            {
                $orderStatus = $newDb->selectSZPayOrderInfo($orderId, 'orderStatus');
                if ($orderStatus == 0)
                {
                    $gameId = $newDb->selectSZPayOrderInfo($orderId, 'gameId');
                    $uid = $newDb->selectSZPayOrderInfo($orderId, 'uid');
                    $orderMoney = $newDb->selectSZPayOrderInfo($orderId, 'orderMoney');
                    $itemName = $newDb->selectSZPayOrderInfo($orderId, 'itemName');
                    $cpInfo = $newDb->selectSZPayOrderInfo($orderId, 'cpInfo');
                    $orderCreateTime = $newDb->selectSZPayOrderInfo($orderId, 'createTime');
                    $partner = $newDb->selectSZPayOrderInfo($orderId, 'partner');
                    $returnPayNotifyUrl = $newDb->selectPayNotifyUrl($gameId);
                    $gameKey = $newDb->selectGameKey($gameId);
                    $payTime = time();
                    $payStatus=1;
                    $channel = 102;
                    $sign = md5($gameId.$uid.$itemName.$orderMoney.$orderId.$cpInfo.$orderCreateTime.$payTime.$payStatus.$channel.$gameKey);

                    //返回给CP_game充值，充值回调
                    $returnInfo = $returnInfo = 'gameId='.$gameId.'&uid='.$uid.'&itemName='.$itemName.'&orderMoney='.$orderMoney.'&orderId='.$orderId.'&payStatus='.$payStatus.'&cpInfo='.$cpInfo.'&orderCreateTime='.$orderCreateTime.'&payTime='.$payTime.'&channel='.$channel.'&sign='.$sign;
                    HaloLog::addLog('sdklog', 'RETURN_CP:'.$returnPayNotifyUrl.'?'.$returnInfo);
                    $updateOrderStatus = $newDb->updateSZPayOrderStatus($orderId, 1, $payTime);
                    $i=0;
                    while($i<5)
                    {
                        $Res = request($returnPayNotifyUrl, $returnInfo,'get');
                        $i++;
                    }
                    $ResultArray = json_decode($Res['msg'], true);
                    if(!empty($ResultArray))
                    {
                        if($ResultArray['ErrorCode']==11)
                        {
                            if($privateField == $privateFieldInDb)
                            {
                                $updateOrderStatus = $newDb->updateSZPayOrderStatus($orderId, 2, $payTime);
                                if($partner=='cjgh01' || $partner=='cjgh02' || $partner=='cjgh03' || $partner=='cjgh04' || $partner=='cjgh05' || $partner=='cjgh06' || $partner=='cjgh07' || $partner=='cjgh08' || $partner=='cjgh09' || $partner=='cjgh10' || $partner=='cjgh11' || $partner=='cjgh12' ||
                                    $partner=='cjgh13' || $partner=='cjgh14' || $partner=='cjgh15'){
                                    $url = "http://pfsdk.9sky.me/order.php?pass=sfjklipognnfdjnjflgsndllgndsjh&type=szpay&partner=$partner&uid=$uid&orderId=$orderId&orderMoney=$orderMoneyStart&cpInfo=$cpInfo&createTime=$createTime&payTime=$payTime&cardType=2&cardMoney=200&privateField=lkioqwrnfgsdjk";
                                    file_get_contents($url);
                                    HaloLog::addLog('gonghui', $url);
                                }
                            }
                        }
                        else
                        {
                            $updateOrderStatus = $newDb->updateSZPayOrderStatus($orderId, $ResultArray['ErrorCode'], $payTime);
                        }
                    }
                    else
                    {
                        //orderstatus为空,即返回为空
                        $updateOrderStatus = $newDb->updateSZPayOrderStatus($orderId, '', $payTime);
                    }
                    //输出订单号返回给神州付
                    echo $orderId;
                }
                else
                {
                    //输出订单号返回给神州付
                    echo $orderId;
                }
            }
        }
        else
        {
            //todo...失败
            echo 'false';
        }
    }
    else
    {
        echo "二级签名校验失败！";
        while ($msg = openssl_error_string()){
            echo $msg . "<br/>\n";
        }

    }
}
else
{
    echo 'MD5校验失败';
}

/**
 * 执行一个 HTTP 请求
 *
 * @param string 	$Url 	执行请求的Url
 * @param mixed	$Params 表单参数
 * @param string	$Method 请求方法 post / get
 * @return array 结果数组
 */
function request($Url, $Params, $Method='post'){

    $Curl = curl_init();//初始化curl

    if ('get' == $Method){//以GET方式发送请求
        curl_setopt($Curl, CURLOPT_URL, "$Url?$Params");
    }else{//以POST方式发送请求
        curl_setopt($Curl, CURLOPT_URL, $Url);
        curl_setopt($Curl, CURLOPT_POST, 1);//post提交方式
        curl_setopt($Curl, CURLOPT_POSTFIELDS, $Params);//设置传送的参数
    }

    curl_setopt($Curl, CURLOPT_HEADER, false);//设置header
    curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
    curl_setopt($Curl, CURLOPT_CONNECTTIMEOUT, 3);//设置等待时间

    $Res = curl_exec($Curl);//运行curl
    $Err = curl_error($Curl);

    if (false === $Res || !empty($Err)){
        $Errno = curl_errno($Curl);
        $Info = curl_getinfo($Curl);
        curl_close($Curl);

        return array(
            'result' => false,
            'errno' => $Errno,
            'msg' => $Err,
            'info' => $Info,
        );
    }
    curl_close($Curl);//关闭curl
    return array(
        'result' => true,
        'msg' => $Res,
    );
}