<?php

require_once 'Model.php';

class PayModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function insertSZPayOrder($partner,$gameId,$uid,$orderId,$orderMoney,$itemName,$cpInfo,$cardType,$cardMoney,$privateField,$orderStatus,$createTime,$payTime)
	{
		$db = $this->db;
		$insert = $db->insertTable('sdk_sz_pay', array('NS_id'=>'', 'NS_partner'=>$partner, 'NS_game_id'=>$gameId, 'NS_uid'=>$uid, 'NS_order_id'=>$orderId,
            'NS_order_money'=>$orderMoney, 'NS_item_name'=>$itemName, 'NS_cp_info'=>$cpInfo, 'NS_card_type'=>$cardType, 'NS_card_money'=>$cardMoney,
            'NS_private_field'=>$privateField, 'NS_order_status'=>$orderStatus, 'NS_create_time'=>$createTime, 'NS_pay_time'=>$payTime));
		return $insert;
	}

    public function selectSZPayOrderInfo($orderId, $type)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('sdk_sz_pay', sprintf("NS_order_id = '%s';", $orderId));
        foreach($result as $row)
        {
            if ($type == 'gameId')
            {
                return $row['NS_game_id'];
            }
            elseif ($type == 'uid')
            {
                return $row['NS_uid'];
            }
            elseif ($type == 'orderMoney')
            {
                return $row['NS_order_money'];
            }
            elseif ($type == 'cpInfo')
            {
                return $row['NS_cp_info'];
            }
            elseif ($type == 'privateField')
            {
                return $row['NS_private_field'];
            }
            elseif ($type == 'orderStatus')
            {
                return $row['NS_order_status'];
            }
            elseif ($type == 'createTime')
            {
                return $row['NS_create_time'];
            }
            elseif ($type == 'itemName')
            {
                return $row['NS_item_name'];
            }
            elseif ($type == 'partner')
            {
                return $row['NS_partner'];
            }
            else
            {
                return $row['NS_pay_time'];
            }
        }
    }


    public function selectPayNotifyUrl($gameId)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('sdk_cp_info', sprintf("NS_game_id = '%s';", $gameId));
        foreach($result as $row)
        {
                return $row['NS_pay_notify_url'];
        }
    }

    public function selectGameKey($gameId)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('sdk_cp_info', sprintf("NS_game_id = '%s';", $gameId));
        foreach($result as $row)
        {
            return $row['NS_game_key'];
        }
    }

    public function updateSZPayOrderStatus($orderId, $orderStatus, $payTime)
    {
        $db = $this->db;
        $upOrderStatus = $db->updateTable('sdk_sz_pay', array('NS_order_status'=>$orderStatus,'NS_pay_time'=>$payTime), "NS_order_id = '$orderId';");
        return $upOrderStatus;
    }



    //alipay
    public function insertAliPayOrder($partner,$gameId,$uid,$orderId,$orderMoney,$itemName,$cpInfo,$orderStatus,$createTime,$payTime)
    {
        $db = $this->db;
        $insert = $db->insertTable('sdk_alipay', array('NS_id'=>'', 'NS_partner'=>$partner, 'NS_game_id'=>$gameId, 'NS_uid'=>$uid, 'NS_order_id'=>$orderId,
            'NS_order_money'=>$orderMoney, 'NS_item_name'=>$itemName, 'NS_cp_info'=>$cpInfo, 'NS_buyer_email'=>'','NS_trade_no'=>'0',
            'NS_order_status'=>$orderStatus, 'NS_create_time'=>$createTime, 'NS_pay_time'=>$payTime));
        return $insert;
    }

    public function selectAliPayOrderInfo($orderId, $type)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('sdk_alipay', sprintf("NS_order_id = '%s';", $orderId));
        foreach($result as $row)
        {
            if ($type == 'gameId')
            {
                return $row['NS_game_id'];
            }
            elseif ($type == 'uid')
            {
                return $row['NS_uid'];
            }
            elseif ($type == 'orderMoney')
            {
                return $row['NS_order_money'];
            }
            elseif ($type == 'cpInfo')
            {
                return $row['NS_cp_info'];
            }
            elseif ($type == 'orderStatus')
            {
                return $row['NS_order_status'];
            }
            elseif ($type == 'createTime')
            {
                return $row['NS_create_time'];
            }
            elseif ($type == 'itemName')
            {
                return $row['NS_item_name'];
            }
            elseif ($type == 'partner')
            {
                return $row['NS_partner'];
            }
            else
            {
                return $row['NS_pay_time'];
            }
        }
    }


    public function updateAliPayOrderStatus($orderId, $trade_no, $orderStatus, $buyerEmail, $payTime)
    {
        $db = $this->db;
        $upOrderStatus = $db->updateTable('sdk_alipay', array('NS_trade_no'=>$trade_no,'NS_order_status'=>$orderStatus,'NS_buyer_email'=>$buyerEmail, 'NS_pay_time'=>$payTime), "NS_order_id = '$orderId';");
        return $upOrderStatus;
    }


    //闪币支付
    public function insertCoinPay($uid, $gameId, $coin, $orderId, $orderStatus, $itemName, $payTime)
    {
        $table='sdk_light_coin';
        $db = $this->db;
        $insert = $db->insertTable($table, array('NS_id'=>'', 'NS_uid'=>$uid, 'NS_order_money'=>$coin, 'NS_game_id'=>$gameId,
            'NS_order_id'=>$orderId, 'NS_item_name'=>$itemName, 'NS_order_status'=>$orderStatus, 'NS_create_time'=>time(), 'NS_pay_time'=>$payTime));
        return $insert;
    }

    //更新支付状态
    public function updateCoinPayStatus($orderId,$orderStatus,$payTime)
    {
        $db = $this->db;
        $upOrderStatus = $db->updateTables('sdk_light_coin', array('NS_order_status'=>$orderStatus, 'NS_pay_time'=>$payTime), "NS_order_id = '$orderId';");
        return $upOrderStatus;
    }

}