<?php

require_once 'Model.php';

class PlatModel extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function insertThirdUser($table,$area,$uid, $account_id,$wz_username,$channel_type=1)
	{
		$db = $this->db;
		$insert = $db->insertTable($table, array('NS_id'=>'', 'NS_area'=>$area, 'NS_uid'=>$uid, 'NS_account_id'=>$account_id, 'NS_wz_username'=>$wz_username,'NS_channel_type'=>$channel_type,'NS_time'=>time()));
		return $insert;
	}

    public function selectThirdUser($table,$area,$uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("NS_area = '%s' and NS_uid = '%s';", $area, $uid), 'NS_uid');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_uid'];
            }
        }
    }

    public function selectVivoThirdUser($table,$area,$uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("NS_area = '%s' and NS_uid = '%s';", $area, $uid), 'NS_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_id'];
            }
        }
    }
	
	public function selectThirdUserCheck($table,$uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_uid = '%s' LIMIT 1;",$uid), 'NS_wz_username');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_wz_username'];
            }
        }
    }
	 public function selectThirdUserAccountId91($table,$area,$uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("NS_area = '%s' and NS_uin = '%s';", $area,$uid), 'NS_account_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_account_id'];
            }
        }
    }

    public function selectThirdUserAccountId($table,$area,$uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("NS_area = '%s' and NS_uid = '%s';", $area,$uid), 'NS_account_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_account_id'];
            }
        }
    }
	
	public function selectThirdUserAccountId_array($table,$area,$uid)
    {	$arr = array(); 
        $db = $this->db;
        $result = $db->getResultsByCondition($table,sprintf("NS_area = '%s' and NS_uid = '%s';", $area,$uid), 'NS_account_id');
		$str_test=json_encode($result);
		file_put_contents('/tmp/testxiao.txt',$str_test);
        if(is_array($result))
        {
            foreach($result as $row)
            {
                $arr[]=$row['NS_account_id'];
            }
        }
		return $arr;
    }

	public function selectThirdUserWzUsername91($table,$area, $uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_area = '%s' and NS_uin = '%s';", $area, $uid), 'NS_wz_username');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_wz_username'];
            }
        }
    }
    public function selectThirdUserWzUsername($table,$area, $uid)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_area = '%s' and NS_uid = '%s';", $area, $uid), 'NS_wz_username');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_wz_username'];
            }
        }
    }

    public function selectPay($uid,$orderid,$table)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_uid = '%s' and NS_orderid = '%s';", $uid, $orderid), 'NS_orderid');

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_orderid'];
            }
        }
    }
	
	public function selectNineOnePay($uid,$orderid,$table)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_uid = '%s' and NS_orderid = '%s';", $uid, $orderid), 'NS_orderid');

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_orderid'];
            }
        }
    }

    public function selectVAlreadyPay($orderid,$table,$filed)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_orderid = '%s' and NS_sdk_pay_time != 0;", $orderid));

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row["$filed"];
            }
        }
    }

    public function selectVPay($orderid,$payTime,$table,$filed)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_orderid = '%s' and NS_sdk_pay_time = %s;", $orderid, $payTime), $filed);

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row["$filed"];
            }
        }
    }
	
	public function selectAnzhiPay($uid,$orderid,$table)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_uid = '%s' and NS_orderid = '%s';", $uid, $orderid), 'NS_orderid');

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_orderid'];
            }
        }
    }
	
	public function selectWdjPay($uid,$orderid,$table)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_uid = '%s' and NS_orderid = '%s';", $uid, $orderid), 'NS_orderid');

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_orderid'];
            }
        }
    }
	
	public function selectQhPay($uid,$orderid,$table)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_user_id = '%s' and NS_order_id = '%s';", $uid, $orderid), 'NS_order_id');

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_order_id'];
            }
        }
    }
	
	public function selectPpPay($uid,$orderid,$table)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_uid = '%s' and NS_order_id = '%s';", $uid, $orderid), 'NS_order_id');

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_order_id'];
            }
        }
    }

    public function insertPay($amount,$cardtype,$uid,$orderid,$area_info,$result,$timetamp,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_duoku_pay', array('NS_id'=>'', 'NS_amount'=>$amount, 'NS_cardtype'=>$cardtype, 'NS_uid'=>$uid, 'NS_orderid'=>$orderid, 'NS_area'=>$area_info, 'NS_result'=>$result, 'NS_timetamp'=>$timetamp,'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type));
        return $insert;
    }

    public function insertYdPay($money,$uid,$orderid,$server,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_yunding_pay',
            array('NS_id'=>'',
                'NS_money'=>$money,
                'NS_uid'=>$uid,
                'NS_orderid'=>$orderid,
                'NS_server'=>$server,
                'NS_sdk_pay_time'=>$sdkPaytime,
                'NS_channel_type'=>$channel_type));
        return $insert;
    }
	
	public function insertNineOnePay($amount,$cardtype,$uid,$orderid,$area_info,$result,$timetamp,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_nineone_pay', array('NS_id'=>'', 'NS_amount'=>$amount, 'NS_cardtype'=>$cardtype, 'NS_uid'=>$uid, 'NS_orderid'=>$orderid, 'NS_area'=>$area_info, 'NS_result'=>$result, 'NS_timetamp'=>$timetamp,'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type));
        return $insert;
    }
	
	public function insertYdyueyuPay($money,$uid,$orderid,$serv_id,$sdkPaytime,$channel_type=2)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_yundingyueyu_pay',
            array('NS_id'=>'',
                'NS_money'=>$money,
                'NS_uid'=>$uid,
                'NS_orderid'=>$orderid,
                'NS_server'=>$serv_id,
                'NS_sdk_pay_time'=>$sdkPaytime,
                'NS_channel_type'=>$channel_type));
        return $insert;
    }
	
		public function insertchuanqibayePay($money,$uid,$orderid,$server,$sdkPaytime,$channel_type=2)
    {
       
			  /*$test_arr =array('NS_id'=>'',
                'NS_money'=>$money,
                'NS_uid'=>$uid,
                'NS_orderid'=>$orderid,
                'NS_server'=>$server,
                'NS_sdk_pay_time'=>$sdkPaytime,
                'NS_channel_type'=>$channel_type);
				$str_test=json_encode($test_arr);
				file_put_contents('/tmp/testc',$str_test);*/
	    $db = $this->db;
        $insert = $db->insertTable('platform_chuanqibaye_pay',
            array('NS_id'=>'',
                'NS_money'=>$money,
                'NS_uid'=>$uid,
                'NS_orderid'=>$orderid,
                'NS_server'=>$server,
                'NS_sdk_pay_time'=>$sdkPaytime,
                'NS_channel_type'=>$channel_type));
        return $insert;
    }

    public function insertYlPay($money,$uid,$orderid,$server,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_youlong_pay',
            array('NS_id'=>'',
                'NS_money'=>$money,
                'NS_uid'=>$uid,
                'NS_orderid'=>$orderid,
                'NS_server'=>$server,
                'NS_sdk_pay_time'=>$sdkPaytime,
                'NS_channel_type'=>$channel_type));
        return $insert;
    }

    public function insertYYBPay($money,$uid,$orderid,$server,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_yyb_pay',
            array('NS_id'=>'',
                'NS_money'=>$money,
                'NS_uid'=>$uid,
                'NS_orderid'=>$orderid,
                'NS_server'=>$server,
                'NS_sdk_pay_time'=>$sdkPaytime,
                'NS_channel_type'=>$channel_type));
        return $insert;
    }

    public function insertHwPay($money,$uid,$orderid,$server,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_huawei_pay',
            array('NS_id'=>'',
                'NS_money'=>$money,
                'NS_uid'=>$uid,
                'NS_orderid'=>$orderid,
                'NS_server'=>$server,
                'NS_sdk_pay_time'=>$sdkPaytime,
                'NS_channel_type'=>$channel_type));
        return $insert;
    }

    public function insertVPay($money,$uid,$orderid,$server,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_vivo_pay',
            array('NS_id'=>'',
                'NS_money'=>$money,
                'NS_uid'=>$uid,
                'NS_orderid'=>$orderid,
                'NS_server'=>$server,
                'NS_sdk_pay_time'=>$sdkPaytime,
                'NS_channel_type'=>$channel_type));
        return $insert;
    }

    public function updateVPay($orderid,$table)
    {
        $db = $this->db;
        $update = $db->updateTable($table,array('NS_sdk_pay_time'=>time()), "NS_orderid='$orderid'");
        return $update;
    }
	
	public function insertAnzhiPay($uid,$orderId,$orderAmount,$orderTime,$orderAccount,$code,$msg,$payAmount,$cpInfo,$notifyTime,$memo,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_anzhi_pay', 
        		array('NS_uid'=>$uid, 'NS_orderid'=>$orderId, 
		        	  'NS_orderamount'=>$orderAmount,'NS_ordertime'=>$orderTime,
		        	  'NS_orderaccount'=>$orderAccount, 'NS_code'=>$code, 
		        	  'NS_msg'=>$msg, 'NS_payamount'=>$payAmount, 
		        	  'NS_cpinfo'=>$cpInfo,'NS_notifytime'=>$notifyTime,
		        	  'NS_memo'=>$memo,'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type)
			    );
        return $insert;
    }

