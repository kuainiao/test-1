<?php

require_once 'PFModel.php';

class PFUserModel extends PFModel
{
	public function __construct()
	{
		parent::__construct();
	}

    public function selectUidInMoney($uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('pf_user_money', sprintf("uid = '%s';", $uid));
        foreach($result as $row)
        {
            return $row['uid'];
        }
    }

    public function updateUserMoney($lightCoin, $money, $uid)
    {
        $db = $this->db;
        $upOrderStatus = $db->updateMoney($lightCoin, $money, $uid);
        return $upOrderStatus;
    }

    public function insertUserMoney($lightCoin, $money, $uid)
    {
        $db = $this->db;
        $insert = $db->insertTable('pf_user_money', array('id'=>'', 'uid'=>$uid, 'light_coin'=>$lightCoin,'money'=>$money,'number'=>0, 'last_light_time'=>0, 'last_money_time'=>time()));
        return $insert;
    }


    //神州付支付
    public function insertSZPayOrder($uid,$orderId,$orderMoney,$cardType,$cardMoney,$privateField,$orderStatus,$createTime,$payTime)
    {
        $table='pf_sz_pay';
        $db = $this->db;
        $insert = $db->insertTable($table, array('id'=>'', 'uid'=>$uid, 'order_id'=>$orderId, 'order_money'=>$orderMoney,
            'card_type'=> $cardType, 'card_money'=>$cardMoney, 'private_field'=>$privateField, 'order_status'=>$orderStatus,
            'create_time'=>$createTime, 'pay_time'=>$payTime));
        return $insert;
    }

    public function selectSZPayOrderInfo($orderId, $type)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('pf_sz_pay', sprintf("order_id = '%s';", $orderId));
        foreach($result as $row)
        {
            if ($type == 'privateField')
            {
                return $row['private_field'];
            }
            else if($type == 'uid')
            {
                return $row['uid'];
            }
            else
            {
                return $row['order_status'];
            }
        }
    }

    public function updateSZPayOrderStatus($orderId, $orderStatus, $payTime)
    {
        $db = $this->db;
        $upOrderStatus = $db->updateTable('pf_sz_pay', array('order_status'=>$orderStatus,'pay_time'=>$payTime), "order_id = '$orderId';");
        return $upOrderStatus;
    }




    //支付宝支付
    public function insertAliPayOrder($uid,$orderId,$orderMoney)
    {
        $db = $this->db;
        $insert = $db->insertTable('pf_ali_pay', array('id'=>'', 'uid'=>$uid, 'order_id'=>$orderId, 'order_money'=>$orderMoney, 'order_status'=>0,
            'create_time'=>time(), 'pay_time'=>0));
        return $insert;
    }

    public function selectAliPayOrderInfo($orderId, $type)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('pf_ali_pay', sprintf("order_id = '%s';", $orderId));
        foreach($result as $row)
        {
            if ($type == 'orderStatus')
            {
                return $row['order_status'];
            }
            else if ($type == 'uid')
            {
                return $row['uid'];
            }
            else if ($type == 'money')
            {
                return $row['order_money'];
            }
            else
            {
                return $row['NS_pay_time'];
            }
        }
    }

    public function updateAliPayOrderStatus($orderId)
    {
        $db = $this->db;
        $upOrderStatus = $db->updateTable('pf_ali_pay', array('order_status'=>1, 'pay_time'=>time()), "order_id = '$orderId';");
        return $upOrderStatus;
    }


	public function insertSource($uid, $gameId, $systemType, $partner, $uuid)
	{
        $table='pf_ucenter_regsource';
		$db = $this->db;
		$insert = $db->insertTable($table, array('id'=>'', 'uid'=>$uid, 'source'=>$gameId, 'system_type'=>$systemType, 'partner'=> $partner, 'uuid' => $uuid, 'regtime'=>time()));
		return $insert;
	}

    public function selectPFUserInfo($username,$action)
    {
        $table='pf_ucenter_members';
        $db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("username = '%s';",$username));
        if(is_array($result))
        {
            foreach($result as $row)
            {
                if($action == 'username')
                {
                    return $row['username'];
                }
                else if($action == 'password')
                {
                    return $row['password'];
                }
                else if($action == 'salt')
                {
                    return $row['salt'];
                }
                else
                {
                    return $row['uid'];
                }
            }
        }
    }

    public function selectLightCoin($uid)
    {
        $table='pf_user_money';
        $db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("uid = '%s';",$uid));
        if(!empty($result))
        {
            foreach($result as $row)
            {
                return $row['light_coin'];
            }
        }
        else
        {
            return 0;
        }
    }

    //扣除闪币
    public function updateUserLightCoin($uid,$coin)
    {
        $db = $this->db;
        $upOrderStatus = $db->updateTables('pf_user_money', array('light_coin'=>"light_coin-$coin"), "uid = '$uid';");
        return $upOrderStatus;
    }


    public function insertCoinPayLog($uid, $gameId, $coin, $orderId, $orderStatus)
    {
        $table='pf_user_money_pay_log';
        $db = $this->db;
        $insert = $db->insertTable($table, array('id'=>'', 'uid'=>$uid, 'light_coin'=>$coin, 'game_id'=>$gameId, 'order_id'=>$orderId, 'order_status'=>$orderStatus, 'pay_time'=>time()));
        return $insert;
    }

    //更新支付状态
    public function updateCoinPayStatus($orderId,$orderStatus)
    {
        $db = $this->db;
        $upOrderStatus = $db->updateTables('pf_user_money_pay_log', array('order_status'=>$orderStatus), "order_id = '$orderId';");
        return $upOrderStatus;
    }
}