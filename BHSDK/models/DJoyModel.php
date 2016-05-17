<?php

require_once 'Model.php';

class DJoyModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertDJoyUser($area, $mid, $account_id, $wz_username,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_djoy_user', array('NS_id'=>'', 'NS_area'=>$area, 'NS_mid'=>$mid, 'NS_account_id'=>$account_id, 'NS_wz_username'=>$wz_username,'NS_channel_type'=>$channel_type,'NS_time'=>time()));
        return $insert;
    }

    public function selectDJoyUser($area, $mid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_djoy_user', sprintf("NS_area = '%s' and NS_mid = '%s';", $area, $mid), 'NS_mid');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_mid'];
            }
        }
    }
	
	public function selectDJoyUserCheck($mid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_djoy_user', sprintf("NS_mid = '%s' LIMIT 1;",$mid), 'NS_wz_username');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_wz_username'];
            }
        }
    }

    public function selectDJoyUserAccountId($area, $mid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_djoy_user', sprintf("NS_area = '%s' and NS_mid = '%s';", $area, $mid), 'NS_account_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row[NS_account_id];
            }
        }
    }

    public function selectDJoyUserWzUsername($area, $mid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_djoy_user', sprintf("NS_area = '%s' and NS_mid = '%s';", $area, $mid), 'NS_wz_username');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row[NS_wz_username];
            }
        }
    }

    public function selectPay($mid, $CooOrderSerial)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_djoy_pay', sprintf("NS_mid = '%s' and NS_order = '%s';", $mid, $CooOrderSerial), 'NS_order');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row[NS_order];
            }
        }
    }

    public function insertPay($money, $order, $mid, $time, $ext, $sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_djoy_pay', array('NS_id'=>'', 'NS_money'=>$money, 'NS_order'=>$order, 'NS_mid'=>$mid, 'NS_time'=>$time, 'NS_ext'=>$ext, 'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type));
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
        $result = $db->getResultsByCondition($table,sprintf("NS_area = '%s' and NS_mid = '%s' LIMIT 1;", $area, $uid), 'NS_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_id'];
            }
        }
    }

}