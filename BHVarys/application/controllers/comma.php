<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comma extends CI_Controller {
	
	
	/**
	 * 定时运行
	 */
	function actioneverydaydata(){
		echo date('Y-m-d H:i:s');
        $dt = date('Y-m-d',strtotime("-1 day"));
		//方法            
        $this->actionflatdata($dt);
        $this->actionchanneldata($dt);
        $this->actionareadata($dt);
        $this->actionserverdata($dt);
        $this->actionflatchannelarea($dt);
        $this->actionflatchannelserver($dt);
        $this->actionflatareaserver($dt);
        $this->actionflatchanlareaser($dt);
		$this->actionpsdata($dt);
        
		$mt = date('Y-m',time());
		$this->actionareamonth($mt);
		$this->actionservermonth($mt);
		$this->actiongetmonth($mt);
		$this->actionmonthchannel($mt);
		$this->actionmtflatchannelarea($mt);
		$this->actionmonthflatchannelserver($mt);
		echo date('Y-m-d H:i:s');

	}
	
		
	 /**
	 * 平台日统计 
	 */
	function actionflatdata($date){
		//定义平台
		$t  = $this->t;
		$tn = $this->tn;
	
		//$t = array('26'=>'ios');
		//$tn = array('26'=>'ios');
		$rst = $this->actiongetdata($date,$t,$tn,0,0,0);
		//print_r($rst);
		//插入数据库
		foreach($rst as $v){
			$sql = "insert into `stats_day_flat` set `df_flat`=:f,`df_fname`=:fn,`df_money`=:m,`df_usernums`=:num,`df_paynums`=:pnums,`df_payaddnums`=:pyadnum,`df_payaddmoney`=:pyadmy,`df_updaynums`=:upnums,`df_upthirdnums`=:thirdnums,`df_upsevennums`=:sevennums,`df_upfifteennums`=:fifnums,`df_upthirtynums`=:thirtynums,`df_login_nums`=:lgnum,`df_date`=:d,`df_time`=:t,`df_upday`=:dayusers,`df_upthirdusers`=:thirdusers,`df_upsevenusers`=:sevenusers,`df_upfifteenusers`=:fifusers,`df_upthirtyusers`=:thirtyusers";       
			$parm = array(':f'=>$v['flat'],':fn'=>$v['flatname'],':m'=>$v['money'],':num'=>$v['usernums'],':pnums'=>$v['paynums'],':pyadnum'=>$v['payaddnums'],':pyadmy'=>$v['payaddmoney'],':upnums'=>$v['updaynums'],':thirdnums'=>$v['upthirdnums'],':sevennums'=>$v['upsevennums'],':fifnums'=>$v['upfifteennums'],':thirtynums'=>$v['upthirtynums'],':lgnum'=>$v['loginnums'],':d'=>$date,':t'=>time(),':dayusers'=>$v['updayusers'],':thirdusers'=>$v['upthirdusers'],':sevenusers'=>$v['upsevenusers'],':fifusers'=>$v['upfifteenusers'],':thirtyusers'=>$v['upthirtyusers']);
			Yii::app()->db_wz->createCommand($sql)->bindValues($parm)->execute();
			unset($sql); 
		}
	}
	
}