<?php

require_once 'Model.php';

class WZTJModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function insertThreeJoyUser($area, $uid, $account_id, $wz_username,$channel_type=1)
	{
		$db = $this->db;
		$insert = $db->insertTable('platform_wzthreejoy_user', array('NS_id'=>'', 'NS_area'=>$area, 'NS_uid'=>$uid, 'NS_account_id'=>$account_id, 'NS_wz_username'=>$wz_username,'NS_channel_type'=>$channel_type,'NS_create_time'=>time()));
		return $insert;
	}

    public function selectThreeJoyUser($area, $uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_wzthreejoy_user', sprintf("NS_area = '%s' and NS_uid = '%s';", $area, $uid), 'NS_uid');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_uid'];
            }
        }
    }
	
	public function selectThreeJoyUserCheck($uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_wzthreejoy_user', sprintf("NS_uid = '%s' LIMIT 1;",$uid), 'NS_wz_username');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_wz_username'];
            }
        }
    }

    public function selectThreeJoyUserAccountId($area, $uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_wzthreejoy_user', sprintf("NS_area = '%s' and NS_uid = '%s';", $area, $uid), 'NS_account_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_account_id'];
            }
        }
    }

    public function selectThreeJoyUserWzUsername($area, $uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_wzthreejoy_user', sprintf("NS_area = '%s' and NS_uid = '%s';", $area, $uid), 'NS_wz_username');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_wz_username'];
            }
        }
    }

    public function selectPay($uid, $orderId)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_wzthreejoy_pay', sprintf("NS_uid = '%s' and NS_order_id = '%s';", $uid, $orderId), 'NS_order_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_order_id'];
            }
        }
    }

    public function insertPay($uid, $cpInfo, $itemName, $orderMoney, $orderId, $orderCreateTime, $payTime, $sdkPayTime)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_wzthreejoy_pay', array('NS_id'=>'',
            'NS_uid'=>$uid,
            'NS_cp_info'=>$cpInfo,
            'NS_item_name'=>$itemName,
            'NS_order_money'=>$orderMoney,
            'NS_order_id'=>$orderId,
            'NS_order_create_time'=>$orderCreateTime,
            'NS_pay_time'=>$payTime,
            'NS_sdk_pay_time'=>$sdkPayTime));
        return $insert;
    }

	//查看平台用户是否在该游戏服存在
	public function selectServer($table,$uid,$server){
		$db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("NS_uid = '%s' and  NS_server = '%s' LIMIT 1 ;",$uid,$server), 'NS_uid');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_uid'];
            }
        }
	}
	
	//插入平台用户到该游戏服
	public function insertServer($table,$uid,$area_id,$server,$area,$channel_type=1,$time='')
	{
		if($time==''){
			$time = time();
		}
		$format_time = date('Y-m-d',$time);
		$db = $this->db;
		$insert = $db->insertTable($table, array('NS_uid'=>$uid,'NS_area_id'=>$area_id,'NS_server'=>$server,'NS_area'=>$area,'NS_channel_type'=>$channel_type,'NS_time'=>$time,'NS_format_time'=>$format_time));
		return $insert;
	}
	
	//查看用户在该大区中的id
	public function selectAreaId($table,$area,$uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("NS_area = '%s' and NS_uid = '%s' LIMIT 1;", $area, $uid), 'NS_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_id'];
            }
        }
    }

}