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
require_once dirname(__FILE__).'/'.'../../models/PayModel.php';

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
			//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
					
			//注意：
			//该种交易状态只在两种情况下出现
			//1、开通了普通即时到账，买家付款成功后。
			//2、开通了高级即时到账，从该笔交易成功时间算起，过了签约时的可退款时限（如：三个月以内可退款、一年以内可退款等）后。
	
			//调试用，写文本函数记录程序运行情况是否正常
			//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
            HaloLog::addLog('pfsdklog', $out_trade_no.'----'.$trade_no.'----'.$trade_status.'----'.$buyer_email);

            $new = new PayModel();
            $selectOrderStatus = $new->selectAliPayOrderInfo($out_trade_no, 'orderStatus');
            if($selectOrderStatus != 0)
            {
                echo "success";
            }
            else
            {
                $gameId = $new->selectAliPayOrderInfo($out_trade_no, 'gameId');
                $uid = $new->selectAliPayOrderInfo($out_trade_no, 'uid');
                $orderMoney = $new->selectAliPayOrderInfo($out_trade_no, 'orderMoney');
                $itemName = $new->selectAliPayOrderInfo($out_trade_no, 'itemName');
                $cpInfo = $new->selectAliPayOrderInfo($out_trade_no, 'cpInfo');
                $orderCreateTime = $new->selectAliPayOrderInfo($out_trade_no, 'createTime');
                $returnPayNotifyUrl = $new->selectPayNotifyUrl($gameId);
                $gameKey = $new->selectGameKey($gameId);
                $payTime = time();
                $sign = md5($gameId.$itemName.$orderMoney.$out_trade_no.$cpInfo.$orderCreateTime.$payTime.$gameKey);

                HaloLog::addLog('pfsdklog', $gameId.'---'.$itemName.'---'.$orderMoney.'---'.$out_trade_no.'---'.$cpInfo.'---'.$orderCreateTime.'---'.$payTime.'---'.$gameKey);


                //返回给CP_game充值，充值回调
                $returnInfo = 'gameId='.$gameId.'&uid='.$uid.'&itemName='.$itemName.'&orderMoney='.$orderMoney.'&orderId='.$out_trade_no.'&payStatus=1'.'&cpInfo='.$cpInfo.'&orderCreateTime='.$orderCreateTime.'&payTime='.$payTime.'&sign='.$sign;

                HaloLog::addLog('pfsdklog', $returnInfo);

                $updateOrder = $new->updateAliPayOrderStatus($out_trade_no, $trade_no, '1', $buyer_email , time());

                $i=0;
                while($i<5)
                {
                    $returnCp = request($returnPayNotifyUrl, $returnInfo, 'get');
                    $i++;
                }
                //HaloLog::addLog('pfsdklog', var_dump($returnCp));die();
                $updateOrder = $new->updateAliPayOrderStatus($out_trade_no, $trade_no, '2', $buyer_email, $payTime);
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