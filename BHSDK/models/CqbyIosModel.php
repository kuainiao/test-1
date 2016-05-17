<?php

require_once 'Model.php';


class CqbyIosModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectIosPay($uniqueIdentifier)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_cqbyios_pay', sprintf("NS_order_id = '%s';", $uniqueIdentifier), 'NS_order_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_order_id'];
            }
        }
    }

    public function insertIosPay($accountId, $server, $orderId, $quantity, $gameMoney, $purchaseDate, $sdkPayTime)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_cqbyios_pay', array('NS_id'=>'', 'NS_account_id'=>$accountId, 'NS_server'=>$server, 'NS_order_id'=>$orderId, 'NS_quantity'=>$quantity, 'NS_game_money'=>$gameMoney, 'NS_purchase_date'=>$purchaseDate,'NS_sdk_pay_time'=>$sdkPayTime));
        return $insert;
    }

    public function selectIosWzUsername($area, $accountId)
    {
        $table = 'platform_chuanqibaye_user';
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_area = '%s' and NS_account_id = '%s';", $area, $accountId), 'NS_wz_username');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_wz_username'];
            }
        }
    }

    public function selectIosUid($area, $accountId)
    {
        $table = 'platform_chuanqibaye_user';
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_area = '%s' and NS_account_id = '%s';", $area, $accountId), 'NS_uid');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_uid'];
            }
        }
    }

}