public function insertTestPays($channel_type)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_test', 
        		array('notify_data'=>$channel_type)
			    );
        return $insert;
    }
	
	
	public function insertWdjPay($buyerId,$orderId,$money,$timeStamp,$chargeType,$code,$out_trade_no,$cardNo,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_wdj_pay', 
        		array('NS_timestamp'=>$timeStamp,'NS_uid'=>$buyerId, 
        			  'NS_orderid'=>$orderId, 'NS_money'=>$money, 
        			  'NS_chargetype'=>$chargeType,'NS_code'=>$code, 
		        	  'NS_server'=>$out_trade_no, 'NS_cardno'=>$cardNo, 
		        	  'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type)
			    );
        return $insert;
    }
	
	public function insertQhPay($array,$app_ext,$sign_return,$sign,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
		$money = $array['amount']/100;
        $insert = $db->insertTable('platform_qh_pay', 
        		array('NS_product_id'=>$array['product_id'],'NS_amount'=>$money,'NS_app_uid'=>$array['app_uid'],'NS_app_ext'=>$app_ext,'NS_user_id'=>$array['user_id'],'NS_order_id'=>$array['order_id'],'NS_gateway_flag'=>$array['gateway_flag'],'NS_sign_type'=>$array['sign_type'],'NS_app_order_id'=>$array['app_order_id'],'NS_sign_return'=>$sign_return,'NS_sign'=>$sign,'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type)
			    );
        return $insert;
    }

    public function insertQhWzsPay($array,$app_ext,$sign_return,$sign,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $money = $array['amount']/100;
        $insert = $db->insertTable('platform_qhwzs_pay',
            array('NS_product_id'=>$array['product_id'],'NS_amount'=>$money,'NS_app_uid'=>$array['app_uid'],'NS_app_ext'=>$app_ext,'NS_user_id'=>$array['user_id'],'NS_order_id'=>$array['order_id'],'NS_gateway_flag'=>$array['gateway_flag'],'NS_sign_type'=>$array['sign_type'],'NS_app_order_id'=>$array['app_order_id'],'NS_sign_return'=>$sign_return,'NS_sign'=>$sign,'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type)
        );
        return $insert;
    }
	
	public function insertPpPay($order_id,$area_info,$uid,$account,$amount,$money,$status,$app_id,$roleid,$uuid,$zone,$sdkPaytime,$channel_type=2)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_pp_pay', 
        		array('NS_order_id'=>$order_id,'NS_uid'=>$uid,'NS_roleid'=>$roleid,'NS_uuid'=>$uuid, 
        			  'NS_server'=>$area_info, 'NS_account'=>$account, 'NS_amount'=>$amount, 'NS_money'=>$money,
					  'NS_status'=>$status, 'NS_appid'=>$app_id, 'NS_zone'=>$zone,
		        	  'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type)
			    );
        return $insert;
    }

	/**
	 * 昆仑
	 */
	public function selectKLPay($uid,$orderid,$table)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_uid = '%s' and NS_oid = '%s';", $uid, $orderid), 'NS_oid');

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_oid'];
            }
        }
    }
	
	public function insertKLPay($array,$server,$sdkPaytime,$channel_type=3)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_kunlun_pay', 
        		array('NS_uid'=>$array['uid'],'NS_oid'=>$array['oid'],'NS_money'=>$array['money'],'NS_amount'=>$array['amount'],'NS_coins'=>$array['coins'],'NS_dtime'=>$array['dtime'],'NS_server'=>$array['server'],'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type)
			    );
        return $insert;
    }
	
	/*
	 * 同步推
	 */ 
	public function selectTbPay($uid,$order_id,$table)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_uid = '%s' and NS_order_id = '%s';", $uid, $order_id), 'NS_order_id');

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_order_id'];
            }
        }
    }
	
	public function insertTbPay($amount,$money,$source,$uid,$order_id,$area_info,$partner,$debug,$sdkPaytime,$channel_type=2)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_tb_pay', 
        		array('NS_amount'=>$amount,'NS_money'=>$money,'NS_source'=>$source,'NS_uid'=>$uid,'NS_order_id'=>$order_id,'NS_server'=>$area_info,'NS_partner'=>$partner,'NS_debug'=>$debug,'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type)
			    );
        return $insert;
    }

	/*
	 * 快用
	 */ 
	public function selectKyPay($uid,$order_id,$table)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_uid = '%s' and NS_order_id = '%s';", $uid, $order_id), 'NS_order_id');

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_order_id'];
            }
        }
    }
	
	public function insertKyPay($request,$fee,$payresult,$uid,$server,$sdkPaytime,$channel_type=2)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_ky_pay', 
        		array('NS_money'=>$fee,'NS_result'=>$payresult,'NS_guid'=>$request['uid'],'NS_uid'=>$uid,'NS_order_id'=>$request['orderid'],'NS_subject'=>$request['subject'],'NS_server'=>$server,'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type)
			    );
        return $insert;
    }

	/*
	 * 瑞高摩奇
	 */ 
	public function selectRgPay($uid,$order_id,$table)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_uid = '%s' and NS_order_id = '%s';", $uid, $order_id), 'NS_order_id');

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_order_id'];
            }
        }
    }
	
	public function insertRgPay($request,$uid,$uin,$server,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_rg_pay', 
        		array('NS_money'=>$request['paymentFee'],'NS_result'=>$request['payresult'],
        		'NS_uid'=>$uid,'NS_order_id'=>$request['order_id'],'NS_uin'=>$uin,
        		'NS_order_money'=>$request['money'],'NS_trancode'=>$request['trancode'],
        		'NS_server'=>$server,'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type)
	    );
        return $insert;
    }
	
	/*
	 * OPPO
	 */ 
	public function selectOpPay($uid,$order_id,$table)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_uid = '%s' and NS_order_id = '%s';", $uid, $order_id), 'NS_order_id');

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_order_id'];
            }
        }
    }
	
	public function insertOpPay($request,$uid,$server,$sdkPaytime,$area,$gameMoney,$channel_type=1)
    {
        $db = $this->db;
		$moeny = $request['price']/100;
        $insert = $db->insertTable('platform_oppo_pay', 
        		array('NS_money'=>$moeny,'NS_count'=>$request['count'],
        		'NS_uid'=>$uid,'NS_order_id'=>$request['order_id'],
        		'NS_notifyid'=>$request['notifyId'],'NS_area'=>$area,'NS_yuanbao'=>$gameMoney,
        		'NS_server'=>$server,'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type)
	    );
        return $insert;
    }
	
	/*
	 * lenovo
	 */ 
	public function insertLxPay($request,$uid,$server,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
		$moeny = $request['money']/100;
        $insert = $db->insertTable('platform_lenovo_pay', 
        		array('NS_money'=>$moeny,'NS_count'=>$request['count'],
        		'NS_uid'=>$uid,'NS_order_id'=>$request['order_id'],
        		'NS_transid'=>$request['transid'],'NS_appid'=>$request['appid'],'NS_waresid'=>$request['waresid'],
        		'NS_feetype'=>$request['feetype'],'NS_transtype'=>$request['transtype'],'NS_transtime'=>$request['transtime'],
        		'NS_result'=>$request['result'],'NS_server'=>$server,'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type)
	    );
        return $insert;
    }
	
	/*
	 * itools
	 */ 
	public function insertItoolsPay($request,$server,$sdkPaytime,$channel_type=2)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_itools_pay', 
        		array('NS_money'=>$request['money'],'NS_account'=>$request['account'],
        		'NS_uid'=>$request['uid'],'NS_order_id'=>$request['order_id'],'NS_order_com'=>$request['order_id_com'],
        		'NS_result'=>$request['result'],'NS_server'=>$server,'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type)
	    );
        return $insert;
    }

	/*
	 * youxin
	 */ 
	public function insertYxPay($request,$uid,$server,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
		$moeny = $request['money']/100;
        $insert = $db->insertTable('platform_youxin_pay', 
        		array('NS_money'=>$moeny,'NS_uid'=>$uid,'NS_openid'=>$request['openid'],'NS_order_id'=>$request['order_id'],
        		'NS_ordertime'=>$request['ordertime'],'NS_notifytime'=>$request['notifytime'],
        		'NS_result'=>$request['result'],'NS_server'=>$server,'NS_sdk_pay_time'=>$sdkPaytime,
        		'NS_channel_type'=>$channel_type)
	    );
        return $insert;
    }

	/*
	 * 机锋
	 */ 
	public function insertJfPay($request,$uid,$server,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
		$moeny = $request['cost']/10;
		$time  = date('Y-m-d H:i:s',$request['create_time']);
        $insert = $db->insertTable('platform_jifeng_pay', 
        		array(
        			  'NS_money'=>$moeny,'NS_uid'=>$uid,'NS_order_id'=>$request['order_id'],
        		      'NS_ordertime'=>$time,'NS_server'=>$server,'NS_sdk_pay_time'=>$sdkPaytime,
        		      'NS_channel_type'=>$channel_type
				)
	    );
        return $insert;
    }
	
	/*
	 * 木蚂蚁
	 */ 
	public function insertMyPay($request,$uid,$server,$sdkPaytime,$channel_type=1)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_mumayi_pay', 
        		array(
        			  'NS_money'=>$request['money'],'NS_uid'=>$uid,'NS_order_id'=>$request['order_id'],
        		      'NS_ordertime'=>$request['ordertime'],'NS_server'=>$server,'NS_result'=>$request['result'],
        		      'NS_sdk_pay_time'=>$sdkPaytime,'NS_channel_type'=>$channel_type
				)
	    );
        return $insert;
    }
	
	
	public function selectPayOrder($uid,$order_id,$table)
    {
        $db = $this->db;
        $result = $db->getResultsByCondition($table, sprintf("NS_uid = '%s' and NS_order_id = '%s';", $uid, $order_id), 'NS_order_id');

        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_order_id'];
            }
        }
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
        $result = $db->getResultsByCondition($table,sprintf("NS_area = '%s' and NS_uid = '%s' LIMIT 1;", $area, $uid), 'NS_id');
        if(is_array($result))
        {
            foreach($result as $row)
            {
                return $row['NS_id'];
            }
        }
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