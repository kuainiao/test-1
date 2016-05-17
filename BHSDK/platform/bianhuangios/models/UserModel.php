<?php

require_once 'Model.php';

class UserModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function insertUserInfo($gameId, $username, $userpass, $systemType, $partner)
	{
        $table='sdk_user';
		$db = $this->db;
		$insert = $db->insertTable($table, array('NS_id'=>'', 'NS_username'=>$username, 'NS_userpass'=>$userpass, 'NS_game_id'=> $gameId, 'NS_system_type'=>$systemType, 'NS_partner'=>$partner, 'NS_create_time'=>time()));
		return $insert;
	}

    public function selectUserInfo($gameId,$username,$action)
    {
        $table='sdk_user';
        $db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("NS_game_id = '%s' and NS_username = '%s';",$gameId, $username));
        if(is_array($result))
        {
            foreach($result as $row)
            {
                if($action == 'username')
                {
                    return $row['NS_username'];
                }
                elseif($action == 'userpass')
                {
                    return $row['NS_userpass'];
                }
                else
                {
                    return $row['NS_id'];
                }
            }
        }
    }

    public function updateUsernameUserpass($gameId, $oldUsername, $oldUserpass, $username, $userpass,$systemType)
    {
        $table='sdk_user';
        $db = $this->db;
        $update = $db->updateTable($table, array('NS_username'=>$username, 'NS_userpass'=>$userpass), 'NS_game_id='.$gameId.' AND NS_system_type='.$systemType. " AND NS_username='".$oldUsername."' AND NS_userpass='".$oldUserpass."'");
        return $update;
    }

    public function updateUserpass($gameId, $username,$userpass)
    {
        $table='sdk_user';
        $db = $this->db;
        $update = $db->updateTable($table, array('NS_userpass'=>$userpass), 'NS_game_id='.$gameId. " AND NS_username='".$username."'");
        return $update;
    }

    public function insertUserLoginTime($gameId, $uid, $loginTime)
    {
        $table = 'sdk_user_login_log';
        $db = $this->db;
        $insert = $db->insertTable($table, array('NS_game_id'=>$gameId, 'NS_uid'=>$uid, 'NS_login_time'=>$loginTime));
        return $insert;
    }


    public function insertUserLoginSession($uid, $gameId, $systemType, $session)
    {
        $table = 'sdk_user_session';
        $db = $this->db;
        $insert = $db->insertTable($table, array('NS_uid'=>$uid, 'NS_game_id'=>$gameId, 'NS_system_type'=>$systemType, 'NS_session'=>$session, 'NS_create_time'=>time()));
        return $insert;
    }

	
	//记录用户充值日志
	public function insertUserPayLog($uid,$third_type,$order_id,$order_time,$area,$server,$money,$account_id,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_user_pay_log', 
        		array(
        			  'NS_uid'=>$uid,'NS_third_type'=>$third_type,'NS_order_id'=>$order_id,
        		      'NS_order_time'=>$order_time,'NS_area'=>$area,'NS_server'=>$server,
        		      'NS_money'=>$money,'NS_account_id'=>$account_id,'NS_channel_type'=>$channel_type
				)
	    );
        return $insert;
    }
}