<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class GIndex extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->data['viewspath'] = base_url() . APPPATH . 'views';
        $this->load->model('gindex_model', 'indexM');
        $this->data['title'] = '龙塔数据后台';
        $this->data['urlpath'] = base_url().'/';
        $this->load->library('session');
        $this->data['user'] = $this->session->userdata('user');
		$this->load->model('account_model', 'accountM');
    }

    public function login(){
		
        if(!empty($_POST)){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password_db_array = $this->indexM->get_data('lt_admin_user', array('username'=>$username));
            $password_db = '';
            foreach($password_db_array as $values){
                $password_db = $values->password;
            }
            if(md5($password) == $password_db){
                $array = array('username'=>$username);
                $add_session = $this->session->set_userdata($array);
                header('Location: '.base_url('index.php/gindex'));
            }
            else{
                $this->data['alert'] = '用户名或者密码错误请重新输入';
                $this->load->view('login', $this->data);
            }
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }

    public function logout(){
        $this->session->unset_userdata('username');
        header('Location: '.base_url('index.php/gindex/login'));
    }

    public function index()
    {
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['username'] = $this->session->all_userdata()['username'];;
            $yesterday_zero = strtotime("today")-24*60*60;
            $this->data['yesterday_new_users'] = $this->indexM->get_sum('lt_day_new_user','number',array('date'=>$yesterday_zero, 'system'=>0, 'channel'=>0, 'server'=>0));
            $this->data['users'] = $this->indexM->get_sum('lt_day_new_user','number', array('id !='=>'0', 'system'=>0, 'channel'=>0, 'server'=>0));
            $this->data['yesterday_new_money'] = $this->indexM->get_sum('lt_day_pay','money',array('date'=>$yesterday_zero, 'system'=>0, 'channel'=>0, 'server'=>0));
            $this->data['money'] = $this->indexM->get_sum('lt_day_pay','money',array('id !='=>'0', 'system'=>0, 'channel'=>0, 'server'=>0));
            $this->data['side'] = $this->side();
            $this->load->view('index', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }
	
	//
    public function daily_return($start_date='', $stop_date='', $server='', $channel='', $system='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
		
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['system'] = $this->config->item('system');
            $this->data['channel'] = $this->config->item('channel');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($start_date) && empty($stop_date) && empty($server) && empty($channel) && empty($system)){
                $seven_day_before = strtotime("today")-7*24*60*60;
                $today = strtotime("today");
                $this->data['start_date'] = date("Y-m-d",$seven_day_before);
                $this->data['stop_date']  = date("Y-m-d",$today-24*60*60);
                $sql = "select sum(money) as money,date from lt_day_pay where date>={$seven_day_before} and date<{$today} and server=0 and channel=0 and system=0  group by date";
                $this->data['last_7day_daily_return'] = $this->indexM->query_data($sql);
				 $this->data['sys'] = '所有系统';
                $this->data['chan'] = '所有平台';
                $this->data['ser'] = '所有服务器';
            }
            else{
                
                $this->data['start_date'] = $start_date;
                $this->data['stop_date'] = $stop_date;
                $start_date = strtotime($start_date);
                $stop_date = strtotime($stop_date)+24*60*60;
				$sql = "select sum(money) as money,date from lt_day_pay where system={$system} and channel={$channel} and server={$server} and date>={$start_date} and date<={$stop_date} group by date";
				if($system==0){
                    $this->data['sys'] = '所有系统';
                }
                elseif($system==2){
                    $this->data['sys'] = '安卓系统';
                }
                else{
                    $this->data['sys'] = 'IOS系统';
                }

                if($channel==0){
                    $this->data['chan'] = '所有平台';
                }
                else{
                    $this->data['chan'] = $this->config->item('channel')[$channel-1]['name'].'平台';
                }

                if($server==0){
                    $this->data['ser'] = '所有服务器';
                }
                else{
                    $this->data['ser'] = $this->config->item('server')[$server-100]['name'].'服';
                }
				
		
                $this->data['system'] = $this->config->item('system');
                $this->data['channel'] = $this->config->item('channel');
                $this->data['server'] = $this->config->item('server');
                $this->data['start_date'] = date("Y-m-d H:i:s",$start_date);
                $this->data['stop_date']  = date("Y-m-d H:i:s",$stop_date-24*60*60);
                $this->data['last_7day_daily_return'] = $this->indexM->query_data($sql);
            }
			//echo $sql;
            $this->load->view('daily_return', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }
	
	
	
	
	
	public function month_return($start_date='', $stop_date='', $server='', $channel='', $system='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
		date_default_timezone_set("Asia/Shanghai");
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['system'] = $this->config->item('system');
            $this->data['channel'] = $this->config->item('channel');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];
			$year = date("Y");

            if(empty($start_date) && empty($stop_date) && empty($server) && empty($channel) && empty($system)){

				//5个月前
				$month = date("m");
				$start_month = $month-5;
				$start_time = strtotime($year."-".$start_month."-1");
				$stop_time=strtotime("today");
				$this->data['start_date'] = date("Y-m",$start_time);
                $this->data['stop_date']  = date("Y-m",$stop_time);
				for($i=0;$i<6;$i++){
					$start_tim = strtotime($year."-".$start_month."-1");
					$start_month=$start_month+1;
					$end_time= strtotime($year."-".$start_month."-1");
					$sql = "select sum(money) as money,date from lt_day_pay where system=0 and channel=0 and server=0 and date>={$start_tim} and date<{$end_time}";
					//echo $sql;
					$arr = $this->indexM->query_data($sql);
					$arr = $this->indexM->object_array($arr);
					if(!empty($arr[0]['money'])){
						$this->data['last_6_month_return'][] = $arr;
						
					}else{
						$this->data['last_6_month_return'][]=array(0=>array("money"=>0,"date"=>$start_tim));
					}
					
				}
				$this->data['sys'] = '所有系统';
                $this->data['chan'] = '所有平台';
                $this->data['ser'] = '所有服务器';

            }
            else{
				if($system==0){
                    $this->data['sys'] = '所有系统';
                }
                elseif($system==2){
                    $this->data['sys'] = '安卓系统';
                }
                else{
                    $this->data['sys'] = 'IOS系统';
                }

                if($channel==0){
                    $this->data['chan'] = '所有平台';
                }
                else{
                    $this->data['chan'] = $this->config->item('channel')[$channel-1]['name'].'平台';
                }

                if($server==0){
                    $this->data['ser'] = '所有服务器';
                }
                else{
                    $this->data['ser'] = $this->config->item('server')[$server-100]['name'].'服';
                }
				
				
				
                $this->data['start_date'] = $start_date;
                $this->data['stop_date'] = $stop_date;
                $start_date = strtotime($start_date);
                $stop_date = strtotime($stop_date);
				$start_month=date("m",$start_date);				
				$end_month=date("m",$stop_date);
				$month_diff=$end_month-$start_month;
				
				for($i=0;$i<=$month_diff;$i++){
						$start_time = strtotime($year."-".$start_month."-1");
						$start_month=$start_month+1;
						$end_time= strtotime($year."-".$start_month."-1");
						$sql = "select sum(money) as money,date from lt_day_pay where date>={$start_time} and date<{$end_time} and system={$system} and channel={$channel} and server={$server}";
						
						$arr = $this->indexM->query_data($sql);
						$arr = $this->indexM->object_array($arr);

						if(!empty($arr[0]['money'])){
							$this->data['last_6_month_return'][] = $arr;
							
						}else{
							$this->data['last_6_month_return'][]=array(0=>array("money"=>0,"date"=>$start_time));
						}
						
				}
				
				$this->data['system'] = $this->config->item('system');
                $this->data['channel'] = $this->config->item('channel');
                $this->data['server'] = $this->config->item('server');
                $this->data['start_date'] = date("Y-m",$start_date);
                $this->data['stop_date']  = date("Y-m",$stop_date);
              
            }
			//echo $sql;
            $this->load->view('month_return', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }
	

	

	
	
	
		public function month_return_user($start_date='', $stop_date='', $server='', $channel='', $system='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
		date_default_timezone_set("Asia/Shanghai");
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['system'] = $this->config->item('system');
            $this->data['channel'] = $this->config->item('channel');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];
			$year = date("Y");
			$this->data['last_6_month_return']= array();
            if(empty($start_date) && empty($stop_date) && empty($server) && empty($channel) && empty($system)){

				//5个月前
				$month = date("m");
				$start_month = $month-5;
				$start_time = strtotime($year."-".$start_month."-1");
				$stop_time=strtotime("today");
				$this->data['start_date'] = date("Y-m",$start_time);
                $this->data['stop_date']  = date("Y-m",$stop_time);
				for($i=0;$i<6;$i++){
					$start_tim = strtotime($year."-".$start_month."-1");
					$start_month=$start_month+1;
					$end_time= strtotime($year."-".$start_month."-1");
					$sql = "select sum(number) as number,date from lt_day_new_user where system=0 and channel=0 and server=0 and date>={$start_tim} and date<{$end_time}";
					
					$arr = $this->indexM->query_data($sql);
					$arr = $this->indexM->object_array($arr);
					
					
					if(!empty($arr[0]['number'])){
						$this->data['last_6_month_return'][] = $arr;
						
					}else{
						$this->data['last_6_month_return'][]=array(0=>array("number"=>0,"date"=>$start_tim));
					}
					
					
				}
				$this->data['sys'] = '所有系统';
                $this->data['chan'] = '所有平台';
                $this->data['ser'] = '所有服务器';
				
            }
			
            else{
				
				if($system==0){
                    $this->data['sys'] = '所有系统';
                }
                elseif($system==2){
                    $this->data['sys'] = '安卓系统';
                }
                else{
                    $this->data['sys'] = 'IOS系统';
                }

                if($channel==0){
                    $this->data['chan'] = '所有平台';
                }
                else{
                    $this->data['chan'] = $this->config->item('channel')[$channel-1]['name'].'平台';
                }

                if($server==0){
                    $this->data['ser'] = '所有服务器';
                }
                else{
                    $this->data['ser'] = $this->config->item('server')[$server-100]['name'].'服';
                }

                $this->data['start_date'] = $start_date;
                $this->data['stop_date'] = $stop_date;
                $start_date = strtotime($start_date);
                $stop_date = strtotime($stop_date);
				$start_month=date("m",$start_date);				
				$end_month=date("m",$stop_date);
				$month_diff=$end_month-$start_month;

				
				for($i=0;$i<=$month_diff;$i++){
						$start_time = strtotime($year."-".$start_month."-1");
						$start_month=$start_month+1;
						$end_time= strtotime($year."-".$start_month."-1");
						$sql = "select sum(number) as number,date from lt_day_new_user where date>={$start_time} and date<{$end_time} and system={$system} and channel={$channel} and server={$server}";
						
						$arr = $this->indexM->query_data($sql);
						$arr = $this->indexM->object_array($arr);
					
					
						if(!empty($arr[0]['number'])){
							$this->data['last_6_month_return'][] = $arr;
							
						}else{
							$this->data['last_6_month_return'][]=array(0=>array("number"=>0,"date"=>$start_time));
						}
				}
				//echo $sql;
				$this->data['system'] = $this->config->item('system');
				$this->data['channel'] = $this->config->item('channel');
				$this->data['server'] = $this->config->item('server');

                $this->data['start_date'] = date("Y-m",$start_date);
                $this->data['stop_date']  = date("Y-m",$stop_date);
              
            }
            $this->load->view('month_return_user', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }
	

	
	//日充值用户
	  public function daily_return_user($start_date='', $stop_date='', $server='', $channel='', $system='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
		date_default_timezone_set("Asia/Shanghai");
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['system'] = $this->config->item('system');
            $this->data['channel'] = $this->config->item('channel');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($start_date) && empty($stop_date) && empty($server) && empty($channel) && empty($system)){
				
                $seven_day_before = strtotime("today")-7*24*60*60;
                $today = strtotime("today");
                $this->data['start_date'] = date("Y-m-d",$seven_day_before);
                $this->data['stop_date']  = date("Y-m-d",$today-24*60*60);
                $sql = "select sum(number) as number,date from lt_day_pay_user where server=0 and channel=0 and system=0 and date>={$seven_day_before} and date<{$today} group by date";
                $this->data['last_7day_daily_return'] = $this->indexM->query_data($sql);
				 $this->data['sys'] = '所有系统';
                $this->data['chan'] = '所有平台';
                $this->data['ser'] = '所有服务器';
				//echo $sql;
            }
            else{

               
                $this->data['stop_date'] = $stop_date;
                $start_date = strtotime($start_date);
                $stop_date = strtotime($stop_date)+24*60*60;
				$sql = "select sum(number) as number,date from lt_day_pay_user where system={$system} and channel={$channel} and server={$server} and date>={$start_date} and date<={$stop_date} group by date";
                //echo $sql;
				if($system==0){
                    $this->data['sys'] = '所有系统';
                }
                elseif($system==2){
                    $this->data['sys'] = '安卓系统';
                }
                else{
                    $this->data['sys'] = 'IOS系统';
                }

                if($channel==0){
                    $this->data['chan'] = '所有平台';
                }
                else{
                    $this->data['chan'] = $this->config->item('channel')[$channel-1]['name'].'平台';
                }

                if($server==0){
                    $this->data['ser'] = '所有服务器';
                }
                else{
                    $this->data['ser'] = $this->config->item('server')[$server-100]['name'].'服';
                }
				
                $this->data['system'] = $this->config->item('system');
                $this->data['channel'] = $this->config->item('channel');
                $this->data['server'] = $this->config->item('server');
                $this->data['start_date'] = date("Y-m-d H:i:s",$start_date);
                $this->data['stop_date']  = date("Y-m-d H:i:s",$stop_date-24*60*60);
                $this->data['last_7day_daily_return'] = $this->indexM->query_data($sql);
				
				
				
            }
            $this->load->view('daily_return_user', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }
 
 
			//在线用户最大数量
	    public function daily_max_online_user($start_date='', $stop_date='', $server='', $channel='', $system='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
		date_default_timezone_set("Asia/Shanghai");
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['system'] = $this->config->item('system');
            $this->data['channel'] = $this->config->item('channel');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($start_date) && empty($stop_date) && empty($server) && empty($channel) && empty($system)){
				
                $seven_day_before = strtotime("today")-7*24*60*60;
                $today = strtotime("today");
                $this->data['start_date'] = date("Y-m-d",$seven_day_before);
                $this->data['stop_date']  = date("Y-m-d",$today-24*60*60);
				
				$time=$seven_day_before;
				while($time<$today){
					$end_date=$time+24*3600;
					
					$sql = "select max(number) as number,date from lt_day_user_online where system=0 and channel=0 and server=0 and date>={$time} and date<{$end_date} group by createtime";
					$arr = $this->indexM->query_data($sql);
					$arr = $this->indexM->object_array($arr);
					if(!empty($arr[0]['number'])){
						$this->data['last_7day_daily_return'][] = $arr;
						
					}else{
						$this->data['last_7day_daily_return'][]=array(0=>array("number"=>0,"date"=>$time));
					}
					$time=$time+24*3600;
					
				}
				
				$this->data['sys'] = '所有系统';
                $this->data['chan'] = '所有平台';
                $this->data['ser'] = '所有服务器';
            }
            else{

				if($system==0){
                    $this->data['sys'] = '所有系统';
                }
                elseif($system==2){
                    $this->data['sys'] = '安卓系统';
                }
                else{
                    $this->data['sys'] = 'IOS系统';
                }

                if($channel==0){
                    $this->data['chan'] = '所有平台';
                }
                else{
                    $this->data['chan'] = $this->config->item('channel')[$channel-1]['name'].'平台';
                }

                if($server==0){
                    $this->data['ser'] = '所有服务器';
                }
                else{
                    $this->data['ser'] = $this->config->item('server')[$server-100]['name'].'服';
                }
				
				
                $start_date = strtotime($start_date);
                $stop_date = strtotime($stop_date)+24*60*60;
				$this->data['start_date'] = date("Y-m-d H:i:s",$start_date);
                $this->data['stop_date']  = date("Y-m-d H:i:s",$stop_date-24*60*60);
				$time=$start_date;
				
				while($time<$stop_date){
					$end_date=$time+24*3600;
					
					$sql = "select max(number) as number,date from lt_day_user_online where system={$system} and channel={$channel} and server={$server} and date>={$time} and date<{$end_date} group by createtime";
					$arr = $this->indexM->query_data($sql);
					$arr = $this->indexM->object_array($arr);
					if(!empty($arr[0]['number'])){
						$this->data['last_7day_daily_return'][] = $arr;
						
					}else{
						$this->data['last_7day_daily_return'][]=array(0=>array("number"=>0,"date"=>$time));
					}
					$time=$time+24*3600;
					
					
				}

                $this->data['system'] = $this->config->item('system');
                $this->data['channel'] = $this->config->item('channel');
                $this->data['server'] = $this->config->item('server');

				
				
            }
			
			
            $this->load->view('daily_max_online_user', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }


	
		//平均在线用户
	    public function daily_average_online_user($start_date='', $stop_date='', $server='', $channel='', $system='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
		date_default_timezone_set("Asia/Shanghai");
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['system'] = $this->config->item('system');
            $this->data['channel'] = $this->config->item('channel');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($start_date) && empty($stop_date) && empty($server) && empty($channel) && empty($system)){
				
                $seven_day_before = strtotime("today")-7*24*60*60;
                $today = strtotime("today");
                $this->data['start_date'] = date("Y-m-d",$seven_day_before);
                $this->data['stop_date']  = date("Y-m-d",$today-24*60*60);
				
				$time=$seven_day_before;
				while($time<$today){
					$end_date=$time+24*3600;
					$sql = "select sum(number) as number,date from lt_day_user_online where system=0 and channel=0 and server=0 and date>={$time} and date<{$end_date}";
					$arr = $this->indexM->query_data($sql);
					$arr = $this->indexM->object_array($arr);
					if(!empty($arr[0]['number'])){
						$this->data['last_7day_daily_return'][] = $arr;
						
					}else{
						$this->data['last_7day_daily_return'][]=array(0=>array("number"=>0,"date"=>$time));
					}
					$time=$time+24*3600;
					
				}
				
				$this->data['sys'] = '所有系统';
                $this->data['chan'] = '所有平台';
                $this->data['ser'] = '所有服务器';
            }
            else{

				if($system==0){
                    $this->data['sys'] = '所有系统';
                }
                elseif($system==2){
                    $this->data['sys'] = '安卓系统';
                }
                else{
                    $this->data['sys'] = 'IOS系统';
                }

                if($channel==0){
                    $this->data['chan'] = '所有平台';
                }
                else{
                    $this->data['chan'] = $this->config->item('channel')[$channel-1]['name'].'平台';
                }

                if($server==0){
                    $this->data['ser'] = '所有服务器';
                }
                else{
                    $this->data['ser'] = $this->config->item('server')[$server-100]['name'].'服';
                }
				
				
                $start_date = strtotime($start_date);
                $stop_date = strtotime($stop_date)+24*60*60;
				$this->data['start_date'] = date("Y-m-d H:i:s",$start_date);
                $this->data['stop_date']  = date("Y-m-d H:i:s",$stop_date-24*60*60);
				$time=$start_date;
				
				while($time<$stop_date){
					$end_date=$time+24*3600;
					
					$sql = "select sum(number) as number,date from lt_day_user_online where system={$system} and channel={$channel} and server={$server} and date>={$time} and date<{$end_date} group by createtime";
					$arr = $this->indexM->query_data($sql);
					$arr = $this->indexM->object_array($arr);
					if(!empty($arr[0]['number'])){
						$this->data['last_7day_daily_return'][] = $arr;
						
					}else{
						$this->data['last_7day_daily_return'][]=array(0=>array("number"=>0,"date"=>$time));
					}
					$time=$time+24*3600;
					
					
				}

                $this->data['system'] = $this->config->item('system');
                $this->data['channel'] = $this->config->item('channel');
                $this->data['server'] = $this->config->item('server');

				
				
            }
			
			
            $this->load->view('daily_average_online_user', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }




	
	
   public function daily_online_trend($start_date='', $server='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
		date_default_timezone_set("Asia/Shanghai");
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($start_date) && empty($server) && empty($channel) && empty($system)){
				
                $yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
				$today_unix = strtotime(date('Ymd'));
				
                $this->data['start_date'] = date("Y-m-d",$yesterday_unix);
                $start_date = $yesterday_unix;
				$stop_date = $start_date+24*60*60;
			
                $sql = "select sum(number) as number,date from lt_day_user_online where date>={$start_date} and date<{$stop_date} and server=0 and channel=0 and system=0 group by date";
                $this->data['last_7day_daily_return'] = $this->indexM->query_data($sql);
				$this->data['ser'] = '所有服务器';
				
            }
            else{
                $this->data['start_date'] = $start_date;
               
                $start_date = strtotime($start_date);
                $stop_date = $start_date+24*60*60;
				if($server==0){
                    $this->data['ser'] = $this->data['start_date'].'所有服务器在线统计';
                }
                else{
                    $this->data['ser'] = $this->data['start_date'].$this->config->item('server')[$server-100]['name'].'服在线统计';
                }
				$sql = "select sum(number) as number,date from lt_day_trend_user where server={$server} and channel=0 and system=0 and date>={$start_date} and date<{$stop_date} group by date";
               echo $sql;
                $this->data['system'] = $this->config->item('system');
                $this->data['channel'] = $this->config->item('channel');
                $this->data['server'] = $this->config->item('server');
                $this->data['start_date'] = date("Y-m-d",$start_date);
               
                $this->data['last_7day_daily_return'] = $this->indexM->query_data($sql);

            }
			
			//echo $sql;
			
			
            $this->load->view('daily_online_trend', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }


	
   
	
 
	//日综合统计
		  public function dayily_multiple($start_date='', $stop_date='', $server='', $channel='', $system='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
		date_default_timezone_set("Asia/Shanghai");
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['system'] = $this->config->item('system');
            $this->data['channel'] = $this->config->item('channel');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($start_date) && empty($stop_date) && empty($server) && empty($channel) && empty($system)){
				
                $seven_day_before = strtotime("today")-7*24*60*60;
                $today = strtotime("today");
                $this->data['start_date'] = date("Y-m-d",$seven_day_before);
                $this->data['stop_date']  = date("Y-m-d",$today-24*60*60);
                $sql = "select sum(new_number) as number1,sum(pay_number) as number2,sum(active_number) as number3,sum(over_number) as number4,date from lt_day_multiple where server=0 and channel=0 and system=0 and date>={$seven_day_before} and date<{$today} group by date";
                $this->data['last_7day_daily_return'] = $this->indexM->query_data($sql);
				 $this->data['sys'] = '所有系统';
                $this->data['chan'] = '所有平台';
                $this->data['ser'] = '所有服务器';
				
            }
            else{
               
                $this->data['start_date'] = $start_date;
                $this->data['stop_date'] = $stop_date;
                $start_date = strtotime($start_date);
                $stop_date = strtotime($stop_date)+24*60*60;
				$sql = "select sum(new_number) as number1,sum(pay_number) as number2,sum(active_number) as number3,sum(over_number) as number4,date from lt_day_multiple where system={$system} and channel={$channel} and server={$server} and date>={$start_date} and date<{$stop_date} group by date";
				if($system==0){
                    $this->data['sys'] = '所有系统';
                }
                elseif($system==2){
                    $this->data['sys'] = '安卓系统';
                }
                else{
                    $this->data['sys'] = 'IOS系统';
                }

                if($channel==0){
                    $this->data['chan'] = '所有平台';
                }
                else{
                    $this->data['chan'] = $this->config->item('channel')[$channel-1]['name'].'平台';
                }

                if($server==0){
                    $this->data['ser'] = '所有服务器';
                }
                else{
                    $this->data['ser'] = $this->config->item('server')[$server-100]['name'].'服';
                }
				

			
                $this->data['system'] = $this->config->item('system');
                $this->data['channel'] = $this->config->item('channel');
                $this->data['server'] = $this->config->item('server');
                $this->data['start_date'] = date("Y-m-d H:i:s",$start_date);
                $this->data['stop_date']  = date("Y-m-d H:i:s",$stop_date-24*60*60);
                $this->data['last_7day_daily_return'] = $this->indexM->query_data($sql);
				
				
            }
			//echo $sql;
			
            $this->load->view('daily_multiple', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }
 
 
 
	
	
	
		//ACU和PCU
		  public function dayily_pc_user($start_date='', $stop_date='', $server='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
		date_default_timezone_set("Asia/Shanghai");
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['system'] = $this->config->item('system');
            $this->data['channel'] = $this->config->item('channel');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($start_date) && empty($stop_date) && empty($server)){
				
                $seven_day_before = strtotime("today")-7*24*60*60;
                $today = strtotime("today");
                $this->data['start_date'] = date("Y-m-d",$seven_day_before);
                $this->data['stop_date']  = date("Y-m-d",$today-24*60*60);
				$time=$seven_day_before;
				//pcu
				while($time<$today){
					$end_date=$time+24*3600;
					
					$sql = "select max(number) as number,date from lt_day_user_online where system=0 and channel=0 and server=0 and date>={$time} and date<{$end_date} group by createtime";
					$arr = $this->indexM->query_data($sql);
					$arr = $this->indexM->object_array($arr);
					
					if(!empty($arr[0]['number'])){
						$this->data['pcu'][] = $arr;
						
					}else{
						$this->data['pcu'][]=array(0=>array("number"=>0,"date"=>$time));
					}
					
					$sql = "select sum(number) as number,date from lt_day_user_online where system=0 and channel=0 and server=0 and date>={$time} and date<{$end_date}";
					$arr = $this->indexM->query_data($sql);
					$arr = $this->indexM->object_array($arr);
					
					if(!empty($arr[0]['number'])){
						$this->data['acu'][] = $arr;
						
					}else{
						$this->data['acu'][]=array(0=>array("number"=>0,"date"=>$time));
					}
					$this->data['time'][] = $time;
					$time=$time+24*3600;
					
					
				}
				
                $this->data['ser'] = '所有服务器';
				
            }
            else{
 
                $this->data['server'] = $this->config->item('server');
                $start_date = strtotime($start_date);
                $stop_date = strtotime($stop_date)+24*60*60;
				
				$time=$start_date;
				//acu
				while($time<$stop_date){
					$end_date=$time+24*3600;
					
					$sql = "select max(number) as number,date from lt_day_user_online where system=0 and channel=0 and server={$server} and date>={$time} and date<{$end_date}";
					
					$arr = $this->indexM->query_data($sql);
					$arr = $this->indexM->object_array($arr);
					
					if(!empty($arr[0]['number'])){
						$this->data['pcu'][] = $arr;
						
					}else{
						$this->data['pcu'][]=array(0=>array("number"=>0,"date"=>$time));
					}
					
					$sql = "select sum(number) as number,date from lt_day_user_online where system=0 and channel=0 and server={$server} and date>={$time} and date<{$end_date}";
					
					$arr = $this->indexM->query_data($sql);
					$arr = $this->indexM->object_array($arr);
					
					if(!empty($arr[0]['number'])){
						$this->data['acu'][] = $arr;
						
					}else{
						$this->data['acu'][]=array(0=>array("number"=>0,"date"=>$time));
					}
					$this->data['time'][] = $time;
					$time=$time+24*3600;

				}
			
				
				 if($server==0){
                    $this->data['ser'] = '所有服务器';
                }
                else{
                    $this->data['ser'] = $this->config->item('server')[$server-100]['name'].'服';
                }
				
                $this->data['start_date'] = date("Y-m-d H:i:s",$start_date);
                $this->data['stop_date']  = date("Y-m-d H:i:s",$stop_date-24*60*60);
                
            }
			//echo $sql;
			
            $this->load->view('daily_pc_user', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }
 
 
 
	
	
	
		public function daily_struct_user($start_date='', $server='', $channel='', $system='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
		date_default_timezone_set("Asia/Shanghai");
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['system'] = $this->config->item('system');
            $this->data['channel'] = $this->config->item('channel');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($start_date) && empty($server) && empty($channel) && empty($system)){
				
                $yesterday = strtotime("today")-24*60*60*2;
                $today = strtotime("today");
                $this->data['start_date'] = date("Y-m-d",$yesterday);
                
				for($id=1;$id<=10;$id++){
					$sql = "select sum(money) as money,date,moneyid,number from lt_day_pay_struct where system=0 and channel=0 and server=0  and date>={$yesterday} and date<{$today} and moneyid={$id}";
					$arr = $this->indexM->query_data($sql);
					$arr = $this->indexM->object_array($arr);
					
					if(!empty($arr[0]['money'])){
						$this->data['last_7day_daily_return'][] = $arr;
						
					}else{
						$this->data['last_7day_daily_return'][]=array(0=>array("money"=>0,"date"=>$yesterday,"moneyid"=>$id,"number"=>0));
					}
					
				}
				$this->data['sys'] = '所有系统';
                $this->data['chan'] = '所有平台';
                $this->data['ser'] = '所有服务器';
				

            }
            else{
                if($system==0){
                    $this->data['sys'] = '所有系统';
                }
                elseif($system==2){
                    $this->data['sys'] = '安卓系统';
                }
                else{
                    $this->data['sys'] = 'IOS系统';
                }

                if($channel==0){
                    $this->data['chan'] = '所有平台';
                }
                else{
                    $this->data['chan'] = $this->config->item('channel')[$channel-1]['name'].'平台';
                }

                if($server==0){
                    $this->data['ser'] = '所有服务器';
                }
                else{
                    $this->data['ser'] = $this->config->item('server')[$server-100]['name'].'服';
                }
                $this->data['start_date'] = $start_date;
                
                $start_date = strtotime($start_date);
				
				
				for($id=1;$id<=10;$id++){
					$sql = "select sum(money) as money,date,moneyid,number from lt_day_pay_struct where date={$start_date} and moneyid={$id} and system={$system} and channel={$channel} and server={$server}";
					$arr = $this->indexM->query_data($sql);
					$arr = $this->indexM->object_array($arr);
					
					if(!empty($arr[0]['money'])){
						$this->data['last_7day_daily_return'][] = $arr;
						
					}else{
						$this->data['last_7day_daily_return'][]=array(0=>array("money"=>0,"date"=>$start_date,"moneyid"=>$id,"number"=>0));
					}
					
				}

                $this->data['system'] = $this->config->item('system');
                $this->data['channel'] = $this->config->item('channel');
                $this->data['server'] = $this->config->item('server');
                $this->data['start_date'] = date("Y-m-d",$start_date);
       
            }
			//echo $sql;
			
			
            $this->load->view('daily_struct_user', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }
 

	
	    public function daily_addpay_user($start_date='', $stop_date='', $server='', $channel='', $system='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
		date_default_timezone_set("Asia/Shanghai");
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['system'] = $this->config->item('system');
            $this->data['channel'] = $this->config->item('channel');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($start_date) && empty($stop_date) && empty($server) && empty($channel) && empty($system)){
				
                $seven_day_before = strtotime("today")-7*24*60*60;
                $today = strtotime("today");
                $this->data['start_date'] = date("Y-m-d",$seven_day_before);
                $this->data['stop_date']  = date("Y-m-d",$today-24*60*60);
                $sql = "select sum(number) as number,date from lt_day_addpay_user where server=0 and channel=0 and system=0 and date>={$seven_day_before} and date<{$today} group by date";
                $this->data['last_7day_daily_return'] = $this->indexM->query_data($sql);
				$this->data['sys'] = '所有系统';
                $this->data['chan'] = '所有平台';
                $this->data['ser'] = '所有服务器';
            }
            else{

				if($system==0){
                    $this->data['sys'] = '所有系统';
                }
                elseif($system==2){
                    $this->data['sys'] = '安卓系统';
                }
                else{
                    $this->data['sys'] = 'IOS系统';
                }

                if($channel==0){
                    $this->data['chan'] = '所有平台';
                }
                else{
                    $this->data['chan'] = $this->config->item('channel')[$channel-1]['name'].'平台';
                }

                if($server==0){
                    $this->data['ser'] = '所有服务器';
                }
                else{
                    $this->data['ser'] = $this->config->item('server')[$server-100]['name'].'服';
                }
				
				
                $start_date = strtotime($start_date);
                $stop_date = strtotime($stop_date)+24*60*60;
				$sql = "select sum(number) as number,date from lt_day_addpay_user where system={$system} and channel={$channel} and server={$server}  and date>={$start_date} and date<{$stop_date} group by date";
				
                $this->data['system'] = $this->config->item('system');
                $this->data['channel'] = $this->config->item('channel');
                $this->data['server'] = $this->config->item('server');
                $this->data['start_date'] = date("Y-m-d H:i:s",$start_date);
                $this->data['stop_date']  = date("Y-m-d H:i:s",$stop_date-24*60*60);
                $this->data['last_7day_daily_return'] = $this->indexM->query_data($sql);
				
				
            }
			
			//echo $sql;
            $this->load->view('daily_addpay_user', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }


	  

		//5分钟在线用户根据服来区分
	
	    public function daily_current_user($start_date='', $server='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
		date_default_timezone_set("Asia/Shanghai");
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];
			$today = strtotime("today");
			$server_arr = $this->config->item('server');
			$now=time();
			$before=$now-300;
			$new_arr=array();
			$num_arr=array();
            
			foreach($server_arr as $v1){
					$server = $v1['number'];
					$ser_name=$v1['name'];
					$number=0;
					$sql="select distinct(game_uid) from lt_online where online_time>={$today} and online_time<{$now}";
					 $this->load->model('game_model_'.$server, 'gameM');
					$login_user_uid_arr = $this->gameM->query_data($sql);
					if(empty($login_user_uid_arr)){
						$number=0;
						$new_arr[]=array("name"=>$ser_name,"number"=>$number);
					}else{
						foreach($login_user_uid_arr as $v){
									$number=$number+1;
						}
						$num_arr[]=$number;
						$new_arr[]=array("name"=>$ser_name,"number"=>$number);
							
					}
			}
			
			$num=array_sum($num_arr);
			$this->data['numd']=$num;
		    $this->data['all_arr'] = $new_arr;

            $this->load->view('daily_current_user', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }


    
	
	public function daily_trend_user($start_date='', $server='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
		date_default_timezone_set("Asia/Shanghai");
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($start_date) && empty($server) && empty($channel) && empty($system)){
				
                $yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
				$today_unix = strtotime(date('Ymd'));
				
                $this->data['start_date'] = date("Y-m-d",$yesterday_unix);

                $start_date = $yesterday_unix;
				$stop_date = $start_date+24*60*60;
			
                $sql = "select sum(number) as number,date from lt_day_trend_user where date>={$start_date} and date<{$stop_date} and server=0 group by date";
                $this->data['last_7day_daily_return'] = $this->indexM->query_data($sql);
				$this->data['ser'] = '所有服务器';
            }
            else{
                $this->data['start_date'] = $start_date;
               
                $start_date = strtotime($start_date);
                $stop_date = $start_date+24*60*60;
				if($server==0){
                    $this->data['ser'] = $this->data['start_date'].'所有服务器充值统计';
                }
                else{
                    $this->data['ser'] = $this->data['start_date'].$this->config->item('server')[$server-100]['name'].'服充值统计';
                }
				$sql = "select sum(number) as number,date from lt_day_trend_user where server={$server} and date>={$start_date} and date<{$stop_date} group by date";
               
                $this->data['system'] = $this->config->item('system');
                $this->data['channel'] = $this->config->item('channel');
                $this->data['server'] = $this->config->item('server');
                $this->data['start_date'] = date("Y-m-d",$start_date);
               
                $this->data['last_7day_daily_return'] = $this->indexM->query_data($sql);

            }
			
			//echo $sql;
			
			
            $this->load->view('daily_trend_user', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }


   


   public function daily_new_user($start_date='', $stop_date='', $server='', $channel='', $system='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['system'] = $this->config->item('system');
            $this->data['channel'] = $this->config->item('channel');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($start_date) && empty($stop_date) && empty($server) && empty($channel) && empty($system)){

                $seven_day_before = strtotime("today")-7*24*60*60;
                $today = strtotime("today");
                $this->data['start_date'] = date("Y-m-d",$seven_day_before);
                $this->data['stop_date']  = date("Y-m-d",$today-24*60*60);
                $sql = "select sum(number) as number,date from lt_day_new_user where server=0 and channel=0 and system=0 and date>={$seven_day_before} and date<{$today} group by date";
                $this->data['last_7day_new_user'] = $this->indexM->query_data($sql);
				 $this->data['sys'] = '所有系统';
                $this->data['chan'] = '所有平台';
                $this->data['ser'] = '所有服务器';
            }
            else{
              
                $this->data['start_date'] = $start_date;
                $this->data['stop_date'] = $stop_date;
                $start_date = strtotime($start_date);
                $stop_date = strtotime($stop_date)+24*60*60;
				$sql = "select sum(number) as number,date from lt_day_new_user where system={$system} and channel={$channel} and server={$server} and date>={$start_date} and date<={$stop_date} group by date";
				if($system==0){
                    $this->data['sys'] = '所有系统';
                }
                elseif($system==2){
                    $this->data['sys'] = '安卓系统';
                }
                else{
                    $this->data['sys'] = 'IOS系统';
                }

                if($channel==0){
                    $this->data['chan'] = '所有平台';
                }
                else{
                    $this->data['chan'] = $this->config->item('channel')[$channel-1]['name'].'平台';
                }

                if($server==0){
                    $this->data['ser'] = '所有服务器';
                }
                else{
                    $this->data['ser'] = $this->config->item('server')[$server-100]['name'].'服';
                }


                $this->data['system'] = $this->config->item('system');
                $this->data['channel'] = $this->config->item('channel');
                $this->data['server'] = $this->config->item('server');
                $this->data['start_date'] = date("Y-m-d H:i:s",$start_date);
                $this->data['stop_date']  = date("Y-m-d H:i:s",$stop_date-24*60*60);
                $this->data['last_7day_new_user'] = $this->indexM->query_data($sql);
            }
			//echo $sql;
            $this->load->view('daily_new_user', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }
	
	
	
	

    public function retention($start_date='', $stop_date='', $server='', $channel='', $system='', $times='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['system'] = $this->config->item('system');
            $this->data['channel'] = $this->config->item('channel');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($start_date) && empty($stop_date) && empty($server) && empty($channel) && empty($system) && empty($time)){
                $seven_day_before = strtotime("today")-8*24*60*60;
                $yesterday_unix = strtotime(date('Ymd',strtotime('-2 day')));
                $today = strtotime("today");
                $this->data['start_date'] = date("Y-m-d",$seven_day_before);
                $this->data['stop_date']  = date("Y-m-d",$today-2*24*60*60);
                $sql = "select retention,date from lt_nextday_retention where server=0 and channel=0 and system=0 and date>={$seven_day_before} and date<={$yesterday_unix} group by date";
                $this->data['last_7day_retention'] = $this->indexM->query_data($sql);
                $this->data['sys'] = '所有系统';
                $this->data['chan'] = '所有平台';
                $this->data['ser'] = '所有服务器';
                $this->data['time'] = '次日留存率';
            }
            else{
                $start_date = strtotime($start_date);
                $stop_date = strtotime($stop_date);
                $table = 'lt_'.$times.'day_retention';
                $sql = "select retention,date from {$table} where system={$system} and channel={$channel} and server={$server} and date>={$start_date} and date<={$stop_date} group by date";
                //echo $sql;

                if($system==0){
                    $this->data['sys'] = '所有系统';
                }
                elseif($system==2){
                    $this->data['sys'] = '安卓系统';
                }
                else{
                    $this->data['sys'] = 'IOS系统';
                }

                if($channel==0){
                    $this->data['chan'] = '所有平台';
                }
                else{
                    $this->data['chan'] = $this->config->item('channel')[$channel-1]['name'].'平台';
                }

                if($server==0){
                    $this->data['ser'] = '所有服务器';
                }
                else{
                    $this->data['ser'] = $this->config->item('server')[$server-100]['name'].'服';
                }

                if($times=='next'){
                    $this->data['time'] = '次日留存率';
                }
                elseif($times=='three'){
                    $this->data['time'] = '三日留存率';
                }
                else{
                    $this->data['time'] = '七日留存率';
                }


                $this->data['system'] = $this->config->item('system');
                $this->data['channel'] = $this->config->item('channel');
                $this->data['server'] = $this->config->item('server');
                $this->data['start_date'] = date("Y-m-d H:i:s",$start_date);
                $this->data['stop_date']  = date("Y-m-d H:i:s",$stop_date);
                $this->data['last_7day_retention'] = $this->indexM->query_data($sql);
            }
            $this->load->view('retention', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }



    //消耗
    public function consume($dates='', $server='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['consume_mode'] = $this->config->item('consume_mode');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($dates) && empty($server)){
                $today = strtotime("today");
                $yesterday_unix = strtotime("today")-24*60*60;
                $yesterday = date("Y-m-d",$today-24*60*60);
                $sql = "select consume_mode, buy_user_num, buy_times, jewel from lt_consume where server=0 and date = {$yesterday_unix}";
                $arr = $this->indexM->query_data($sql);
                foreach($arr as $v1){
                    $consume_arr[] = array('consume_mode'=>$v1->consume_mode, 'buy_user_num'=>$v1->buy_user_num,
                                           'buy_times'=>$v1->buy_times, 'jewel'=>$v1->jewel);
                }
                $this->data['dates'] = $yesterday;
            }
            else{
                $new_dates = strtotime($dates);
                $sql = "select consume_mode, buy_user_num, buy_times, jewel from lt_consume where server={$server} and date = {$new_dates}";
                $arr = $this->indexM->query_data($sql);
                foreach($arr as $v1){
                    $consume_arr[] = array('consume_mode'=>$v1->consume_mode, 'buy_user_num'=>$v1->buy_user_num,
                        'buy_times'=>$v1->buy_times, 'jewel'=>$v1->jewel);
                }
                $this->data['dates'] = $dates;
            }

            $date = $this->data['dates'];
            $consume_mode = $this->data['consume_mode'];

            foreach($consume_mode as $v1){
                if(!empty($consume_arr)){
                    foreach($consume_arr as $v2){
                        if($v1['number']==$v2['consume_mode']){
                            $all_arr[] = array('date'=>$date, 'consume_mode'=>$v1['name'], 'buy_user_num'=>$v2['buy_user_num'],
                                'buy_times'=>$v2['buy_times'], 'jewel'=>$v2['jewel']);
                        }
                    }
                }
                else{
                    $all_arr[] = array('date'=>$date, 'consume_mode'=>'', 'buy_user_num'=>'',
                        'buy_times'=>'', 'jewel'=>'');
                }
            }
            $this->data['all_arr'] = $all_arr;
            $this->load->view('consume', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }



    //奖励发放
    public function reward($dates='', $server='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['consume_mode'] = $this->config->item('consume_mode');
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($dates) && empty($server)){
                $today = strtotime("today");
                $yesterday_unix = strtotime("today")-24*60*60;
                $yesterday = date("Y-m-d",$today-24*60*60);
                $sql = "select login_user,gold,gold_average,jewel,jewel_average,material,material_average from lt_reward where server=0 and date = {$yesterday_unix}";
                $arr = $this->indexM->query_data($sql);
                if(!empty($arr)){
                    foreach($arr as $v1){
                        $reward_arr[] = array('date'=>$dates, 'login_user'=>$v1->login_user, 'gold'=>$v1->gold, 'gold_average'=>$v1->gold_average,
                            'jewel'=>$v1->jewel, 'jewel_average'=>$v1->jewel_average,
                            'material'=>$v1->material, 'material_average'=>$v1->material_average);
                    }
                }
                else{
                    $reward_arr[] = array('date'=>$dates, 'login_user'=>'', 'gold'=>'','gold_average'=>'',
                        'jewel'=>'', 'jewel_average'=>'',
                        'material'=>'', 'material_average'=>'');
                }
                $this->data['dates'] = $yesterday;
            }
            else{
                $new_dates = strtotime($dates);
                $sql = "select login_user,gold,gold_average,jewel,jewel_average,material,material_average from lt_reward where server={$server} and date = {$new_dates}";
                $arr = $this->indexM->query_data($sql);
                if(!empty($arr)){
                    foreach($arr as $v1){
                        $reward_arr[] = array('date'=>$dates, 'login_user'=>$v1->login_user, 'gold'=>$v1->gold, 'gold_average'=>$v1->gold_average,
                            'jewel'=>$v1->jewel, 'jewel_average'=>$v1->jewel_average,
                            'material'=>$v1->material, 'material_average'=>$v1->material_average);
                    }
                }
                else{
                    $reward_arr[] = array('date'=>$dates, 'login_user'=>'', 'gold'=>'','gold_average'=>'',
                        'jewel'=>'', 'jewel_average'=>'',
                        'material'=>'', 'material_average'=>'');
                }
                $this->data['dates'] = $dates;
            }
            $this->data['reward_arr'] = $reward_arr;
            $this->load->view('reward', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }



    //流失等级
    public function loss_level($dates='', $server='')
    {
        header ( "Content-type: text/html; charset=utf-8" );
        if(!empty($this->session->all_userdata()['username'])){
            $this->data['server'] = $this->config->item('server');
            $this->data['side'] = $this->side();
            $this->data['username'] = $this->session->all_userdata()['username'];

            if(empty($dates) && empty($server)){
                $today = strtotime("today");
                $yesterday_unix = strtotime("today")-24*60*60;
                $yesterday = date("Y-m-d",$today-24*60*60);
                $sql = "select loss_level_1,loss_level_2,loss_level_3,loss_level_4,loss_level_5,loss_level_6,loss_level_7,loss_level_8,loss_level_9,loss_level_10,
            loss_level_11,loss_level_12,loss_level_13,loss_level_14,loss_level_15,loss_level_16,loss_level_17,loss_level_18,loss_level_19,loss_level_20,
            loss_level_21,loss_level_22,loss_level_23,loss_level_24,loss_level_25,loss_level_26,loss_level_27,loss_level_28,loss_level_29,loss_level_30,
            loss_level_31,loss_level_32,loss_level_33,loss_level_34,loss_level_35,loss_level_36,loss_level_37,loss_level_38,loss_level_39,loss_level_40,
            loss_level_41,loss_level_42,loss_level_43,loss_level_44,loss_level_45,loss_level_46,loss_level_47,loss_level_48,loss_level_49,loss_level_50
            from lt_loss_level where server=0 and date={$yesterday_unix}";
                $level_arr = $this->indexM->query_data($sql);

                $this->data['dates'] = $yesterday;
            }
            else{
                $new_dates = strtotime($dates);
                $sql = "select loss_level_1,loss_level_2,loss_level_3,loss_level_4,loss_level_5,loss_level_6,loss_level_7,loss_level_8,loss_level_9,loss_level_10,
            loss_level_11,loss_level_12,loss_level_13,loss_level_14,loss_level_15,loss_level_16,loss_level_17,loss_level_18,loss_level_19,loss_level_20,
            loss_level_21,loss_level_22,loss_level_23,loss_level_24,loss_level_25,loss_level_26,loss_level_27,loss_level_28,loss_level_29,loss_level_30,
            loss_level_31,loss_level_32,loss_level_33,loss_level_34,loss_level_35,loss_level_36,loss_level_37,loss_level_38,loss_level_39,loss_level_40,
            loss_level_41,loss_level_42,loss_level_43,loss_level_44,loss_level_45,loss_level_46,loss_level_47,loss_level_48,loss_level_49,loss_level_50
            from lt_loss_level where server={$server} and date={$new_dates}";
                $level_arr = $this->indexM->query_data($sql);
                $this->data['dates'] = $dates;
            }

            $level_arr = $this->object_array($level_arr);
            foreach($level_arr as $values){
                for($i=1;$i<=50;$i++){
                    $string = 'loss_level_'.$i;
                    $new_level_arr[$i] = $values[$string];
                }
            }

            if($server==0){
                $this->data['ser'] = '所有服务器';
            }
            else{
                $this->data['ser'] = $this->config->item('server')[$server-100]['name'].'服';
            }
            if(!empty($new_level_arr)){
                $this->data['level_arr'] = $new_level_arr;
            }
            else{
                $this->data['level_arr'] = '';
            }

            $this->load->view('loss_level', $this->data);
        }
        else{
            $this->data['alert'] = '请输入您的用户名和密码';
            $this->load->view('login', $this->data);
        }
    }



    public function side()
    {
		$index = site_url('gindex/index');
        $daily_return = site_url('gindex/daily_return');
        $daily_return_user = site_url('gindex/daily_return_user');
		$daily_trend_user = site_url('gindex/daily_trend_user');
		$daily_addpay_user = site_url('gindex/daily_addpay_user');
        $daily_new_user = site_url('gindex/daily_new_user');
		$month_return = site_url('gindex/month_return');
		$month_return_user = site_url('gindex/month_return_user');
		$daily_struct_user = site_url('gindex/daily_struct_user');
		$dayily_multiple = site_url('gindex/dayily_multiple');
        $retention = site_url('gindex/retention');
        $consume = site_url('gindex/consume');
        $reward = site_url('gindex/reward');
        $loss_level = site_url('gindex/loss_level');
		$daily_max_online_user = site_url('gindex/daily_max_online_user');
		$daily_average_online_user = site_url('gindex/daily_average_online_user');
		$daily_online_trend = site_url('gindex/daily_online_trend');
		$daily_current_user = site_url('gindex/daily_current_user');
		$dayily_pc_user = site_url('gindex/dayily_pc_user');
		
		
        return <<<EOT
    <div class="col-sm-2 col-lg-2">
    <div class="sidebar-nav">
        <div class="nav-canvas">
            <div class="nav-sm nav nav-stacked">

            </div>
            <ul class="nav nav-pills nav-stacked main-menu">
                <li><a class="ajax-link" href="{$index}"><i class="glyphicon glyphicon-home"></i><span> 首页</span></a></li>
                <li><a class="ajax-link" href="{$daily_return}"><i class="glyphicon glyphicon-list-alt"></i><span> 日收益</span></a></li>
                <li><a class="ajax-link" href="{$daily_return_user}"><i class="glyphicon glyphicon-list-alt"></i><span> 日充值用户</span></a></li>
                <li><a class="ajax-link" href="{$daily_trend_user}"><i class="glyphicon glyphicon-list-alt"></i><span> 玩家充值走势</span></a></li>
                <li><a class="ajax-link" href="{$daily_addpay_user}"><i class="glyphicon glyphicon-list-alt"></i><span> 新增充值用户</span></a></li>
                <li><a class="ajax-link" href="{$daily_new_user}"><i class="glyphicon glyphicon-list-alt"></i><span> 日新增用户</span></a></li>
                <li><a class="ajax-link" href="{$retention}"><i class="glyphicon glyphicon-list-alt"></i><span> 用户留存率</span></a></li>
                <li><a class="ajax-link" href="{$dayily_multiple}"><i class="glyphicon glyphicon-list-alt"></i><span> 日综合统计</span></a></li>
                <li><a class="ajax-link" href="{$month_return}"><i class="glyphicon glyphicon-list-alt"></i><span> 月收益</span></a></li>
                <li><a class="ajax-link" href="{$month_return_user}"><i class="glyphicon glyphicon-list-alt"></i><span> 月新增用户</span></a></li>
                <li><a class="ajax-link" href="{$consume}"><i class="glyphicon glyphicon-list-alt"></i><span> 游戏消耗</span></a></li>
                <li><a class="ajax-link" href="{$reward}"><i class="glyphicon glyphicon-list-alt"></i><span> 奖励发放</span></a></li>
                <li><a class="ajax-link" href="{$loss_level}"><i class="glyphicon glyphicon-list-alt"></i><span> 流失等级</span></a></li>
                <li><a class="ajax-link" href="{$daily_struct_user}"><i class="glyphicon glyphicon-list-alt"></i><span> 充值结构</span></a></li>
                <li><a class="ajax-link" href="{$daily_average_online_user}"><i class="glyphicon glyphicon-list-alt"></i><span> 平均在线用户</span></a></li>
                <li><a class="ajax-link" href="{$daily_max_online_user}"><i class="glyphicon glyphicon-list-alt"></i><span> 最高在线用户</span></a></li>
                <li><a class="ajax-link" href="{$daily_online_trend}"><i class="glyphicon glyphicon-list-alt"></i><span> 玩家在线走势</span></a></li>
                <li><a class="ajax-link" href="{$dayily_pc_user}"><i class="glyphicon glyphicon-list-alt"></i><span> ACU和PCU</span></a></li>
                <li><a class="ajax-link" href="{$daily_current_user}"><i class="glyphicon glyphicon-list-alt"></i><span> 当前玩家</span></a></li>
            </ul>
        </div>
    </div>
</div>
EOT;
    }

   

   private function object_array($array){
        if(is_object($array)){
            $array = (array)$array;
        }
        if(is_array($array)){
            foreach($array as $key=>$value){
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }
}
