<?php

require_once 'Model.php';

class NineOneModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertNineOneUser($area, $Uin, $account_id, $wz_username,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_nineone_user', array('NS_id'=>'', 'NS_area'=>$area, 'NS_uin'=>$Uin, 'NS_account_id'=>$account_id, 'NS_wz_username'=>$wz_username,'NS_channel_type'=>$channel_type,'NS_time'=>time()));
        return $insert;
    }

    public function selectNineOneUser($area, $Uin)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_nineone_user', sprintf("NS_area = '%s' and NS_uin = '%s';", $area, $Uin), 'NS_uin');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_uin'];
            }
        }
    }

    public function selectNineOneUserCheck($Uin)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_nineone_user', sprintf("NS_uin = '%s' LIMIT 1;",$Uin), 'NS_wz_username');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_wz_username'];
            }
        }
    }

    public function selectNineOneUserAccountId($area, $Uin)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_nineone_user', sprintf("NS_area = '%s' and NS_uin = '%s';", $area, $Uin), 'NS_account_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_account_id'];
            }
        }
    }

    public function selectNineOneUserWzUsername($area, $Uin)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_nineone_user', sprintf("NS_area = '%s' and NS_uin = '%s';", $area, $Uin), 'NS_wz_username');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_wz_username'];
            }
        }
    }

    public function selectPay($Uin, $CooOrderSerial)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition('platform_nineone_pay', sprintf("NS_uin = '%s' and NS_coo_order_serial = '%s';", $Uin, $CooOrderSerial), 'NS_coo_order_serial');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_coo_order_serial'];
            }
        }
    }

    public function insertPay($uin, $consume_stream_id, $coo_order_serial, $goods_id, $goods_info, $goods_count, $original_money, $order_money, $note, $pay_status, $create_time, $sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_nineone_pay', array('NS_id'=>'', 'NS_uin'=>$uin,
            'NS_consume_stream_id'=>$consume_stream_id, 'NS_coo_order_serial'=>$coo_order_serial,
            'NS_goods_id'=>$goods_id, 'NS_goods_info'=>$goods_info, 'NS_goods_count'=>$goods_count,
            'NS_original_money'=>$original_money, 'NS_order_money'=>$order_money, 'NS_note'=>$note,
            'NS_pay_status'=>$pay_status, 'NS_create_time'=>$create_time, 'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type));
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
        $result = $db->getResultsByCondition($table,sprintf("NS_area = '%s' and NS_uin = '%s' LIMIT 1;", $area, $uid), 'NS_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_id'];
            }
        }
    }

}