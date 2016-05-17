<?php

require_once 'GameServerModel.php';

class ToGameServerModel extends GameServerModel
{
	public function __construct()
	{
		parent::__construct();
	}

    public function selectUserOnGameServer($ucid, $third_type)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('account_third', sprintf("third_id = '%s' and third_type = '%s';", $ucid, $third_type), 'third_id');
        foreach($result as $row)
        {
            return $row[third_id];
        }
    }

    public function updateUserCodeOnGameServer($code, $ucid)
    {
        $db = $this->db;
        $update = $db->updateTable('account_third', array('code'=>$code), 'third_id='.$ucid);
        return $update;
    }

	public function insertUserCodeOnGameServer($account_id, $code, $third_type, $ucid)
	{
		$db = $this->db;
		$insert = $db->insertTable('account_third', array('id'=>'', 'account_id'=>$account_id, 'code'=>$code, 'third_type'=>$third_type, 'third_id'=>$ucid));
		return $insert;
	}


    public function insertUserOnGameServer($name, $password)
    {
        $db = $this->db;
        $insert = $db->insertTable('account', array('id'=>'', 'name'=>$name, 'password'=>$password, 'privilege'=>'', 'login_status'=>'', 'world_name_crc'=>'', 'forbid_mask'=>'', 'guard'=>'', 'mibao'=>'', 'ip'=>'', 'time'=>''));
        return $insert;
    }


    public function selectUserIdOnGameServer($name, $password)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('account', sprintf("name = '%s' and password = '%s';", $name, $password), 'id');
        foreach($result as $row)
        {
            return $row[id];
        }
    }

}