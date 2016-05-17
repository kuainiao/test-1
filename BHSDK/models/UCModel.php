<?php

require_once 'Model.php';

class UCModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function insertUcUser($area, $Uin, $account_id, $wz_username, $ucaccountId)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_uc_user', array('NS_id'=>'', 'NS_area'=>$area, 'NS_ucid'=>$Uin, 'NS_ucaccount_id'=>$ucaccountId, 'NS_account_id'=>$account_id, 'NS_wz_username'=>$wz_username,'NS_time'=>time()));
        return $insert;
    }

    public function selectUcUser($area, $Uin)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_uc_user', sprintf("NS_area = '%s' and NS_ucid = '%s';", $area, $Uin), 'NS_ucid');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_ucid'];
            }
        }
    }
	
	public function selectUcUserCheck($uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_uc_user', sprintf("NS_ucid = '%s' LIMIT 1;",$uid), 'NS_wz_username');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_wz_username'];
            }
        }
    }

    public function selectUcUserAccountId($area, $Uin)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_uc_user', sprintf("NS_area = '%s' and NS_ucid = '%s';", $area, $Uin), 'NS_account_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_account_id'];
            }
        }
    }

    public function selectUcUserWzUsername($area, $Uin)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_uc_user', sprintf("NS_area = '%s' and NS_ucid = '%s';", $area, $Uin), 'NS_wz_username');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_wz_username'];
            }
        }
    }

    public function selectPay($ucid, $orderId)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_uc_pay', sprintf("NS_ucid = '%s' and NS_order_id = '%s';", $ucid, $orderId), 'NS_order_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_order_id'];
            }
        }
    }


    public function insertPay($ucid, $ucaccountId, $orderId, $payWay, $amount, $orderStatus, $callbackInfo, $sdkPaytime)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_uc_pay', array('NS_id'=>'', 'NS_ucid'=>$ucid, 'NS_ucaccount_id'=>$ucaccountId, 'NS_order_id'=>$orderId, 'NS_pay_way'=>$payWay, 'NS_amount'=>$amount, 'NS_order_status'=>$orderStatus, 'NS_area'=>$callbackInfo, 'NS_sdk_pay_time'=>$sdkPaytime));
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
        $result = $db->getResultsByCondition($table,sprintf("NS_area = '%s' and NS_ucid = '%s' LIMIT 1;", $area, $uid), 'NS_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_id'];
            }
        }
    }
}