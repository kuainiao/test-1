<?php

require_once 'GameServerModel.php';

class PlatToGameServerModel extends GameServerModel
{
	public function __construct($area)
	{
		parent::__construct($area);
	}

    public function selectThirdUserOnGameServer($uid,$third_type,$table='account_third')
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("third_id = '%s' and third_type = '%s';", $uid,$third_type), 'third_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['third_id'];
            }
        }
    }

    public function updateThirdUserCodeOnGameServer($code,$uid,$table='account_third')
    {
        $db = $this->db;
        $update = $db->updateTable($table,array('code'=>$code), 'third_id='.$uid);
        return $update;
    }

	public function insertThirdUserCodeOnGameServer($account_id,$code,$third_type,$uid,$table='account_third')
	{
		$db = $this->db;
		$insert = $db->insertTableRows($table,array('account_id'=>$account_id, 'code'=>$code, 'third_type'=>$third_type, 'third_id'=>$uid));
		return $insert;
	}


    public function insertThirdUserOnGameServer($name,$password,$table='account')
    {
        $db = $this->db;
        $insert = $db->insertTable($table,array('name'=>$name, 'password'=>$password));
        return $insert;
    }
	
	public function selectThirdUserIdOnGameServer($name,$table="account")
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("name = '%s' ORDER BY id DESC LIMIT 1;", $name), 'id');
        foreach($result as $row)
        {
            return $row['id'];
        }
    }

}