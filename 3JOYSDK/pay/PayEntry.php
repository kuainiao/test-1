<?php
/**
 * Created by PhpStorm.
 * User: YANG
 * Date: 14-5-23
 * Time: 下午1:52
 */
require_once dirname(__FILE__).'/'.'szpay/send.php';
require_once dirname(__FILE__).'/'.'alipay/send.php';
require_once dirname(__FILE__).'/'.'lightcoin/coinpay.php';

class PayEntry
{
    public function szPay($partner,$gameId, $uid, $orderMoney, $itemName, $cpInfo, $cardTypeCombine, $cardMoney, $cardNum, $cardPwd)
    {
        $newSzPay = new SendSzPay();
        $newSzPay->sendToSZPay($partner,$gameId, $uid, $orderMoney, $itemName, $cpInfo, $cardTypeCombine, $cardMoney, $cardNum, $cardPwd);
    }
    public function aliPay($partner,$gameId, $uid, $orderMoney, $itemName, $cpInfo)
    {
        $newAlipay = new SendAlipay();
        $newAlipay->sendToAlipay($partner,$gameId, $uid, $orderMoney, $itemName, $cpInfo);
    }
    public function coinPay($partner,$gameId, $uid, $orderMoney, $itemName, $cpInfo)
    {
        lightCoinPay($partner,$gameId, $uid, $orderMoney, $itemName, $cpInfo);
    }
}