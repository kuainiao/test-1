<?php
date_default_timezone_set('Asia/ShangHai');
require_once dirname(__FILE__).'/'.'../../models/PayModel.php';
require_once dirname(__FILE__).'/'.'../../models/PFUserModel.php';
require_once dirname(__FILE__).'/'.'../../halo/HaloLog.php';


function lightCoinPay($partner,$gameId, $uid, $orderMoney, $itemName, $cpInfo)
{
    //扣除闪币-》加入闪币支付记录-》返回cp
    $newPay = new PFUserModel();
    $newSDKPay = new PayModel();
    $coin = $newPay->selectLightCoin($uid);
    if($coin >= $orderMoney*100)
    {
        $today = date("Ymd");
        $randNum = rand(1000, 9999);
        $orderId = "$today"."$gameId"."$uid"."$randNum";//订单号
        $newPay->updateUserLightCoin($uid, $orderMoney*100);
        $newSDKPay->insertCoinPay($uid,$gameId,$orderMoney,$orderId, 1, $itemName, 0);

        $orderCreateTime = $payTime = time();
        $returnPayNotifyUrl = $newSDKPay->selectPayNotifyUrl($gameId);
        $gameKey = $newSDKPay->selectGameKey($gameId);
        $payStatus=1;
        $channel = 100;
        $sign = md5($gameId.$uid.$itemName.$orderMoney.$orderId.$cpInfo.$orderCreateTime.$payTime.$payStatus.$channel.$gameKey);

        HaloLog::addLog('lightcoin', $gameId.'---'.$itemName.'---'.$orderMoney.'---'.$orderId.'---'.$cpInfo.'---'.$orderCreateTime.'---'.$payTime.'---'.$gameKey);

        //返回给CP_game充值，充值回调
        $returnInfo = 'gameId='.$gameId.'&uid='.$uid.'&itemName='.$itemName.'&orderMoney='.$orderMoney.'&orderId='.$orderId.'&payStatus='.$payStatus.'&cpInfo='.$cpInfo.'&orderCreateTime='.$orderCreateTime.'&payTime='.$payTime.'&channel='.$channel.'&sign='.$sign;

        HaloLog::addLog('lightcoin',$returnPayNotifyUrl.'?'.$returnInfo);

        $i=0;
        while($i<5)
        {
            $Res = request($returnPayNotifyUrl, $returnInfo,'get');
            $i++;
        }
        $ResultArray = json_decode($Res['msg'], true);
        if($ResultArray['ErrorCode']==11)
        {
            $newSDKPay->updateCoinPayStatus($orderId, 2, time());
            if($partner=='cjgh01' || $partner=='cjgh02' || $partner=='cjgh03' || $partner=='cjgh04' || $partner=='cjgh05' || $partner=='cjgh06' || $partner=='cjgh07' || $partner=='cjgh08' || $partner=='cjgh09' || $partner=='cjgh10' || $partner=='cjgh11' || $partner=='cjgh12' ||
                $partner=='cjgh13' || $partner=='cjgh14' || $partner=='cjgh15'){
                $url = "http://pfsdk.9sky.me/order.php?pass=sfjklipognnfdjnjflgsndllgndsjh&type=coin&partner=$partner&uid=$uid&orderId=$orderId&orderMoney=$orderMoneyStart&cpInfo=$cpInfo&createTime=$payTime&payTime=$payTime";
                file_get_contents($url);
                HaloLog::addLog('gonghui', $url);
            }
        }
        else
        {
            $newSDKPay->updateCoinPayStatus($orderId, $ResultArray['ErrorCode'], time());
        }

        $result = '恭喜您！充值成功！';
        header("Location: http://sdk.3joy.cn/alisuccess.php?status=$result&orderId=$orderId");
    }
    else
    {
        header("Location: http://sdk.3joy.cn/coinfail.html");
    }
}


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