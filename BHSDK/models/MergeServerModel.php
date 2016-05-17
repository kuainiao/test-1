<?php

require_once 'Model.php';
require_once 'GameServerModel.php';

class MergeServerModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

    public function selectUser($table,$mergeserver)
    {
        $db = $this->db;
		if($table=='nineone'){
        	$result = $db->getResultsByCondition('platform_'.$table.'_user',sprintf("NS_area = '%s' GROUP BY NS_uin;",$mergeserver), 'NS_uin as NS_uid');
		}elseif($table=='djoy'){
			$result = $db->getResultsByCondition('platform_'.$table.'_user',sprintf("NS_area = '%s' GROUP BY NS_mid;",$mergeserver), 'NS_mid as NS_uid');
		}elseif($table=='uc'){
			$result = $db->getResultsByCondition('platform_'.$table.'_user',sprintf("NS_area = '%s' GROUP BY NS_ucid;",$mergeserver), 'NS_ucid as NS_uid');
		}else{
			$result = $db->getResultsByCondition('platform_'.$table.'_user',sprintf("NS_area = '%s' GROUP BY NS_uid;",$mergeserver), 'NS_uid');
		}
        if(is_array($result))
        {
            return $result;
        }
    }
	
	public function selectServerCount($table,$server,$uid)
    {
        $db = $this->db;
		if($table=='nineone'){
        	$result = $db->getColByCondition('platform_'.$table.'_user',sprintf("NS_area = '%s' AND NS_uin=:'%s';",$server,$uid), 'COUNT(*) as cnt');
		}elseif($table=='djoy'){
			$result = $db->getColByCondition('platform_'.$table.'_user',sprintf("NS_area = '%s' AND NS_mid='%s';",$server,$uid), 'COUNT(*) as cnt');
		}elseif($table=='uc'){
			$result = $db->getColByCondition('platform_'.$table.'_user',sprintf("NS_area = '%s' AND NS_ucid='%s';",$server,$uid), 'COUNT(*) as cnt');
		}else{
			$result = $db->getColByCondition('platform_'.$table.'_user',sprintf("NS_area = '%s' AND NS_uid='%s';",$server,$uid), 'COUNT(*) as cnt');
		}
        if(is_array($result))
        {
            return (int)$result[0];
        }
    }

    public function insertThirdUser($table,$area,$uid, $account_id,$wz_username,$channel_type=1)
	{
		$db = $this->db;
		$time = time();
		$insert = '';
		if($table=='nineone'){
			$insert = $db->insertTable('platform_'.$table.'_user', array('NS_id'=>'', 'NS_area'=>$area, 'NS_uin'=>$uid, 'NS_account_id'=>$account_id, 'NS_wz_username'=>$wz_username,'NS_channel_type'=>$channel_type,'NS_time'=>$time));
		}elseif($table=='djoy'){
			$insert = $db->insertTable('platform_'.$table.'_user', array('NS_id'=>'', 'NS_area'=>$area, 'NS_mid'=>$uid, 'NS_account_id'=>$account_id, 'NS_wz_username'=>$wz_username,'NS_channel_type'=>$channel_type,'NS_time'=>$time));
		}elseif($table=='uc'){
			$insert = $db->insertTable('platform_'.$table.'_user', array('NS_id'=>'', 'NS_area'=>$area, 'NS_ucid'=>$uid, 'NS_account_id'=>$account_id, 'NS_wz_username'=>$wz_username,'NS_channel_type'=>$channel_type,'NS_time'=>$time));
		}else{
			$insert = $db->insertTable('platform_'.$table.'_user', array('NS_id'=>'', 'NS_area'=>$area, 'NS_uid'=>$uid, 'NS_account_id'=>$account_id, 'NS_wz_username'=>$wz_username,'NS_channel_type'=>$channel_type,'NS_time'=>$time));
		}
		return $insert;
	}

	public function selectThirdUserField($table,$area,$uid,$field)
    {
        $db = $this->db;
		if($table=='nineone'){
			$result = $db->getResultsByCondition('platform_'.$table.'_user',sprintf("NS_area = '%s' and NS_uin = '%s';", $area, $uid), $field);
		}elseif($table=='djoy'){
			$result = $db->getResultsByCondition('platform_'.$table.'_user',sprintf("NS_area = '%s' and NS_mid = '%s';", $area, $uid), $field);
		}elseif($table=='uc'){
			$result = $db->getResultsByCondition('platform_'.$table.'_user',sprintf("NS_area = '%s' and NS_ucid = '%s';", $area, $uid), $field);
		}else{
			$result = $db->getResultsByCondition('platform_'.$table.'_user',sprintf("NS_area = '%s' and NS_uid = '%s';", $area, $uid), $field);
		}
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_channel_type'];
            }
        }
    }
	
	public function insertMergeFail($uid,$platform)
	{
		$db = $this->db;
		$time = time();
		$insert = '';
		$insert = $db->insertTable('platform_merge_fail', array('NS_uid'=>$uid,'NS_platform'=>$platform));
		return $insert;
	}

}



class MergeToServerModel extends GameServerModel
{
	public function __construct($area)
	{
		parent::__construct($area);
	}
	
	public function selectAccountThrid($uid,$third_type,$table='account_third'){
		$db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("third_id = '%s' and third_type = '%s' LIMIT 1;", $uid,$third_type), 'account_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['account_id'];
            }
        }
	}
	
	public function selectAccount($account_id,$table="account")
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("id = '%s' LIMIT 1;", $account_id), 'name');
        foreach($result as $row)
        {
            return $row['name'];
        }
    }
	
	

}