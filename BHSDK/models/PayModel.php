<?php

require_once 'Model.php';

class PayModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}
    public function insertPay($uid,$third_id,$server,$yuanbao,$order_id,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_recharge', array('NS_id'=>'', 'NS_uid'=>$uid,
            'NS_third_id'=>$third_id, 'NS_server'=>$server,
            'NS_yuanbao'=>$yuanbao, 'NS_order_id'=>$order_id, 'NS_sdk_pay_time'=>time(),
            'NS_channel_type'=>$channel_type));
        return $insert;
    }

}