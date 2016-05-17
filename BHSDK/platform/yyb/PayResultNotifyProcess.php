<?php
header("Content-type: text/html; charset=utf-8");

require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';
require_once __DIR__ .'/Api.php';
// 应用基本信息，需要替换为应用自己的信息，必须和客户端保持一致
// 需要登录腾讯开放平台 open.qq.com，注册开发者，并创建移动应用，审核通过后可以获得APPID和APPKEY
$appid = '1105389198';
$appkey = 'kNWHgtMOy7w4tdQl';
// 应用支      APPKEY
//付基本信息,需要替换为应用自己的信息必须和客户端保持一致
// 需要登录腾讯开放平台管理中心 http://op.open.qq.com/，选择已创建的应用进入，然后进入支付结算，完成支付的接入配置
$pay_appid = 1104936059;
$pay_appkey = 'i0o7LppGJSpNVGRE';

// YSDK后台API的服务器域名
// 调试环境: ysdktest.qq.com
// 正式环境: ysdk.qq.com
// 调试环境仅供调试时调用，调试完成发布至现网环境时请务必修改为正式环境域名
$server_name = 'ysdktest.qq.com';

// 用户的OpenID，从客户端YSDK登录返回的LoginRet获取
$openid = '1B3C4822236840403571E30922A8F088';
// 用户的openkey，从客户端YSDK登录返回的LoginRet获取
$openkey = '9F2806884C8703F7FEA1490B474940F4';

// 支付接口票据, 从客户端YSDK登录返回的LoginRet获取
$pay_token='CC3CB5E77BDA623016CFA3162DA3EFA8';
// 支付接口票据, 从客户端YSDK登录返回的LoginRet获取
$pf='desktop_m_qq-73213123-android-73213123-qq-100703379-0EF80D52AE52324D51958FE6EDC3DBF3';
// 支付接口票据, 从客户端YSDK登录返回的LoginRet获取
$pfkey= '94d7e9a1f441b69f26b113214760100e';
// 支付分区, 需要先在open.qq.com接入支付结算，并配置了分区
// 注意是分区ID，默认为1，如果在平台配置了分区需要传入对应的分区ID！
$zoneId=1;
// 创建YSDK实例
/// 当前UNIX时间戳
$ts=time();
// 用户的IP，可选，默认为空
$userip = '';

$sdk = new Api($appid, $appkey);
// 设置支付信息
//$sdk->setPay($pay_appid, $pay_appkey);
// 设置YSDK调用环境
$sdk->setServerName($server_name);


$amt = 10;
$params = array(
        'openid' => $openid,
        'openkey' => $openkey,
        'pay_token' => $pay_token,
        'ts' => $ts,
        'pf' => $pf,
        'pfkey' => $pfkey,
        'zoneid' => $zoneid,
        'amt' => $amt,
    );

$ret = pay_m($sdk, $params, $accout_type);
function pay_m($sdk, $params, $cookie){
    $method="post";
    $script_name = '/mpay/pay_m';
    $cookie["org_loc"] = urlencode($script_name);
    $protocol ='https';
    $accout_type='qq';
    return $sdk->api_pay($script_name, $accout_type,$params,$method,$protocol);
}


if($ret){
	//验签名失败
	die('fail');
}
$re = $_POST;
$Res = pay_result_notify_process($re);


 function pay_result_notify_process($re)
    {
        $thirdType = 16;
		if($re['result']!=1){
			die('fail');
		}
		$uid = $re['openid'];
		if($uid>9223372036854775807){
			//attension
            $uid = abs(crc32($uid));
		}
		$server = $re['ext1'];
        $newdb = new PlatModel();
        $amount = 30;
        $selectOrderId = $newdb->selectPayOrder($uid,$re['order_id'],'platform_yyb_pay');
        //验证是否充值过
        if ($re['order_id'] == $selectOrderId)
        {
            die('success');
        }
        else
        {
            $sdkPaytime = time();
            $insert = $newdb->insertYYBPay($amount,$uid,$server,$sdkPaytime,$channel_type=1);
            if($insert)
            {
            	$area = Ext::getInstance()->getArea($server);
				
                $gameMoney = $re['money']/10;
                $accountId = $newdb->selectThirdUserAccountId('platform_yyb_user',$area,$uid);
                $username = $newdb->selectThirdUserWzUsername('platform_yyb_user',$area,$uid);
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server,$thirdType,$username,$accountId,$gameMoney,$re['order_id']);
				$newdb->insertUserpaylog($uid, $thirdType, $re['order_id'], $sdkPaytime, $area, $server, $re['money']/100, $accountId);
                die('success');
            }
            else
            {
            	die('插入游戏平台订单失败');
            }
        }
    }
?>