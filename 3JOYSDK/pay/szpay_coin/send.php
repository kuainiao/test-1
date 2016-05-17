<?php
date_default_timezone_set('Asia/ShangHai');
require_once dirname(__FILE__).'/'.'../../models/PFUserModel.php';
require_once dirname(__FILE__).'/'.'../../halo/HaloLog.php';


class SendSzPay
{
    public function sendToSZPay($uid, $orderMoney, $cardTypeCombine, $cardMoney, $cardNum, $cardPwd)
    {
        //组织请求数据
        $szfGatewayUrl = "http://pay3.shenzhoufu.com/interface/version3/serverconnszx/entry-noxml.aspx?";//神州付网关地址
        $privateKey = "ksgjihhGDGLJGHACZGHL";//密钥
        $desKey = '40BiPXbpa98=';
        $signString = '';
        $serverReturnUrl = "http://sdk.3joy.cn/pay/szpay_coin/serverReceive.php";//服务器返回地址
        $merId = "188334";//商户ID
        $version = "3";//版本号
        $merUserName = "北京闪卓互动科技有限公司"; //商户网站的用户的姓名
        $merUserMail = "yangdongjie@9sky.me"; //商户网站的用户的Email
        $today = date("Ymd");
        $payMoney = $orderMoney*100;
        $randNum = rand(1000, 9999);
        $orderId = "$today"."-"."$merId"."-"."$uid"."$randNum";//订单号（格式：yyyyMMdd-merId-SN） *
        $privateField = $this->randkeys(16);//商户私有数据
        $verifyType = "1";//数据校验方式
        $orderStatus = "0";
        $cardInfo = $this->getDesCardInfo($cardMoney, $cardNum, $cardPwd, $desKey);
        //进行MD5加密
        $combineString=$version.$merId.$payMoney.$orderId.$serverReturnUrl.$cardInfo.$privateField.$verifyType.$privateKey;
        $md5String=md5($combineString);

        //执行请求
        $url = $szfGatewayUrl."version=$version&merId=$merId&payMoney=$payMoney&orderId=$orderId&returnUrl=$serverReturnUrl&cardInfo=".urlencode($cardInfo)."&merUserName=$merUserName&merUserMail=$merUserMail&privateField=$privateField&verifyType=$verifyType&cardTypeCombine=$cardTypeCombine&md5String=$md5String&signString=$signString";
        $state = file_get_contents($url);
        HaloLog::addLog('sdklog', $url);
        if($state == 200)
        {
            $cardType = $cardTypeCombine;
            $createTime = time();
            $payTime = 0;
            $newDb = new PFUserModel();
            $insert = $newDb->insertSZPayOrder($uid,$orderId,$orderMoney,$cardType,$cardMoney,$privateField,$orderStatus,$createTime,$payTime);
            header("Location: http://sdk.3joy.cn/success.php?orderMoney=$orderMoney&orderId=$orderId");
        }
        else
        {
            header("Location: http://sdk.3joy.cn/fail.html");
        }
    }

    private function randkeys($length)
    {
        $key = '';
        $pattern = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';  //定义字符池
        for($i=0;$i<$length;$i++)
        {
            $key .= $pattern{mt_rand(0,35)};  //从0-Z选择生成随机数
        }
        return $key; // 终止函数的执行和从函数中返回一个值
    }

    // 生成通过des加密后的cardinfo，并进行base64加密
    private function getDesCardInfo($cardmoney,$cardnum,$cardpwd,$deskey)
    {
        $str=$cardmoney."@".$cardnum."@".$cardpwd;
        $size = mcrypt_get_block_size('des', 'ecb');
        $input = $this->pkcs5Pad($str, $size);
        $td = mcrypt_module_open(MCRYPT_DES,'','ecb',''); //使用MCRYPT_DES算法,ecb模式
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        $key=base64_decode($deskey);
        mcrypt_generic_init($td, $key, $iv); //初始处理
        //加密
        $encrypted_data = mcrypt_generic($td, $input);
        //结束处理
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $encode = base64_encode($encrypted_data);
        return $encode;
    }

    private function pkcs5Pad ($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
}