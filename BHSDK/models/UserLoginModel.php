<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yang
 * Date: 13-9-22
 * Time: 下午10:23
 * To change this template use File | Settings | File Templates.
 */

require_once 'Model.php';

class UserLoginModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertUserLoginTime($third_type, $area, $account_id, $third_id, $login_time,$server='',$channel=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_user_login_log', array('NS_third_type'=>$third_type, 'NS_area'=>$area, 'NS_account_id'=>$account_id, 'NS_third_id'=>$third_id, 'NS_login_time'=>$login_time,'NS_server'=>$server,'NS_channel_type'=>$channel));
        return $insert;
    }
	
	//记录用户充值日志
	public function insertUserpaylog($uid,$third_type,$order_id,$order_time,$area,$server,$money,$account_id,$channel_type=1)
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