<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
require_once dirname(__FILE__).'/'.'../../halo/HaloLog.php';
require_once dirname(__FILE__).'/'.'../../models/PFUserModel.php';

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();


if($verify_result) {

    $notify_data = $_POST['notify_data'];
	$doc = new DOMDocument();
	$doc->loadXML($notify_data);
	
	if( ! empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {
		//商户订单号
		$out_trade_no = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
		//支付宝交易号
		$trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
		//交易状态
		$trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
        //购买者账号
        $buyer_email = $doc->getElementsByTagName( "buyer_email" )->item(0)->nodeValue;


		if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
            $new = new PFUserModel();
            $selectOrderStatus = $new->selectAliPayOrderInfo($out_trade_no, 'orderStatus');
            if($selectOrderStatus != 0)
            {
                echo "success";
            }
            else
            {
                $money = $new->selectAliPayOrderInfo($out_trade_no, 'money');
                $uid = $new->selectAliPayOrderInfo($out_trade_no, 'uid');
                $selectUid = $new->selectUidInMoney($uid);
                HaloLog::addLog('alipay_coin', '$selectUid='.$selectUid);
                if(!empty($selectUid)){
                    //充值闪币(1:100)
                    $lightCoin = $money*100;
                    $updateUserMoney = $new->updateUserMoney($lightCoin, $money, $uid);
                    HaloLog::addLog('alipay_coin', 'updateOk');
                }
                else{
                    $lightCoin = $money*100;
                    $insertUserMoney = $new->insertUserMoney($lightCoin, $money, $uid);
                    HaloLog::addLog('alipay_coin', 'insertOk');
                }
                $updateOrder = $new->updateAliPayOrderStatus($out_trade_no);
                echo "success";
            }
		}
		else{
            echo "fail";
		}
	}
}
else {
    //验证失败
    echo "fail";
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
?>