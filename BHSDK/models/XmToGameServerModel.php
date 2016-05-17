<?php

require_once 'GameServerModel.php';

class XmToGameServerModel extends GameServerModel
{
	public function __construct($area)
	{
		parent::__construct($area);
	}

    public function selectXmUserOnGameServer($uid,$third_type=3)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('account_third', sprintf("third_id = '%s' and third_type = '%s';", $uid,$third_type), 'third_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['third_id'];
            }
        }
    }

    public function updateXmUserCodeOnGameServer($code, $uid)
    {
        $db = $this->db;
        $update = $db->updateTable('account_third', array('code'=>$code), 'third_id='.$uid);
        return $update;
    }

	public function insertXmUserCodeOnGameServer($account_id, $code, $third_type, $uid)
	{
		$db = $this->db;
		$insert = $db->insertTable('account_third', array('account_id'=>$account_id, 'code'=>$code, 'third_type'=>$third_type, 'third_id'=>$uid));
		return $insert;
	}


    public function insertXmUserOnGameServer($name, $password)
    {
        $db = $this->db;
        $insert = $db->insertTable('account', array('name'=>$name, 'password'=>$password));
        return $insert;
    }


    public function selectXmUserIdOnGameServer($name)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('account', sprintf("name = '%s' ORDER BY id DESC LIMIT 1;", $name), 'id');
        foreach($result as $row)
        {
            return $row['id'];
        }
    }

}