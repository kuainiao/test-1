<?php

require_once 'Model.php';

class SessionModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }


    public function selectGameKey($gameId)
    {
        $table='sdk_cp_info';
        $db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("NS_game_id = '%s';",$gameId));
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_game_key'];
            }
        }
    }


    public function selectUserSession($gameId,$uid)
    {
        $table='sdk_user_session';
        $now = time();
        $beforeOneHour = time()-3600;
        $db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("NS_uid = '%s' and NS_game_id = '%s' and NS_create_time > '%s' and NS_create_time <= '%s' order by NS_id desc limit 1;",$uid, $gameId, $beforeOneHour, $now));
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_session'];
            }
        }
    }
}