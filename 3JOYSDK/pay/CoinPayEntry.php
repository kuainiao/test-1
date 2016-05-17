<?php
/**
 * Created by PhpStorm.
 * User: YANG
 * Date: 14-5-23
 * Time: 下午1:52
 */
require_once dirname(__FILE__).'/'.'szpay_coin/send.php';
require_once dirname(__FILE__).'/'.'alipay_coin/send.php';

class PayEntry
{
    public function szPay($uid, $orderMoney, $cardTypeCombine, $cardMoney, $cardNum, $cardPwd)
    {
        $newSzPay = new SendSzPay();
        $newSzPay->sendToSZPay($uid, $orderMoney, $cardTypeCombine, $cardMoney, $cardNum, $cardPwd);
    }
    public function aliPay($uid, $orderMoney)
    {
        $newAlipay = new SendAlipay();
        $newAlipay->sendToAlipay($uid, $orderMoney);
    }
}