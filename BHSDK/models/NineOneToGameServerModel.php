<?php

require_once 'GameServerModel.php';

class NineOneToGameServerModel extends GameServerModel
{
	public function __construct($area)
	{
		parent::__construct($area);
	}

    public function selectNineOneUserOnGameServer($Uin,$third_type=0)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('account_third', sprintf("third_id = '%s' and third_type = '%s';", $Uin,$third_type), 'third_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['third_id'];
            }
        }
    }

    public function updateNineOneUserCodeOnGameServer($code, $Uin)
    {
        $db = $this->db;
        $update = $db->updateTable('account_third', array('code'=>$code), 'third_id='.$Uin);
        return $update;
    }

	public function insertNineOneUserCodeOnGameServer($account_id, $code, $third_type, $Uin)
	{
		$db = $this->db;
		$insert = $db->insertTable('account_third', array('account_id'=>$account_id, 'code'=>$code, 'third_type'=>$third_type, 'third_id'=>$Uin));
		return $insert;
	}


    public function insertNineOneUserOnGameServer($name, $password)
    {
        $db = $this->db;
        $insert = $db->insertTable('account', array('name'=>$name, 'password'=>$password));
        return $insert;
    }


    public function selectNineOneUserIdOnGameServer($name)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('account', sprintf("name = '%s' ORDER BY id DESC LIMIT 1;", $name), 'id');
        foreach($result as $row)
        {
            return $row['id'];
        }
    }

}