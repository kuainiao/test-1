<?php
//die;
/**
 * 游戏区服合并   平台sdk验证用户数据调整
 * @author wolf
 * @time 2013-11-23 p.m. 16:11
 */
date_default_timezone_set('Asia/ShangHai');
require_once '../../models/MergeServerModel.php';
/*$options = getopt("",array('mergeserver:'));
var_dump($options);die;*/
if($argc == 3){
	$str1 = explode('mergeserver=',$argv[1]);
	$str2 = explode('server=',$argv[2]);
	$mergeserver = (int)$str1[1];
	$server      = (int)$str2[1];
	//echo $mergeserver;die;
}elseif(isset($_GET['mergeserver'])){
	$mergeserver = $_GET['mergeserver'];
	$server      = $_GET['server'];
}else{
	die("参数错误!");
}

$merge = new MergeServer();
$data = $merge->deal($mergeserver,$server);	
var_dump($data);
echo "\n";die;

Class MergeServer{
	/*
	 * 所需table name
	 * $mergeserver 哪个服要合并(区)
	 * $server      合并到哪个服(区)
	 */
   public function deal($mergeserver=300,$server=100){
   	    //echo $server;die;
		$table_names = $this->getTableName();
		//var_dump($table_names);die;
		$nskydb = new MergeServerModel();
		$gamedb = new MergeToServerModel($server);
		//var_dump($gamedb);die;
		if($table_names)foreach($table_names as $third_type=>$table){
			//被合并的服的用户uid
			$data = $nskydb->selectUser($table,$mergeserver);
			$data3 = array();
			$data4 = array();
			if($data)foreach($data as $v){
				if($v['NS_uid']!=''){
					$data3[] = $v['NS_uid'];
				}
			}
			//var_dump($data);
			//合并到服的现有的用户uid
			$data1 = $nskydb->selectUser($table,$server);
			if($data1)foreach($data1 as $v1){
				if($v1['NS_uid']!=''){
					$data4[] = $v1['NS_uid'];
				}
			}
			//var_dump($data4);die;
			$data2 = array_diff($data3,$data4);
			/*var_dump($data2);echo "\n";*/ echo count($data2);echo "\n";//die;
			//处理要合并服中没有在合并到服中注册的用户
			$i=0;
			if($data2!=array())foreach($data2 as $uid){
				$channel_type = $nskydb->selectThirdUserField($table,$mergeserver,$uid,'NS_channel_type');
				//var_dump($channel_type);die;
				if($channel_type==''||(int)$channel_type==0){
					$channel_type =1;
				}
				$account_id = $gamedb->selectAccountThrid($uid,$third_type);//123456;
				if($account_id){
					$wz_username = $gamedb->selectAccount($account_id);//'test测试';
					if($account_id!=''&&$wz_username!='')
					$insert = $nskydb->insertThirdUser($table, $server, $uid, $account_id, $wz_username,(int)$channel_type);
					$i++;
					$arr = array('uid'=>$uid,'table'=>$table,'server'=>$server,
								'wz_username'=>$wz_username,'account_id'=>$account_id,
								'channel_type'=>$channel_type,'third_type'=>$third_type);
				    var_dump($arr);
					echo "\n";
					echo $insert."\n";
				}else{
					$merge_fail = $nskydb->insertMergeFail($uid,$table);
				}
			}
			echo $table." ok\n";//die;
			echo $i."\n";
			echo count($data2)."\n";
		}
		return '执行完毕    ';
	}
	
	public function getTableName(){
		$data = array(
					 /*0=>'nineone',1=>'uc',2=>'djoy',3=>'xiaomi',*/4=>'duoku',5=>'anzhi',
					  6=>'wdj',7=>'qh',8=>'pp',9=>'kunlun',10=>'tb',
				);
		return $data;
	}
	
	/*public function dealMergeSever($area,$uid){
		
	}*/
} 
