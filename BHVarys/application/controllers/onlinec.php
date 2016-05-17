<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Onlinec extends CI_Controller {
	private $f  = array();  //��׿ƽ̨
	private $fn = array();  //��׿ƽ̨����
	private $a  = array();  //��Ϸ����
	private $s  = array();  //��Ϸ��
	private $is = array();  //Խ����ios
	private $isn = array(); //Խ����ios����
	private $i  = array();  //��Խ����ios
	private $in = array();  //��Խ����ios����
	private $t  = array();  //ƽ̨
	private $tn = array();  //ƽ̨����
	private $channel = array();
	

	function  __construct(){
		parent::__construct();
		$this->load->model('ns_model', 'nsM');
		$this->load->model('wz_model', 'wzM');
		$this->load->model('bh_model', 'bhM');
		
		date_default_timezone_set('Asia/Shanghai');
        ini_set('memory_limit','-1');
		//��ȡƽ̨����
        $fsql = "select * from `platform_plat`";
		//$flatarr = $this->wzM->query_data_arr($fsql);
		$flatarr = $this->bhM->query_data_arr($fsql);
		/*
		foreach($flatarr as $v){
		  $sql= "insert into platform_plat values('','{$v['platform_name']}','{$v['platform_table']}','{$v['platform_type']}','{$v['platform_third_type']}')";
		  $this->bhM->query_sql($sql); 
		  

		  echo $sql;
		}
		$in = $this->bhM->query_sql($sql); 
		*/
		foreach($flatarr as $ky=>$vl){
            $cha_type = explode(',', $vl['platform_type']);
            //��׿
            if(in_array(1,$cha_type)){
                $this->f[$vl['platform_third_type']] = $vl['platform_table'];
                $this->fn[$vl['platform_third_type']] = $vl['platform_name'];
            }
            //Խ��
            if(in_array(2,$cha_type)){
                $this->is[$vl['platform_third_type']] = $vl['platform_table'];
                $this->isn[$vl['platform_third_type']] = $vl['platform_name'];
            }
            //��Խ��
            if(in_array(3,$cha_type)){
                $this->i[$vl['platform_third_type']] = $vl['platform_table'];
                $this->in[$vl['platform_third_type']] = $vl['platform_name'];
            }
            //����ƽ̨ 0  nineone 91
            $this->t[$vl['platform_third_type']] = $vl['platform_table'];
            $this->tn[$vl['platform_third_type']] = $vl['platform_name'];
			
		}
		//��ȡ��������arrr
		$sev = '';
		$asql = "select `area_id`,`server_id` from `platform_area`";
		$arearr = $this->bhM->query_data_arr($asql);	
		print_r($arearr);
		foreach ($arearr as $ky => $vl) {
			$this->a[] = $vl['area_id'];
			$sev .= ','.$vl['server_id'];
			//1001,1002server
			$this->s = array_filter(array_filter(explode(',',$sev)));
		}
		print_r($this->a);
		//array(1=>'��׿',2=>'Խ����IOS',3=>'IOS');
		$this->channel = $this->config->item('channelt');	
	}
	
	public function dump($data){
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
	
	/**
	 * ���ĳһʱ����ڵ����ݣ�1��Сʱ��
	 */
	 //$type android $area ��� $ser С�� 
	function actiononlinedata($time,$t,$tn,$type=0,$area=0,$ser=0){
		//ʱ��2015-12-1512:00
		
		$te = strtotime($time);
		$ts = $te-3600;
		//����
		$datas = array();
		//ѭ��ƽ̨
		foreach($t as $k=>$v){
			$sql = "select `NS_third_id` from `platform_user_login_log` where `NS_third_type`=".$k." and `NS_login_time` between ".$ts." and ".$te;
			
			if($type > 0){
				$sql .= " and `NS_channel_type`=".$type;
			}
			if($area > 0){
				$sql .= " and `NS_area` between ".$area." and ".($area+100);
			}
			if($ser > 0){
				$sql .= " and `NS_server`=".$ser;
			}
			$sql .= " group by `NS_third_id`";
			echo $sql;
			$ids = $this->nsM->query_data_arr($sql);
			if(empty($ids)){
				
				$re['uids'] = 0;
			}else{
				$re['uids'] = count($ids);
			}
			$re['flat'] = $v;
			$re['fname'] = $tn[$k];
			if($type > 0){
				$re['channel'] = $type;
			}
			if($area > 0){
				$re['area'] = $area;
			}
			if($ser > 0){
				$re['server'] = $ser;
			}
			$re['start'] = $ts;
			$re['end'] = $te;
			$datas[] = $re;
		}
		
		
		return $datas;
	}
	
	/****************************************** ������ݿ�  *****************************************/
	
	/**
	 * ��ƽ̨��ѯ
	 */
	function actionflatdata($time){
		//����ƽ̨2015-12-1512:00
		$t  = $this->t;
		$tn = $this->tn;
		
		//26 sever data һ��Сʱ�е�
		$rst = $this->actiononlinedata($time,$t,$tn,0,0,0);
		foreach ($rst as $k => $v){
			 $sql= "insert into stats_online_flat values('','{$v['flat']}','{$v['fname']}','{$v['uids']}','{$v['start']}','{$v['end']}')";
			 $this->bhM->query_sql($sql); 			
			unset($sql);
		}
		
		
		
		
	}
	
	/**
	 * ��������ѯ
	 */
	function actionchanneldata($time){
		$ch = $this->channel;
		foreach ($ch as $ck => $cv) {
			//����ƽ̨
			if($ck == 1){
				$t = $this->f;
				$tn = $this->fn;	
			}
			if($ck == 2){
				$t = $this->is;
				$tn = $this->isn;	
			}
			if($ck == 3){
				$t = $this->i;
				$tn = $this->in;	
			}
			$rst = $this->actiononlinedata($time,$t,$tn,$ck,0,0);
			
			foreach ($rst as $k => $v){
				
				$sql= "insert into stats_online_channel values('','{$v['flat']}','{$v['fname']}','{$v['channel']}','{$v['uids']}','{$v['start']}','{$v['end']}')";
			//	echo $sql;
				$this->bhM->query_sql($sql); 			
				unset($sql);
				
			}
		}
		
	}
	
	/**
	 * ��������ѯ
	 */
	function actionareadata($time){
		$a = $this->a;
		foreach ($a as $ak => $av){
			//����ƽ̨
			$t  = $this->t;
			$tn = $this->tn;
			$rst = $this->actiononlinedata($time,$t,$tn,0,$av,0);
			print_r($rst);
			
			foreach ($rst as $k => $v){
				
				
				$sql= "insert into stats_online_area values('','{$v['flat']}','{$v['fname']}','{$v['area']}','{$v['uids']}','{$v['start']}','{$v['end']}')";
			//	echo $sql;
				$this->bhM->query_sql($sql); 			
				unset($sql);
			
			}
		}
	}
	
	/**
	 * ����Ϸ����ѯ
	 */
	function actionserverdata($time){
		$s = $this->s;
		//����ƽ̨
		$t  = $this->t;
		$tn = $this->tn;
		foreach ($s as $sk => $sv){
			$rst = $this->actiononlinedata($time,$t,$tn,0,0,$sv);
			foreach ($rst as $k => $v){
				
				
				$sql= "insert into stats_online_server values('','{$v['flat']}','{$v['fname']}','{$v['server']}','{$v['uids']}','{$v['start']}','{$v['end']}')";
			//	echo $sql;
				$this->bhM->query_sql($sql); 			
				unset($sql);
				
				
			}
		}
	}
	
	/**
	 * ������������ѯ 
	 */
	function actionfcadata($time){
		$ch = $this->channel;
		$a = $this->a;
		foreach ($ch as $ck => $cv){
			//����ƽ̨
			if($ck == 1){
				$t = $this->f;
				$tn = $this->fn;	
			}
			if($ck == 2){
				$t = $this->is;
				$tn = $this->isn;	
			}
			if($ck == 3){
				$t = $this->i;
				$tn = $this->in;	
			}
			foreach ($a as $ak => $av) {
				$rst = $this->actiononlinedata($time,$t,$tn,$ck,$av,0);
				print_r($rst);
			
				foreach ($rst as $k => $v){
					$sql= "insert into stats_online_fca values('','{$v['flat']}','{$v['fname']}','{$v['channel']}','{$v['area']}','{$v['uids']}','{$v['start']}','{$v['end']}')";
					echo $sql.'<br/>';
					$this->bhM->query_sql($sql); 			
					unset($sql);
					
					
				}
			}
		}
	}
	
	/**
	 * ������������ѯ 
	 */
	function actionfcsdata($time){
		$ch = $this->channel;
		$s = $this->s;
		foreach ($ch as $ck => $cv){
			//����ƽ̨
			if($ck == 1){
				$t = $this->f;
				$tn = $this->fn;	
			}
			if($ck == 2){
				$t = $this->is;
				$tn = $this->isn;	
			}
			if($ck == 3){
				$t = $this->i;
				$tn = $this->in;	
			}
			foreach ($s as $sk => $sv) {
				$rst = $this->actiononlinedata($time,$t,$tn,$ck,0,$sv);
				print_r($rst);
				foreach ($rst as $k => $v){
					
					
					
					$sql= "insert into stats_online_fcs values('','{$v['flat']}','{$v['fname']}','{$v['channel']}','{$v['server']}','{$v['uids']}','{$v['start']}','{$v['end']}')";
					echo $sql.'<br/>';
					$this->bhM->query_sql($sql); 			
					unset($sql);
					
				}
			}
		}
	}

/*********************** ɾ��`stats_online_now`һ��ǰ���� ***************************/

   function actiondeleteonline(){
        $t = date('Y-m-d',strtotime('-7 day'));
        $sql = "delete from `stats_online_now` where `sn_time` like '".$t."%'";
        Yii::app()->db_wz->createCommand($sql)->execute();
        unset($sql);        
   }
	
	
	/**
	 * ��ȡʱ���ѯ 
	 */
	function actiongotobegin(){
		echo date('Y-m-d H:i:s');
		$day = date('Y-m-d',strtotime('-1 days'));
		
		$t = array();
		for($i=1;$i<25;$i++){
			if($i<10){
				$d = $day.' 0'.$i.':00';
			}else{
				$d = $day.' '.$i.':00';
			}
			$t[] = $d;
		}
		//2015-12-1504:00
		//2015-12-1512:00
		foreach($t as $v){
			$this->actionflatdata($v);
			
			$this->actionchanneldata($v);
			
			$this->actionareadata($v);
			
			$this->actionserverdata($v);
			
			$this->actionfcadata($v);
		
			$this->actionfcsdata($v);
			echo $v." datas over!\n";
			exit();
		
		}

        $this->actiondeleteonline();
		echo date('Y-m-d H:i:s');
		
	}
}
    
	
	
	
	
	
	
	