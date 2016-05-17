<?php

require_once 'GameServerModel.php';

class DJoyToGameServerModel extends GameServerModel
{
	public function __construct($area)
	{
		parent::__construct($area);
	}

    public function selectDJoyUserOnGameServer($mid,$third_type=2)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('account_third', sprintf("third_id = '%s' and third_type = '%s';", $mid,$third_type), 'third_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['third_id'];
            }
        }
    }

    public function updateDJoyUserCodeOnGameServer($code, $mid,$third_type=2)
    {
        $db = $this->db;
        $update = $db->updateTable('account_third', array('code'=>$code), 'third_id='.$mid.' AND third_type='.$third_type);
        return $update;
    }

	public function insertDJoyUserCodeOnGameServer($account_id, $code, $third_type, $mid)
	{
		$db = $this->db;
		$insert = $db->insertTable('account_third', array('account_id'=>$account_id, 'code'=>$code, 'third_type'=>$third_type, 'third_id'=>$mid));
		return $insert;
	}


    public function insertDJoyUserOnGameServer($name, $password)
    {
        $db = $this->db;
        $insert = $db->insertTable('account', array('name'=>$name, 'password'=>$password));
        return $insert;
    }


    public function selectDJoyUserIdOnGameServer($name)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('account', sprintf("name = '%s' ORDER BY id DESC LIMIT 1;", $name), 'id');
        foreach($result as $row)
        {
            return $row['id'];
        }
    }

}