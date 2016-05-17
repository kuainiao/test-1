<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Command extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('account_model', 'accountM');
        $this->load->model('pay_model', 'payM');
        $this->load->model('gindex_model', 'indexM');
    }

	public function test(){
		 //按系统按服
		 date_default_timezone_set("Asia/Shanghai");
		$yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));

        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');
		foreach($system_arr as $v1){
			$system = $v1['number'];
			$num_arr=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					$table = 'lt_account';
					//echo $system.','.$channel.','.$server.','.$table.'===>';
					 $sql = "select count(id) as number from {$table} where system={$system} and area={$server} and create_time>={$yesterday_unix} and channel={$channel} and create_time<{$today_unix}";
					 echo $sql.'<hr/>';
					 $num_arrt = $this->accountM->query_data($sql);
					 var_dump($num_arrt);
					 foreach($num_arrt as $v){
						$num_arr[] = $v->number;
					 }
					 if(empty($num_arrt)){
						$num_arr[] = 0;
					 }
				}
				
			}
			var_dump($num_arr);
			$now = time();
			$numbe=array_sum($num_arr);
			//$sql = "insert into lt_day_new_user values ('',{$system},0, 0, {$numbe}, {$yesterday_unix}, {$now})";
			//$in = $this->indexM->query_sql($sql);
		}
		
		
	}
	
	//日充值用户 
	 public function day_pay_user()
    {
        date_default_timezone_set("Asia/Shanghai");
		$yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));

        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');
		// 按系统按渠道按服
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                $table = $v2['table'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
                
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select count(distinct game_uid) as number  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					
					$user_arr = $this->payM->query_data($sql);
					var_dump($user_arr);
					foreach($user_arr as $v){
						$number = $v->number;
					}
					if(empty($user_arr)){
						$number = 0;
					}
					
					$now = time();
					$sql = "insert into lt_day_pay_user values ('', {$system}, {$channel}, {$server}, {$number}, {$yesterday_unix}, {$now})";
					$in = $this->indexM->query_sql($sql);
				}
            }
			
			
			
        }
		//按系统按服
		foreach($system_arr as $v1){
			$system = $v1['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				foreach($channel_arr as $v2){
					$table = $v2['table'];
					$channel = $v2['number'];
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select count(distinct game_uid) as number  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					
					$user_arr = $this->payM->query_data($sql);
					foreach($user_arr as $v){
						$num_arr[]=$v->number;
					}
					if(empty($user_arr)){
						$num_arr[]=0;
					}
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_pay_user values ('', {$system}, 0, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//按渠道按服
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				foreach($system_arr as $v1){
					$system = $v1['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select count(distinct game_uid) as number  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					
					$user_arr = $this->payM->query_data($sql);
					foreach($user_arr as $v){
						$num_arr[]=$v->number;
					}
					if(empty($user_arr)){
						$num_arr[]=0;
					}
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_pay_user values ('', 0, {$channel}, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		//按系统按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				$num_arr=array();
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select count(distinct game_uid) as number  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					
					$user_arr = $this->payM->query_data($sql);
					foreach($user_arr as $v){
						$num_arr[]=$v->number;
					}
					if(empty($user_arr)){
						$num_arr[]=0;
					}
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_pay_user values ('', {$system},{$channel}, 0, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			$num_arr=array();
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select count(distinct game_uid) as number  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					
					$user_arr = $this->payM->query_data($sql);
					foreach($user_arr as $v){
						$num_arr[]=$v->number;
					}
					if(empty($user_arr)){
						$num_arr[]=0;
					}
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_pay_user values ('',0,{$channel}, 0, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		//按系统
		foreach($system_arr as $v1){
			$system = $v1['number'];
			$num_arr=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select count(distinct game_uid) as number  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					
					$user_arr = $this->payM->query_data($sql);
					foreach($user_arr as $v){
						$num_arr[]=$v->number;
					}
					if(empty($user_arr)){
						$num_arr[]=0;
					}
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_pay_user values ('',{$system},0, 0, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		//按服
		foreach($server_arr as $v3){
			$server = $v3['number'];
			$num_arr=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($system_arr as $v1){
					$system = $v1['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select count(distinct game_uid) as number  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					
					$user_arr = $this->payM->query_data($sql);
					foreach($user_arr as $v){
						$num_arr[]=$v->number;
					}
					if(empty($user_arr)){
						$num_arr[]=0;
					}
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_pay_user values ('',0,0, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		
		//所有的出来
		$num_arr=array();
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select count(distinct game_uid) as number  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					
					$user_arr = $this->payM->query_data($sql);
					foreach($user_arr as $v){
						
						$num_arr[]=$v->number;
					}
					if(empty($user_arr)){
						
						$num_arr[]=0;
					}
				}
				
			}
			
			
		}
		$now = time();
		$numbe=array_sum($num_arr);
		$sql = "insert into lt_day_pay_user values ('',0,0, 0, {$numbe}, {$yesterday_unix}, {$now})";
		$in = $this->indexM->query_sql($sql);
       
    }
	
	
	
		
	
		
	//日综合统计 ,要在最后执行一定要
	 public function day_multiple_user()
    {
        date_default_timezone_set("Asia/Shanghai");
		$yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));

        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');
		// 按系统按渠道按服
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                $table = $v2['table'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
                
					echo $system.','.$channel.','.$server.','.$table.'===>';
					//新增用户
					 $sql = "select sum(number) as number from lt_day_new_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$number_1=$arr[0]->number;
							
					}else{
						$number_1=0;
					}
					//日充值用户
					$sql = "select sum(number) as number from lt_day_pay_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$number_2=$arr[0]->number;
							
					}else{
						$number_2=0;
					}
					//活跃用户 
					$sql = "select sum(number) as number from lt_day_active_user where system={$system} and   channel={$channel} and server={$server} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$number_3=$arr[0]->number;
							
					}else{
						$number_3=0;
					}
					//排重用户
					$sql = "select sum(number) as number from lt_day_over_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$number_4=$arr[0]->number;
							
					}else{
						$number_4=0;
					}
					//日收益总
					$sql = "select sum(money) as money from lt_day_pay where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->money)){
						$money=$arr[0]->money;
							
					}else{
						$money=0;
					}
					$now = time();
					$sql = "insert into lt_day_multiple values ('', {$system}, {$channel}, {$server}, {$number_1}, {$number_2}, {$number_3}, {$number_4},{$money}, {$yesterday_unix}, {$now})";
					$in = $this->indexM->query_sql($sql);
				
				}
            }

        }
		//按系统按服
		foreach($system_arr as $v1){
			$system = $v1['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				$num_a=array();
				$num_b=array();
				$num_c=array();
				$num_d=array();
				$money_a=array();
				foreach($channel_arr as $v2){
					$table = $v2['table'];
					$channel = $v2['number'];
					echo $system.','.$channel.','.$server.','.$table.'===>';
					 $sql = "select sum(number) as number from lt_day_new_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_a[]=$arr[0]->number;
							
					}else{
						$num_a[]=0;
					}
					//日充值用户
					$sql = "select sum(number) as number from lt_day_pay_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_b[]=$arr[0]->number;
							
					}else{
						$num_b[]=0;
					}
					//活跃用户 
					$sql = "select sum(number) as number from lt_day_active_user where system={$system} and   channel={$channel} and server={$server} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_c[]=$arr[0]->number;
							
					}else{
						$num_c[]=0;
					}
					//排重用户
					$sql = "select sum(number) as number from lt_day_over_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_d[]=$arr[0]->number;
							
					}else{
						$num_d[]=0;
					}
					//日收益总
					$sql = "select sum(money) as money from lt_day_pay where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->money)){
						$money_a[]=$arr[0]->money;
							
					}else{
						$money_a[]=0;
					}
				}
				$now = time();
				$number_1=array_sum($num_a);
				$number_2=array_sum($num_b);
				$number_3=array_sum($num_c);
				$number_4=array_sum($num_d);
				$money=array_sum($money_a);
				$sql = "insert into lt_day_multiple values ('', {$system}, 0, {$server}, {$number_1}, {$number_2}, {$number_3}, {$number_4},{$money}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//按渠道按服
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				$num_a=array();
				$num_b=array();
				$num_c=array();
				$num_d=array();
				$money_a=array();
				foreach($system_arr as $v1){
					$system = $v1['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(number) as number from lt_day_new_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_a[]=$arr[0]->number;
							
					}else{
						$num_a[]=0;
					}
					//日充值用户
					$sql = "select sum(number) as number from lt_day_pay_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_b[]=$arr[0]->number;
							
					}else{
						$num_b[]=0;
					}
					//活跃用户 
					$sql = "select sum(number) as number from lt_day_active_user where system={$system} and   channel={$channel} and server={$server} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_c[]=$arr[0]->number;
							
					}else{
						$num_c[]=0;
					}
					//排重用户
					$sql = "select sum(number) as number from lt_day_over_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_d[]=$arr[0]->number;
							
					}else{
						$num_d[]=0;
					}
					//日收益总
					$sql = "select sum(money) as money from lt_day_pay where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->money)){
						$money_a[]=$arr[0]->money;
							
					}else{
						$money_a[]=0;
					}
				}
				$now = time();
				$number_1=array_sum($num_a);
				$number_2=array_sum($num_b);
				$number_3=array_sum($num_c);
				$number_4=array_sum($num_d);
				$money=array_sum($money_a);
				$sql = "insert into lt_day_multiple values ('',0, {$channel}, {$server}, {$number_1}, {$number_2}, {$number_3}, {$number_4},{$money}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
				
			}
			
		}
		
		//按系统按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				$num_arr=array();
				$num_a=array();
				$num_b=array();
				$num_c=array();
				$num_d=array();
				$money_a=array();
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(number) as number from lt_day_new_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_a[]=$arr[0]->number;
							
					}else{
						$num_a[]=0;
					}
					//日充值用户
					$sql = "select sum(number) as number from lt_day_pay_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_b[]=$arr[0]->number;
							
					}else{
						$num_b[]=0;
					}
					//活跃用户 
					$sql = "select sum(number) as number from lt_day_active_user where system={$system} and   channel={$channel} and server={$server} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_c[]=$arr[0]->number;
							
					}else{
						$num_c[]=0;
					}
					//排重用户
					$sql = "select sum(number) as number from lt_day_over_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_d[]=$arr[0]->number;
							
					}else{
						$num_d[]=0;
					}
					//日收益总
					$sql = "select sum(money) as money from lt_day_pay where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->money)){
						$money_a[]=$arr[0]->money;
							
					}else{
						$money_a[]=0;
					}
				}
				$now = time();
				$number_1=array_sum($num_a);
				$number_2=array_sum($num_b);
				$number_3=array_sum($num_c);
				$number_4=array_sum($num_d);
				$money=array_sum($money_a);
				$sql = "insert into lt_day_multiple values ('',{$system}, {$channel},0, {$number_1}, {$number_2}, {$number_3}, {$number_4},{$money}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
				
			}
			
		}
		
		
		//按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			$num_arr=array();
			$num_a=array();
			$num_b=array();
			$num_c=array();
			$num_d=array();
			$money_a=array();
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(number) as number from lt_day_new_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_a[]=$arr[0]->number;
							
					}else{
						$num_a[]=0;
					}
					//日充值用户
					$sql = "select sum(number) as number from lt_day_pay_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_b[]=$arr[0]->number;
							
					}else{
						$num_b[]=0;
					}
					//活跃用户 
					$sql = "select sum(number) as number from lt_day_active_user where system={$system} and   channel={$channel} and server={$server} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_c[]=$arr[0]->number;
							
					}else{
						$num_c[]=0;
					}
					//排重用户
					$sql = "select sum(number) as number from lt_day_over_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_d[]=$arr[0]->number;
							
					}else{
						$num_d[]=0;
					}
					//日收益总
					$sql = "select sum(money) as money from lt_day_pay where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->money)){
						$money_a[]=$arr[0]->money;
							
					}else{
						$money_a[]=0;
					}
				}
				
			}
			$now = time();
			$number_1=array_sum($num_a);
			$number_2=array_sum($num_b);
			$number_3=array_sum($num_c);
			$number_4=array_sum($num_d);
			$money=array_sum($money_a);
			$sql = "insert into lt_day_multiple values ('',0, {$channel},0, {$number_1}, {$number_2}, {$number_3}, {$number_4},{$money}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		//按系统
		foreach($system_arr as $v1){
			$system = $v1['number'];
			$num_arr=array();
			$num_a=array();
			$num_b=array();
			$num_c=array();
			$num_d=array();
			$money_a=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(number) as number from lt_day_new_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_a[]=$arr[0]->number;
							
					}else{
						$num_a[]=0;
					}
					//日充值用户
					$sql = "select sum(number) as number from lt_day_pay_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_b[]=$arr[0]->number;
							
					}else{
						$num_b[]=0;
					}
					//活跃用户 
					$sql = "select sum(number) as number from lt_day_active_user where system={$system} and   channel={$channel} and server={$server} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_c[]=$arr[0]->number;
							
					}else{
						$num_c[]=0;
					}
					//排重用户
					$sql = "select sum(number) as number from lt_day_over_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_d[]=$arr[0]->number;
							
					}else{
						$num_d[]=0;
					}
					//日收益总
					$sql = "select sum(money) as money from lt_day_pay where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->money)){
						$money_a[]=$arr[0]->money;
							
					}else{
						$money_a[]=0;
					}
				}
				
			}
			$now = time();
			$number_1=array_sum($num_a);
			$number_2=array_sum($num_b);
			$number_3=array_sum($num_c);
			$number_4=array_sum($num_d);
			$money=array_sum($money_a);
			$sql = "insert into lt_day_multiple values ('',{$system},0,0, {$number_1}, {$number_2}, {$number_3}, {$number_4},{$money}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
		
			
		}
		
		//按服
		foreach($server_arr as $v3){
			$server = $v3['number'];
			$num_arr=array();
			$num_a=array();
			$num_b=array();
			$num_c=array();
			$num_d=array();
			$money_a=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($system_arr as $v1){
					$system = $v1['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(number) as number from lt_day_new_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_a[]=$arr[0]->number;
							
					}else{
						$num_a[]=0;
					}
					//日充值用户
					$sql = "select sum(number) as number from lt_day_pay_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_b[]=$arr[0]->number;
							
					}else{
						$num_b[]=0;
					}
					//活跃用户 
					$sql = "select sum(number) as number from lt_day_active_user where system={$system} and   channel={$channel} and server={$server} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_c[]=$arr[0]->number;
							
					}else{
						$num_c[]=0;
					}
					//排重用户
					$sql = "select sum(number) as number from lt_day_over_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_d[]=$arr[0]->number;
							
					}else{
						$num_d[]=0;
					}
					//日收益总
					$sql = "select sum(money) as money from lt_day_pay where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->money)){
						$money_a[]=$arr[0]->money;
							
					}else{
						$money_a[]=0;
					}
				}
				
			}
			$now = time();
			$number_1=array_sum($num_a);
			$number_2=array_sum($num_b);
			$number_3=array_sum($num_c);
			$number_4=array_sum($num_d);
			$money=array_sum($money_a);
			$sql = "insert into lt_day_multiple values ('',0,0,{$server}, {$number_1}, {$number_2}, {$number_3}, {$number_4},{$money}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		
		//所有的出来
		$num_arr=array();
		$num_a=array();
		$num_b=array();
		$num_c=array();
		$num_d=array();
		$money_a=array();
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(number) as number from lt_day_new_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_a[]=$arr[0]->number;
							
					}else{
						$num_a[]=0;
					}
					//日充值用户
					$sql = "select sum(number) as number from lt_day_pay_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_b[]=$arr[0]->number;
							
					}else{
						$num_b[]=0;
					}
					//活跃用户 
					$sql = "select sum(number) as number from lt_day_active_user where system={$system} and   channel={$channel} and server={$server} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_c[]=$arr[0]->number;
							
					}else{
						$num_c[]=0;
					}
					//排重用户
					$sql = "select sum(number) as number from lt_day_over_user where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$num_d[]=$arr[0]->number;
							
					}else{
						$num_d[]=0;
					}
					//日收益总
					$sql = "select sum(money) as money from lt_day_pay where system={$system} and server={$server}  and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->money)){
						$money_a[]=$arr[0]->money;
							
					}else{
						$money_a[]=0;
					}
				}
				
			}
			
			
		}
		$now = time();
		$number_1=array_sum($num_a);
		$number_2=array_sum($num_b);
		$number_3=array_sum($num_c);
		$number_4=array_sum($num_d);
		$money=array_sum($money_a);
		$sql = "insert into lt_day_multiple values ('',0,0,0, {$number_1}, {$number_2}, {$number_3}, {$number_4},{$money}, {$yesterday_unix}, {$now})";
		$in = $this->indexM->query_sql($sql);
       
    }
	
	
	
	//日新增用户 
	 public function day_new_user()
    {
        date_default_timezone_set("Asia/Shanghai");
		$yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));

        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');
		// 按系统按渠道按服
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                $table = $v2['table'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
					$table = 'lt_account';
					echo $system.','.$channel.','.$server.','.$table.'===>';
					 $sql = "select count(id) as number from {$table} where system={$system} and area={$server}  and create_time>={$yesterday_unix} and channel={$channel} and create_time<{$today_unix}";
					 $num_arr = $this->accountM->query_data($sql);
					 foreach($num_arr as $v){
						$number = $v->number;
					 }
					 if(empty($num_arr)){
						$number = 0;
					 }
					
					$now = time();
					$sql = "insert into lt_day_new_user values ('', {$system}, {$channel}, {$server}, {$number}, {$yesterday_unix}, {$now})";
					$in = $this->indexM->query_sql($sql);
				}
            }
			
			
			
        }
		
	
		//按系统按服
		foreach($system_arr as $v1){
			$system = $v1['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				foreach($channel_arr as $v2){
					$table = $v2['table'];
					$channel = $v2['number'];
					$table = 'lt_account';
					echo $system.','.$channel.','.$server.','.$table.'===>';
					 $sql = "select count(id) as number from {$table} where system={$system} and area={$server} and create_time>={$yesterday_unix} and channel={$channel} and create_time<{$today_unix}";
					 $num_arrt = $this->accountM->query_data($sql);
					 foreach($num_arrt as $v){
						$num_arr[] = $v->number;
					 }
					 if(empty($num_arrt)){
						$num_arr[] = 0;
					 }
					
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_new_user values ('', {$system}, 0, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//按渠道按服
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				foreach($system_arr as $v1){
					$system = $v1['number'];
					$table = 'lt_account';
					echo $system.','.$channel.','.$server.','.$table.'===>';
					 $sql = "select count(id) as number from {$table} where system={$system} and area={$server} and create_time>={$yesterday_unix} and channel={$channel} and create_time<{$today_unix}";
					 $num_arrt = $this->accountM->query_data($sql);
					 foreach($num_arrt as $v){
						$num_arr[] = $v->number;
					 }
					 if(empty($num_arrt)){
						$num_arr[] = 0;
					 }
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_new_user values ('', 0, {$channel}, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		//按系统按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				$num_arr=array();
				foreach($server_arr as $v3){
					$server = $v3['number'];
					$table = 'lt_account';
					echo $system.','.$channel.','.$server.','.$table.'===>';
					 $sql = "select count(id) as number from {$table} where system={$system} and area={$server} and create_time>={$yesterday_unix} and channel={$channel} and create_time<{$today_unix}";
					 $num_arrt = $this->accountM->query_data($sql);
					 foreach($num_arrt as $v){
						$num_arr[] = $v->number;
					 }
					 if(empty($num_arrt)){
						$num_arr[] = 0;
					 }
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_new_user values ('', {$system},{$channel}, 0, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			$num_arr=array();
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					$table = 'lt_account';
					echo $system.','.$channel.','.$server.','.$table.'===>';
					 $sql = "select count(id) as number from {$table} where system={$system} and area={$server} and create_time>={$yesterday_unix} and channel={$channel} and create_time<{$today_unix}";
					 $num_arrt = $this->accountM->query_data($sql);
					 foreach($num_arrt as $v){
						$num_arr[] = $v->number;
					 }
					 if(empty($num_arrt)){
						$num_arr[] = 0;
					 }
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_new_user values ('',0,{$channel}, 0, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		//按系统
		foreach($system_arr as $v1){
			$system = $v1['number'];
			$num_arr=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					$table = 'lt_account';
					echo $system.','.$channel.','.$server.','.$table.'===>';
					 $sql = "select count(id) as number from {$table} where system={$system} and area={$server} and create_time>={$yesterday_unix} and channel={$channel} and create_time<{$today_unix}";
					 $num_arrt = $this->accountM->query_data($sql);
					 foreach($num_arrt as $v){
						$num_arr[] = $v->number;
					 }
					 if(empty($num_arrt)){
						$num_arr[] = 0;
					 }
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_new_user values ('',{$system},0, 0, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		//按服
		foreach($server_arr as $v3){
			$server = $v3['number'];
			$num_arr=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($system_arr as $v1){
					$system = $v1['number'];
					$table = 'lt_account';
					echo $system.','.$channel.','.$server.','.$table.'===>';
					 $sql = "select count(id) as number from {$table} where system={$system} and area={$server} and create_time>={$yesterday_unix} and channel={$channel} and create_time<{$today_unix}";
					 $num_arrt = $this->accountM->query_data($sql);
					 foreach($num_arrt as $v){
						$num_arr[] = $v->number;
					 }
					 if(empty($num_arrt)){
						$num_arr[] = 0;
					 }
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_new_user values ('',0,0, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		
		//所有的出来
		$num_arr=array();
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					$table = 'lt_account';
					echo $system.','.$channel.','.$server.','.$table.'===>';
					 $sql = "select count(id) as number from {$table} where system={$system} and area={$server} and create_time>={$yesterday_unix} and channel={$channel} and create_time<{$today_unix}";
					 $num_arrt = $this->accountM->query_data($sql);
					 foreach($num_arrt as $v){
						$num_arr[] = $v->number;
					 }
					 if(empty($num_arrt)){
						$num_arr[] = 0;
					 }
				}
				
			}
			
			
		}
		$now = time();
		$numbe=array_sum($num_arr);
		$sql = "insert into lt_day_new_user values ('',0,0, 0, {$numbe}, {$yesterday_unix}, {$now})";
		$in = $this->indexM->query_sql($sql);
       
    }
	
	

	
			
		//排重用户 
	 public function day_over_user()
    {
        date_default_timezone_set("Asia/Shanghai");
		$yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));

        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');

		// 按系统按渠道按服
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                $table = $v2['table'];
				
                foreach($server_arr as $v3){
                    $server = $v3['number'];
					$number = 0;
					echo $system.','.$channel.','.$server.','.$table.'===>';
					//有哪些符合
					$sql="select game_uid from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
					$this->load->model('game_model_'.$server, 'gameM');
					$login_user_uid_arr = $this->gameM->query_data($sql);
				
					$new_arr = array();
					foreach($login_user_uid_arr as $v){
						$new_arr[]=$v->game_uid;
					}
					$count_arr=array_count_values($new_arr);
					
					
					foreach($count_arr as $k =>$v){
							if($v>1){
								$table = 'lt_account';
								$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$k}";
								
								$acc_arr = $this->accountM->query_data($sql);
								if(empty($acc_arr)){
									break;
								}
								else{
									$number=$number+1;
								}
								
							}
					}
					
				
					$now = time();
					$sql = "insert into lt_day_over_user values ('', {$system}, {$channel}, {$server}, {$number}, {$yesterday_unix}, {$now})";
					$in = $this->indexM->query_sql($sql);
				}
            }
			
			
			
        }
		
	
		//按系统按服
		foreach($system_arr as $v1){
			$system = $v1['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				foreach($channel_arr as $v2){
					$table = $v2['table'];
					$channel = $v2['number'];
					$number=0;
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql="select game_uid from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
					$this->load->model('game_model_'.$server, 'gameM');
					$login_user_uid_arr = $this->gameM->query_data($sql);
					$new_arr = array();
					foreach($login_user_uid_arr as $v){
						$new_arr[]=$v->game_uid;
					}
					$count_arr=array_count_values($new_arr);
					
					
					foreach($count_arr as $k =>$v){
							if($v>1){
								$table = 'lt_account';
								$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$k}";
								
								$acc_arr = $this->accountM->query_data($sql);
								if(empty($acc_arr)){
									break;
								}
								else{
									$number=$number+1;
								}
								
							}
					}
					$num_arr[] = $number;
					
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_over_user values ('', {$system}, 0, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//按渠道按服
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				foreach($system_arr as $v1){
					$system = $v1['number'];
					$number=0;
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql="select game_uid from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
					$this->load->model('game_model_'.$server, 'gameM');
					$login_user_uid_arr = $this->gameM->query_data($sql);
					$new_arr = array();
					foreach($login_user_uid_arr as $v){
						$new_arr[]=$v->game_uid;
					}
					$count_arr=array_count_values($new_arr);
					foreach($count_arr as $k =>$v){
							if($v>1){
								$table = 'lt_account';
								$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$k}";
								
								$acc_arr = $this->accountM->query_data($sql);
								if(empty($acc_arr)){
									break;
								}
								else{
									$number=$number+1;
								}
								
							}
					}
					$num_arr[] = $number;
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_over_user values ('', 0, {$channel}, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		//按系统按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				$num_arr=array();
				foreach($server_arr as $v3){
					$server = $v3['number'];
					$number=0;
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql="select game_uid from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
					$this->load->model('game_model_'.$server, 'gameM');
					$login_user_uid_arr = $this->gameM->query_data($sql);
					$new_arr = array();
					foreach($login_user_uid_arr as $v){
						$new_arr[]=$v->game_uid;
					}
					$count_arr=array_count_values($new_arr);
					foreach($count_arr as $k =>$v){
							if($v>1){
								$table = 'lt_account';
								$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$k}";
								
								$acc_arr = $this->accountM->query_data($sql);
								if(empty($acc_arr)){
									break;
								}
								else{
									$number=$number+1;
								}
								
							}
					}
					$num_arr[] = $number;
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_over_user values ('', {$system},{$channel}, 0, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			$num_arr=array();
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					$number=0;
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql="select game_uid from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
					$this->load->model('game_model_'.$server, 'gameM');
					$login_user_uid_arr = $this->gameM->query_data($sql);
					$new_arr = array();
					foreach($login_user_uid_arr as $v){
						$new_arr[]=$v->game_uid;
					}
					$count_arr=array_count_values($new_arr);
					foreach($count_arr as $k =>$v){
							if($v>1){
								$table = 'lt_account';
								$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$k}";
								
								$acc_arr = $this->accountM->query_data($sql);
								if(empty($acc_arr)){
									break;
								}
								else{
									$number=$number+1;
								}
								
							}
					}
					$num_arr[] = $number;
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_over_user values ('',0,{$channel}, 0, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		//按系统
		foreach($system_arr as $v1){
			$system = $v1['number'];
			$num_arr=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					$number=0;
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql="select game_uid from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
					$this->load->model('game_model_'.$server, 'gameM');
					$login_user_uid_arr = $this->gameM->query_data($sql);
					$new_arr = array();
					foreach($login_user_uid_arr as $v){
						$new_arr[]=$v->game_uid;
					}
					$count_arr=array_count_values($new_arr);
					foreach($count_arr as $k =>$v){
							if($v>1){
								$table = 'lt_account';
								$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$k}";
								
								$acc_arr = $this->accountM->query_data($sql);
								if(empty($acc_arr)){
									break;
								}
								else{
									$number=$number+1;
								}
								
							}
					}
					$num_arr[] = $number;
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_over_user values ('',{$system},0, 0, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		//按服
		foreach($server_arr as $v3){
			$server = $v3['number'];
			$num_arr=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($system_arr as $v1){
					$system = $v1['number'];
					$number=0;
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql="select game_uid from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
					$this->load->model('game_model_'.$server, 'gameM');
					$login_user_uid_arr = $this->gameM->query_data($sql);
					$new_arr = array();
					foreach($login_user_uid_arr as $v){
						$new_arr[]=$v->game_uid;
					}
					$count_arr=array_count_values($new_arr);
					foreach($count_arr as $k =>$v){
							if($v>1){
								$table = 'lt_account';
								$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$k}";
								
								$acc_arr = $this->accountM->query_data($sql);
								if(empty($acc_arr)){
									break;
								}
								else{
									$number=$number+1;
								}
								
							}
					}
					$num_arr[] = $number;
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_over_user values ('',0,0, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		
		//所有的出来
		$num_arr=array();
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					$number=0;
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql="select game_uid from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
					$this->load->model('game_model_'.$server, 'gameM');
					$login_user_uid_arr = $this->gameM->query_data($sql);
					$new_arr = array();
					foreach($login_user_uid_arr as $v){
						$new_arr[]=$v->game_uid;
					}
					$count_arr=array_count_values($new_arr);
					foreach($count_arr as $k =>$v){
							if($v>1){
								$table = 'lt_account';
								$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$k}";
								
								$acc_arr = $this->accountM->query_data($sql);
								if(empty($acc_arr)){
									break;
								}
								else{
									$number=$number+1;
								}
								
							}
					}
					$num_arr[] = $number;
				}
				
			}
			
			
		}
		$now = time();
		$numbe=array_sum($num_arr);
		$sql = "insert into lt_day_over_user values ('',0,0, 0, {$numbe}, {$yesterday_unix}, {$now})";
		$in = $this->indexM->query_sql($sql);
	  
    }
	
	

	
	
	
	//日活跃用户在日新增后执行 
	 public function day_active_user()
    {
        date_default_timezone_set("Asia/Shanghai");
		$yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));

        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');
		// 按系统按渠道按服
		
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                $table = $v2['table'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
					$number_1 = 0;
					$number_2 = 0;
					
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
					$login_user_uid_arr = $this->gameM->object_array($login_user_uid_arr);

					if(empty($login_user_uid_arr))
						$number_1=0;
					else {
						foreach($login_user_uid_arr as $v){
							foreach($v as $k1 =>$v1){
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$v1}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										break;
									}
									else{
										$number_1=$number_1+1;
									}
									
								
							}
							
						}
					}

					$table='lt_day_new_user';
					$sql = "select sum(number) as number from {$table} where system={$system} and server={$server} and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$number_2=$arr[0]->number;
							
					}else{
						$number_2=0;
					}
					$number=$number_1-$number_2;
					if($number<0){
					$number=0;
					}
					$now = time();
					$sql = "insert into lt_day_active_user values ('', {$system}, {$channel}, {$server}, {$number}, {$yesterday_unix}, {$now})";
					$in = $this->indexM->query_sql($sql);
				}
            }
			
			
			
        }
		
	
		//按系统按服
		foreach($system_arr as $v1){
			$system = $v1['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				foreach($channel_arr as $v2){
					$table = $v2['table'];
					$channel = $v2['number'];
					$number_1 = 0;
					$number_2 = 0;
					
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
					$login_user_uid_arr = $this->gameM->object_array($login_user_uid_arr);

					if(empty($login_user_uid_arr))
						$number_1=0;
					else {
						foreach($login_user_uid_arr as $v){
							foreach($v as $k1 =>$v1){
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$v1}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										break;
									}
									else{
										$number_1=$number_1+1;
									}
									
								
							}
							
						}
					}
					
					
					
					
					$table='lt_day_new_user';
					$sql = "select sum(number) as number from {$table} where system={$system} and server={$server} and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$number_2=$arr[0]->number;
							
					}else{
						$number_2=0;
					}
					$number=$number_1-$number_2;
					if($number<0){
					$number=0;
					}
					$num_arr[]=$number;
					
					
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_active_user values ('', {$system}, 0, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//按渠道按服
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				foreach($system_arr as $v1){
					$system = $v1['number'];
					$number_1 = 0;
					$number_2 = 0;
					
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
					$login_user_uid_arr = $this->gameM->object_array($login_user_uid_arr);

					if(empty($login_user_uid_arr))
						$number_1=0;
					else {
						foreach($login_user_uid_arr as $v){
							foreach($v as $k1 =>$v1){
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$v1}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										break;
									}
									else{
										$number_1=$number_1+1;
									}
									
								
							}
							
						}
					}
					
					$table='lt_day_new_user';
					$sql = "select sum(number) as number from {$table} where system={$system} and server={$server} and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$number_2=$arr[0]->number;
							
					}else{
						$number_2=0;
					}
					$number=$number_1-$number_2;
					if($number<0){
					$number=0;
					}
					$num_arr[]=$number;
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_active_user values ('', 0, {$channel}, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		//按系统按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				$num_arr=array();
				foreach($server_arr as $v3){
					$server = $v3['number'];
					$number_1 = 0;
					$number_2 = 0;
					
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
					$login_user_uid_arr = $this->gameM->object_array($login_user_uid_arr);

					if(empty($login_user_uid_arr))
						$number_1=0;
					else {
						foreach($login_user_uid_arr as $v){
							foreach($v as $k1 =>$v1){
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$v1}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										break;
									}
									else{
										$number_1=$number_1+1;
									}
									
								
							}
							
						}
					}
					$table='lt_day_new_user';
					$sql = "select sum(number) as number from {$table} where system={$system} and server={$server} and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$number_2=$arr[0]->number;
							
					}else{
						$number_2=0;
					}
					$number=$number_1-$number_2;
					if($number<0){
					$number=0;
					}
					$num_arr[]=$number;
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_active_user values ('', {$system},{$channel}, 0, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			$num_arr=array();
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					$number_1 = 0;
					$number_2 = 0;
					
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
					$login_user_uid_arr = $this->gameM->object_array($login_user_uid_arr);

					if(empty($login_user_uid_arr))
						$number_1=0;
					else {
						foreach($login_user_uid_arr as $v){
							foreach($v as $k1 =>$v1){
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$v1}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										break;
									}
									else{
										$number_1=$number_1+1;
									}
									
								
							}
							
						}
					}
					$table='lt_day_new_user';
					$sql = "select sum(number) as number from {$table} where system={$system} and server={$server} and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$number_2=$arr[0]->number;
							
					}else{
						$number_2=0;
					}
					$number=$number_1-$number_2;
					if($number<0){
					$number=0;
					}
					$num_arr[]=$number;
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_active_user values ('',0,{$channel}, 0, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		//按系统
		foreach($system_arr as $v1){
			$system = $v1['number'];
			$num_arr=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					$number_1 = 0;
					$number_2 = 0;
					
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
					$login_user_uid_arr = $this->gameM->object_array($login_user_uid_arr);

					if(empty($login_user_uid_arr))
						$number_1=0;
					else {
						foreach($login_user_uid_arr as $v){
							foreach($v as $k1 =>$v1){
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$v1}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										break;
									}
									else{
										$number_1=$number_1+1;
									}
									
								
							}
							
						}
					}
					$table='lt_day_new_user';
					$sql = "select sum(number) as number from {$table} where system={$system} and server={$server} and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$number_2=$arr[0]->number;
							
					}else{
						$number_2=0;
					}
					$number=$number_1-$number_2;
					if($number<0){
					$number=0;
					}
					$num_arr[]=$number;
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_active_user values ('',{$system},0, 0, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		//按服
		foreach($server_arr as $v3){
			$server = $v3['number'];
			$num_arr=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($system_arr as $v1){
					$system = $v1['number'];
					$number_1 = 0;
					$number_2 = 0;
					
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
					$login_user_uid_arr = $this->gameM->object_array($login_user_uid_arr);

					if(empty($login_user_uid_arr))
						$number_1=0;
					else {
						foreach($login_user_uid_arr as $v){
							foreach($v as $k1 =>$v1){
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$v1}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										break;
									}
									else{
										$number_1=$number_1+1;
									}
									
								
							}
							
						}
					}
					$table='lt_day_new_user';
					$sql = "select sum(number) as number from {$table} where system={$system} and server={$server} and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$number_2=$arr[0]->number;
							
					}else{
						$number_2=0;
					}
					$number=$number_1-$number_2;
					if($number<0){
						$number=0;
					}
					$num_arr[]=$number;
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_active_user values ('',0,0, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		
		//所有的出来
		$num_arr=array();
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					$number_1 = 0;
					$number_2 = 0;
					
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
					$login_user_uid_arr = $this->gameM->object_array($login_user_uid_arr);

					if(empty($login_user_uid_arr))
						$number_1=0;
					else {
						foreach($login_user_uid_arr as $v){
							foreach($v as $k1 =>$v1){
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$v1}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										break;
									}
									else{
										$number_1=$number_1+1;
									}
									
								
							}
							
						}
					}
					
					$table='lt_day_new_user';
					$sql = "select sum(number) as number from {$table} where system={$system} and server={$server} and channel={$channel} and date={$yesterday_unix}";
					$arr = $this->indexM->query_data($sql);
					if(!empty($arr[0]->number)){
						$number_2=$arr[0]->number;
							
					}else{
						$number_2=0;
					}
					$number=$number_1-$number_2;
					if($number<0){
					$number=0;
					}
					$num_arr[]=$number;
				}
				
			}
			
			
		}
		$now = time();
		$numbe=array_sum($num_arr);
		$sql = "insert into lt_day_active_user values ('',0,0, 0, {$numbe}, {$yesterday_unix}, {$now})";
		$in = $this->indexM->query_sql($sql);
       
    }
	
	
	
	
	//日充值
	 public function day_pay()
    {
        date_default_timezone_set("Asia/Shanghai");
		$yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));

        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');
		// 按系统按渠道按服
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                $table = $v2['table'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
                
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money from {$table} where system={$system} and server={$server} and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					echo $sql;
					$user_arr = $this->payM->query_data($sql);
					var_dump($user_arr);
					foreach($user_arr as $v){
						$money = $v->money;
					}
					if(empty($user_arr[0]->money)){
						$money = 0;
					}
					$now = time();
					$sql = "insert into lt_day_pay values ('',{$system}, {$channel},{$server}, '{$money}', {$yesterday_unix}, {$now})";
					$in = $this->indexM->query_sql($sql);
				}
            }
			
			
			
        }
		
		//按系统按服
		foreach($system_arr as $v1){
			$system = $v1['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				foreach($channel_arr as $v2){
					$table = $v2['table'];
					$channel = $v2['number'];
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $v){
						$num_arr[]=$v->money;
					}
					if(empty($user_arr[0]->money)){
						$money = 0;
					}
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_pay values ('', {$system}, 0, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//按渠道按服
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				foreach($system_arr as $v1){
					$system = $v1['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money from {$table} where system={$system} and server={$server} and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $v){
						$num_arr[]=$v->money;
					}
					if(empty($user_arr[0]->money)){
						$money = 0;
					}
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_pay values ('', 0, {$channel}, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		//按系统按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				$num_arr=array();
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money from {$table} where system={$system} and server={$server} and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $v){
						$num_arr[]=$v->money;
					}
					if(empty($user_arr[0]->money)){
						$money = 0;
					}
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_pay values ('', {$system},{$channel}, 0, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			$num_arr=array();
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money from {$table} where system={$system} and server={$server} and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $v){
						$num_arr[]=$v->money;
					}
					if(empty($user_arr[0]->money)){
						$money = 0;
					}
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_pay values ('',0,{$channel}, 0, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		//按系统
		foreach($system_arr as $v1){
			$system = $v1['number'];
			$num_arr=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money from {$table} where system={$system} and server={$server} and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $v){
						$num_arr[]=$v->money;
					}
					if(empty($user_arr[0]->money)){
						$money = 0;
					}
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_pay values ('',{$system},0, 0, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		//按服
		foreach($server_arr as $v3){
			$server = $v3['number'];
			$num_arr=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($system_arr as $v1){
					$system = $v1['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money from {$table} where system={$system} and server={$server} and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $v){
						$num_arr[]=$v->money;
					}
					if(empty($user_arr[0]->money)){
						$money = 0;
					}
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_pay values ('',0,0, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		
		//所有的出来
		$num_arr=array();
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money from {$table} where system={$system} and server={$server} and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $v){
						$num_arr[]=$v->money;
					}
					if(empty($user_arr[0]->money)){
						$money = 0;
					}
				}
				
			}
			
			
		}
		$now = time();
		$numbe=array_sum($num_arr);
		$sql = "insert into lt_day_pay values ('',0,0, 0, {$numbe}, {$yesterday_unix}, {$now})";
		$in = $this->indexM->query_sql($sql);
       
    }
	
	
	
	
	
	
	
	

	

	
	


		
	//日充值结构 
	 public function day_pay_struct()
    {
        date_default_timezone_set("Asia/Shanghai");
		$yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));

        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');
		$now= time();
		// 按系统按渠道按服
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                $table = $v2['table'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
                
					echo $system.','.$channel.','.$server.','.$table.'===>';
					 $sql = "select sum(money) as money  from {$table} where system={$system} and server={$server}   and pay_time>={$yesterday_unix} and pay_time<{$today_unix}  group by game_uid";
					 $user_arr = $this->payM->query_data($sql);
					
					
					if(empty($user_arr)){
						$number=0;
					}else{
						foreach($user_arr as $key=>$v){
							$money=$v->money;
							if($money==0){
								$id=0;
							}
							else if($money>=1 && $money<11){
								$id=1;
							}else if($money>=11 && $money<51){
								$id=2;
							}else if($money>=51 && $money<101){
								$id=3;
							}else if($money>=101 && $money<201){
								$id=4;
							}else if($money>=201 && $money<501){
								$id=5;
							}else if($money>=501 && $money<801){
								$id=6;
							}else if($money>=801 && $money<1001){
								$id=7;
							}else if($money>=1001 && $money<2001){
								$id=8;
							}else if($money>=2001 && $money<5001){
								$id=9;
							}else{
								$id =10;
							}
							$sql="select * from lt_day_pay_struct where date={$yesterday_unix} and moneyid={$id} and system={$system} and server={$server} and channel={$channel}";
							$arr = $this->indexM->query_data($sql);
							if(empty($arr)){
								$sql = "insert into `lt_day_pay_struct`  values ('', {$system}, {$channel}, {$server}, {$id},{$yesterday_unix},{$now},1,{$money})";
								$in = $this->indexM->query_sql($sql);
							}else{
								$sql="update lt_day_pay_struct set number=number+1,money=money+{$money} where date={$yesterday_unix} and moneyid={$id} and system={$system} and server={$server} and channel={$channel} ";
								$in = $this->indexM->query_sql($sql);
							}
							
						}
					}
				}
            }
			
			
			
        }
		//按系统按服
		foreach($system_arr as $v1){
			$system = $v1['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				$money_1=array();
				$money_2=array();
				$money_3=array();
				$money_4=array();
				$money_5=array();
				$money_6=array();
				$money_7=array();
				$money_8=array();
				$money_9=array();
				$money_10=array();
				foreach($channel_arr as $v2){
					$table = $v2['table'];
					$channel = $v2['number'];
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money  from {$table} where system={$system} and server={$server}   and pay_time>={$yesterday_unix} and pay_time<{$today_unix}  group by game_uid";
					$user_arr = $this->payM->query_data($sql);
					
					if(empty($user_arr)){
						$money=0;
					}else{
						foreach($user_arr as $key=>$v){
							$money=$v->money;
							if($money==0){
								$id=0;
							}
							else if($money>=1 && $money<11){
								$id=1;
								$money_1[]=$money;
							}else if($money>=11 && $money<51){
								$id=2;
								$money_2[]=$money;
							}else if($money>=51 && $money<101){
								$id=3;
								$money_3[]=$money;
							}else if($money>=101 && $money<201){
								$id=4;
								$money_4[]=$money;
							}else if($money>=201 && $money<501){
								$id=5;
								$money_5[]=$money;
							}else if($money>=501 && $money<801){
								$id=6;
								$money_6[]=$money;
							}else if($money>=801 && $money<1001){
								$id=7;
								$money_7[]=$money;
							}else if($money>=1001 && $money<2001){
								$id=8;
								$money_8[]=$money;
							}else if($money>=2001 && $money<5001){
								$id=9;
								$money_9[]=$money;
							}else{
								$id =10;
								$money_10[]=$money;
							}
							
							
						}
					
					}
					
				}

				$arr = array();
				for($i=1;$i<=10;$i++){
					$arr =${"money_".$i};
					$numbs=count($arr);
					if(empty($arr)){
						$money=0;
					}else{
						$money=array_sum($arr);
					}
					$sql="insert into `lt_day_pay_struct` values('',{$system},0,{$server},{$i},{$yesterday_unix}, {$now}, {$numbs}, {$money})";
					$in = $this->indexM->query_sql($sql);
				}
			}
			
		}
		

		
		//按渠道按服
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				$money_1=array();
				$money_2=array();
				$money_3=array();
				$money_4=array();
				$money_5=array();
				$money_6=array();
				$money_7=array();
				$money_8=array();
				$money_9=array();
				$money_10=array();
				foreach($system_arr as $v1){
					$system = $v1['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money  from {$table} where system={$system} and server={$server}   and pay_time>={$yesterday_unix} and pay_time<{$today_unix}  group by game_uid";
					$user_arr = $this->payM->query_data($sql);
					
					if(empty($user_arr)){
						$money=0;
					}else{
						foreach($user_arr as $key=>$v){
							$money=$v->money;
							if($money==0){
								$id=0;
							}
							else if($money>=1 && $money<11){
								$id=1;
								$money_1[]=$money;
							}else if($money>=11 && $money<51){
								$id=2;
								$money_2[]=$money;
							}else if($money>=51 && $money<101){
								$id=3;
								$money_3[]=$money;
							}else if($money>=101 && $money<201){
								$id=4;
								$money_4[]=$money;
							}else if($money>=201 && $money<501){
								$id=5;
								$money_5[]=$money;
							}else if($money>=501 && $money<801){
								$id=6;
								$money_6[]=$money;
							}else if($money>=801 && $money<1001){
								$id=7;
								$money_7[]=$money;
							}else if($money>=1001 && $money<2001){
								$id=8;
								$money_8[]=$money;
							}else if($money>=2001 && $money<5001){
								$id=9;
								$money_9[]=$money;
							}else{
								$id =10;
								$money_10[]=$money;
							}
							
							
						}
						
					}
				}
				
				$arr = array();
				for($i=1;$i<=10;$i++){
					$arr =${"money_".$i};
					$numbs=count($arr);
					if(empty($arr)){
						$money=0;
					}else{
						$money=array_sum($arr);
					}
					$sql="insert into `lt_day_pay_struct` values('',0,{$channel},{$server},{$i},{$yesterday_unix}, {$now}, {$numbs}, {$money})";
					$in = $this->indexM->query_sql($sql);
				}
			}
			
		}
		
		//按系统按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				$num_arr=array();
				$money_1=array();
				$money_2=array();
				$money_3=array();
				$money_4=array();
				$money_5=array();
				$money_6=array();
				$money_7=array();
				$money_8=array();
				$money_9=array();
				$money_10=array();
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money  from {$table} where system={$system} and server={$server}   and pay_time>={$yesterday_unix} and pay_time<{$today_unix}  group by game_uid";
					$user_arr = $this->payM->query_data($sql);
					
					if(empty($user_arr)){
						$money=0;
					}else{
						foreach($user_arr as $key=>$v){
							$money=$v->money;
							if($money==0){
								$id=0;
							}
							else if($money>=1 && $money<11){
								$id=1;
								$money_1[]=$money;
							}else if($money>=11 && $money<51){
								$id=2;
								$money_2[]=$money;
							}else if($money>=51 && $money<101){
								$id=3;
								$money_3[]=$money;
							}else if($money>=101 && $money<201){
								$id=4;
								$money_4[]=$money;
							}else if($money>=201 && $money<501){
								$id=5;
								$money_5[]=$money;
							}else if($money>=501 && $money<801){
								$id=6;
								$money_6[]=$money;
							}else if($money>=801 && $money<1001){
								$id=7;
								$money_7[]=$money;
							}else if($money>=1001 && $money<2001){
								$id=8;
								$money_8[]=$money;
							}else if($money>=2001 && $money<5001){
								$id=9;
								$money_9[]=$money;
							}else{
								$id =10;
								$money_10[]=$money;
							}
							
							
						}
						
					}
				}
				
				$arr = array();
				for($i=1;$i<=10;$i++){
					$arr =${"money_".$i};
					$numbs=count($arr);
					if(empty($arr)){
						$money=0;
					}else{
						$money=array_sum($arr);
					}
					$sql="insert into `lt_day_pay_struct` values('',{$system},{$channel},0,{$i},{$yesterday_unix}, {$now}, {$numbs}, {$money})";
					$in = $this->indexM->query_sql($sql);
				}
			}
			
		}
		
		
		//按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			$num_arr=array();
			$money_1=array();
			$money_2=array();
			$money_3=array();
			$money_4=array();
			$money_5=array();
			$money_6=array();
			$money_7=array();
			$money_8=array();
			$money_9=array();
			$money_10=array();
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money  from {$table} where system={$system} and server={$server}   and pay_time>={$yesterday_unix} and pay_time<{$today_unix}  group by game_uid";
					$user_arr = $this->payM->query_data($sql);
					
					if(empty($user_arr)){
						$money=0;
					}else{
						foreach($user_arr as $key=>$v){
							$money=$v->money;
							if($money==0){
								$id=0;
							}
							else if($money>=1 && $money<11){
								$id=1;
								$money_1[]=$money;
							}else if($money>=11 && $money<51){
								$id=2;
								$money_2[]=$money;
							}else if($money>=51 && $money<101){
								$id=3;
								$money_3[]=$money;
							}else if($money>=101 && $money<201){
								$id=4;
								$money_4[]=$money;
							}else if($money>=201 && $money<501){
								$id=5;
								$money_5[]=$money;
							}else if($money>=501 && $money<801){
								$id=6;
								$money_6[]=$money;
							}else if($money>=801 && $money<1001){
								$id=7;
								$money_7[]=$money;
							}else if($money>=1001 && $money<2001){
								$id=8;
								$money_8[]=$money;
							}else if($money>=2001 && $money<5001){
								$id=9;
								$money_9[]=$money;
							}else{
								$id =10;
								$money_10[]=$money;
							}
							
							
						}
						
					}
				}
				
			}
			$arr = array();
			for($i=1;$i<=10;$i++){
				$arr =${"money_".$i};
				$numbs=count($arr);
				if(empty($arr)){
					$money=0;
				}else{
					$money=array_sum($arr);
				}
				$sql="insert into `lt_day_pay_struct` values('',0,{$channel},0,{$i},{$yesterday_unix}, {$now}, {$numbs}, {$money})";
				$in = $this->indexM->query_sql($sql);
			}
		}
		
		//按系统
		foreach($system_arr as $v1){
			$system = $v1['number'];
			$num_arr=array();
			$money_1=array();
			$money_2=array();
			$money_3=array();
			$money_4=array();
			$money_5=array();
			$money_6=array();
			$money_7=array();
			$money_8=array();
			$money_9=array();
			$money_10=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money  from {$table} where system={$system} and server={$server}   and pay_time>={$yesterday_unix} and pay_time<{$today_unix}  group by game_uid";
					$user_arr = $this->payM->query_data($sql);
					
					if(empty($user_arr)){
						$money=0;
					}else{
						foreach($user_arr as $key=>$v){
							$money=$v->money;
							if($money==0){
								$id=0;
							}
							else if($money>=1 && $money<11){
								$id=1;
								$money_1[]=$money;
							}else if($money>=11 && $money<51){
								$id=2;
								$money_2[]=$money;
							}else if($money>=51 && $money<101){
								$id=3;
								$money_3[]=$money;
							}else if($money>=101 && $money<201){
								$id=4;
								$money_4[]=$money;
							}else if($money>=201 && $money<501){
								$id=5;
								$money_5[]=$money;
							}else if($money>=501 && $money<801){
								$id=6;
								$money_6[]=$money;
							}else if($money>=801 && $money<1001){
								$id=7;
								$money_7[]=$money;
							}else if($money>=1001 && $money<2001){
								$id=8;
								$money_8[]=$money;
							}else if($money>=2001 && $money<5001){
								$id=9;
								$money_9[]=$money;
							}else{
								$id =10;
								$money_10[]=$money;
							}
							
							
						}
						
					}
				}
				
			}
			$arr = array();
			for($i=1;$i<=10;$i++){
				$arr =${"money_".$i};
				$numbs=count($arr);
				if(empty($arr)){
					$money=0;
				}else{
					$money=array_sum($arr);
				}
				$sql="insert into `lt_day_pay_struct` values('',{$system},0,0,{$i},{$yesterday_unix}, {$now}, {$numbs}, {$money})";
				$in = $this->indexM->query_sql($sql);
			}
		}
		
		//按服
		foreach($server_arr as $v3){
			$server = $v3['number'];
			$num_arr=array();
			$money_1=array();
			$money_2=array();
			$money_3=array();
			$money_4=array();
			$money_5=array();
			$money_6=array();
			$money_7=array();
			$money_8=array();
			$money_9=array();
			$money_10=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($system_arr as $v1){
					$system = $v1['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money  from {$table} where system={$system} and server={$server}   and pay_time>={$yesterday_unix} and pay_time<{$today_unix}  group by game_uid";
					$user_arr = $this->payM->query_data($sql);
					
					if(empty($user_arr)){
						$money=0;
					}else{
						foreach($user_arr as $key=>$v){
							$money=$v->money;
							if($money==0){
								$id=0;
							}
							else if($money>=1 && $money<11){
								$id=1;
								$money_1[]=$money;
							}else if($money>=11 && $money<51){
								$id=2;
								$money_2[]=$money;
							}else if($money>=51 && $money<101){
								$id=3;
								$money_3[]=$money;
							}else if($money>=101 && $money<201){
								$id=4;
								$money_4[]=$money;
							}else if($money>=201 && $money<501){
								$id=5;
								$money_5[]=$money;
							}else if($money>=501 && $money<801){
								$id=6;
								$money_6[]=$money;
							}else if($money>=801 && $money<1001){
								$id=7;
								$money_7[]=$money;
							}else if($money>=1001 && $money<2001){
								$id=8;
								$money_8[]=$money;
							}else if($money>=2001 && $money<5001){
								$id=9;
								$money_9[]=$money;
							}else{
								$id =10;
								$money_10[]=$money;
							}
							
							
						}
						
					}
				}
				
			}
			$arr = array();
			for($i=1;$i<=10;$i++){
				$arr =${"money_".$i};
				$numbs=count($arr);
				if(empty($arr)){
					$money=0;
				}else{
					$money=array_sum($arr);
				}
				$sql="insert into `lt_day_pay_struct` values('',0,0,{$server},{$i},{$yesterday_unix}, {$now}, {$numbs}, {$money})";
				$in = $this->indexM->query_sql($sql);
			}
		}
		
		
		//所有的出来
		$num_arr=array();
		$money_1=array();
		$money_2=array();
		$money_3=array();
		$money_4=array();
		$money_5=array();
		$money_6=array();
		$money_7=array();
		$money_8=array();
		$money_9=array();
		$money_10=array();
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select sum(money) as money  from {$table} where system={$system} and server={$server}   and pay_time>={$yesterday_unix} and pay_time<{$today_unix}  group by game_uid";
					$user_arr = $this->payM->query_data($sql);
					
					if(empty($user_arr)){
						$money=0;
					}else{
						foreach($user_arr as $key=>$v){
							$money=$v->money;
							if($money==0){
								$id=0;
							}
							else if($money>=1 && $money<11){
								$id=1;
								$money_1[]=$money;
							}else if($money>=11 && $money<51){
								$id=2;
								$money_2[]=$money;
							}else if($money>=51 && $money<101){
								$id=3;
								$money_3[]=$money;
							}else if($money>=101 && $money<201){
								$id=4;
								$money_4[]=$money;
							}else if($money>=201 && $money<501){
								$id=5;
								$money_5[]=$money;
							}else if($money>=501 && $money<801){
								$id=6;
								$money_6[]=$money;
							}else if($money>=801 && $money<1001){
								$id=7;
								$money_7[]=$money;
							}else if($money>=1001 && $money<2001){
								$id=8;
								$money_8[]=$money;
							}else if($money>=2001 && $money<5001){
								$id=9;
								$money_9[]=$money;
							}else{
								$id =10;
								$money_10[]=$money;
							}
							
							
						}
						
					}
				}
				
			}
			
			
		}
		$arr = array();
		for($i=1;$i<=10;$i++){
			$arr =${"money_".$i};
			$numbs=count($arr);
			if(empty($arr)){
				$money=0;
			}else{
				$money=array_sum($arr);
			}
			$sql="insert into `lt_day_pay_struct` values('',0,0,0,{$i},{$yesterday_unix}, {$now}, {$numbs}, {$money})";
			$in = $this->indexM->query_sql($sql);
		}
    }
	
	
	
	
	

		
		
		//日新增充值用户 
	 public function day_addpay_user()
    {
        date_default_timezone_set("Asia/Shanghai");
		$yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $yesterday_yes = strtotime(date('Ymd',strtotime('-2 day')));
        $today_unix = strtotime(date('Ymd'));

        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');
		// 按系统按渠道按服
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                $table = $v2['table'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
                
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct game_uid  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $k=>$v){
						$sql = "select * from {$table} where system={$system} and server={$server}  and pay_time<{$yesterday_yes} and game_uid={$v->game_uid}";
						$arr = $this->payM->query_data($sql);
						if(empty($arr)){
							unset($user_arr[$k]);
						}
					}
					$num=count($user_arr);
					
					$now = time();
					$sql = "insert into lt_day_addpay_user values ('', {$system}, {$channel}, {$server}, {$num}, {$yesterday_unix}, {$now})";
					$in = $this->indexM->query_sql($sql);

				}
            }
			
			
			
        }
		//按系统按服
		foreach($system_arr as $v1){
			$system = $v1['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				foreach($channel_arr as $v2){
					$table = $v2['table'];
					$channel = $v2['number'];
					echo $system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct game_uid  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $k=>$v){
						$sql = "select * from {$table} where system={$system} and server={$server}  and pay_time<{$yesterday_yes} and game_uid={$v->game_uid}";
						$arr = $this->payM->query_data($sql);
						if(empty($arr)){
							unset($user_arr[$k]);
						}
					}
					$num=count($user_arr);
					$num_arr[]=$num;
					
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_addpay_user values ('', {$system}, 0, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//按渠道按服
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				$num_arr=array();
				foreach($system_arr as $v1){
					$system = $v1['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct game_uid  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $k=>$v){
						$sql = "select * from {$table} where system={$system} and server={$server}  and pay_time<{$yesterday_yes} and game_uid={$v->game_uid}";
						$arr = $this->payM->query_data($sql);
						if(empty($arr)){
							unset($user_arr[$k]);
						}
					}
					$num=count($user_arr);
					$num_arr[]=$num;
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_addpay_user values ('', 0, {$channel}, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		//按系统按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				$num_arr=array();
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct game_uid  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $k=>$v){
						$sql = "select * from {$table} where system={$system} and server={$server}  and pay_time<{$yesterday_yes} and game_uid={$v->game_uid}";
						$arr = $this->payM->query_data($sql);
						if(empty($arr)){
							unset($user_arr[$k]);
						}
					}
					$num=count($user_arr);
					$num_arr[]=$num;
				}
				$now = time();
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_addpay_user values ('', {$system},{$channel}, 0, {$numbe}, {$yesterday_unix}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			$num_arr=array();
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct game_uid  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $k=>$v){
						$sql = "select * from {$table} where system={$system} and server={$server}  and pay_time<{$yesterday_yes} and game_uid={$v->game_uid}";
						$arr = $this->payM->query_data($sql);
						if(empty($arr)){
							unset($user_arr[$k]);
						}
					}
					$num=count($user_arr);
					$num_arr[]=$num;
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_addpay_user values ('',0,{$channel}, 0, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		//按系统
		foreach($system_arr as $v1){
			$system = $v1['number'];
			$num_arr=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct game_uid  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $k=>$v){
						$sql = "select * from {$table} where system={$system} and server={$server}  and pay_time<{$yesterday_yes} and game_uid={$v->game_uid}";
						$arr = $this->payM->query_data($sql);
						if(empty($arr)){
							unset($user_arr[$k]);
						}
					}
					$num=count($user_arr);
					$num_arr[]=$num;
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_addpay_user values ('',{$system},0, 0, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		//按服
		foreach($server_arr as $v3){
			$server = $v3['number'];
			$num_arr=array();
			foreach($channel_arr as $v2){
				$table = $v2['table'];
				$channel = $v2['number'];
				foreach($system_arr as $v1){
					$system = $v1['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct game_uid  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $k=>$v){
						$sql = "select * from {$table} where system={$system} and server={$server}  and pay_time<{$yesterday_yes} and game_uid={$v->game_uid}";
						$arr = $this->payM->query_data($sql);
						if(empty($arr)){
							unset($user_arr[$k]);
						}
					}
					$num=count($user_arr);
					$num_arr[]=$num;
				}
				
			}
			$now = time();
			$numbe=array_sum($num_arr);
			$sql = "insert into lt_day_addpay_user values ('',0,0, {$server}, {$numbe}, {$yesterday_unix}, {$now})";
			$in = $this->indexM->query_sql($sql);
			
		}
		
		
		//所有的出来
		$num_arr=array();
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				foreach($server_arr as $v3){
					$server = $v3['number'];
					echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					$sql = "select distinct game_uid  from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_unix} and pay_time<{$today_unix}";
					$user_arr = $this->payM->query_data($sql);
					
					foreach($user_arr as $k=>$v){
						$sql = "select * from {$table} where system={$system} and server={$server}  and pay_time<{$yesterday_yes} and game_uid={$v->game_uid}";
						$arr = $this->payM->query_data($sql);
						if(empty($arr)){
							unset($user_arr[$k]);
						}
					}
					$num=count($user_arr);
					$num_arr[]=$num;
				}
				
			}
			
			
		}
		$now = time();
		$numbe=array_sum($num_arr);
		$sql = "insert into lt_day_addpay_user values ('',0,0, 0, {$numbe}, {$yesterday_unix}, {$now})";
		$in = $this->indexM->query_sql($sql);
       
    }
	
	
	
	

	
		
		//玩家充值走势
	 public function day_trend_user()
    {
        date_default_timezone_set("Asia/Shanghai");
		$yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $yesterday_yes = strtotime(date('Ymd',strtotime('-2 day')));
        $today_unix = strtotime(date('Ymd'));

        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');

		//按服
		foreach($server_arr as $v3){
			$server = $v3['number'];
			
			for($i=0;$i<24;$i++){
				$num_arr=array();
				$yesterday_time_1 = $yesterday_unix+3600*$i;
				$yesterday_time_2 = $yesterday_unix+3600*($i+1);
				foreach($channel_arr as $v2){
					$table = $v2['table'];
					$channel = $v2['number'];
					foreach($system_arr as $v1){
					   $system = $v1['number'];
					   echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
					   $sql = "select count(distinct game_uid) as number from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_time_1} and pay_time<{$yesterday_time_2}";
						$user_arr = $this->payM->query_data($sql);
						foreach($user_arr as $v){
								$num_arr[]=$v->number;
						}
						if(empty($user_arr)){
							$num_arr[]=0;
						} 
					}
				}
				$numbe=array_sum($num_arr);
				$now = time();
				$sql = "insert into lt_day_trend_user values ('', 0, 0, {$server}, {$numbe}, {$yesterday_time_1}, {$now})";
			   
				$in = $this->indexM->query_sql($sql);
	
			}
			
		}
		
		
		//所有的出来
		$num_arr=array();
		for($i=0;$i<24;$i++){
			$yesterday_time_1 = $yesterday_unix+3600*$i;
			$yesterday_time_2 = $yesterday_unix+3600*($i+1);
			$num_arr=array();
			foreach($server_arr as $v3){
					$server = $v3['number'];	
					foreach($channel_arr as $v2){
						$table = $v2['table'];
						$channel = $v2['number'];
						foreach($system_arr as $v1){
						   $system = $v1['number'];
						   echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
						   $sql = "select count(distinct game_uid) as number from {$table} where system={$system} and server={$server}  and pay_time>={$yesterday_time_1} and pay_time<{$yesterday_time_2}";
							$user_arr = $this->payM->query_data($sql);
							foreach($user_arr as $v){
									$num_arr[]=$v->number;
							}
							if(empty($user_arr)){
								$num_arr[]=0;
							} 
						}
					}
					
			}
			$numbe=array_sum($num_arr);
			$now = time();
			$sql = "insert into lt_day_trend_user values ('', 0, 0, 0, {$numbe}, {$yesterday_time_1}, {$now})";
		   
			$in = $this->indexM->query_sql($sql);

		}
		
       
    }
	
	
	
	
	

	
	
	
		
		//在线人数整点统计
	
	 public function day_user_online()
    {
        date_default_timezone_set("Asia/Shanghai");
		$yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));

        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');
		$now=time();
		// 按系统按渠道按服
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                $table = $v2['table'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
					echo $system.','.$channel.','.$server.','.$table.'===>';
					for($i=0;$i<24;$i++){
						$number = 0;
						$yesterday_time_1 = $yesterday_unix+3600*$i;
						$yesterday_time_2 = $yesterday_unix+3600*($i+1);
						$sql="select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_time_1} and online_time<{$yesterday_time_2}";
						 $this->load->model('game_model_'.$server, 'gameM');
						$login_user_uid_arr = $this->gameM->query_data($sql);
						if(empty($login_user_uid_arr)){
							$sql = "insert into `lt_day_user_online`  values ('', {$system}, {$channel}, {$server}, 0, {$yesterday_time_1}, {$now})";
							$in = $this->indexM->query_sql($sql);
						}else{
							foreach($login_user_uid_arr as $v){
								$game_uid=$v->game_uid;
								$table = 'lt_account';
								$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$game_uid}";
								$acc_arr = $this->accountM->query_data($sql);
								if(empty($acc_arr)){
									continue;
								}
								else{
									$number=$number+1;
								}
							}
							$sql = "insert into `lt_day_user_online`  values ('', {$system}, {$channel}, {$server}, {$number}, {$yesterday_time_1}, {$now})";
							$in = $this->indexM->query_sql($sql);
	
						}
						
					}
					
				}
            }
			
			
			
        }
		

		//按系统按服
		foreach($system_arr as $v1){
			$system = $v1['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				
				for($i=0;$i<24;$i++){
					$num_arr=array();
					$yesterday_time_1 = $yesterday_unix+3600*$i;
					$yesterday_time_2 = $yesterday_unix+3600*($i+1);
					foreach($channel_arr as $v2){
							$channel = $v2['number'];
							echo $system.','.$channel.','.$server.','.$table.'===>';
							$number = 0;
							
							$sql="select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_time_1} and online_time<{$yesterday_time_2}";
							 $this->load->model('game_model_'.$server, 'gameM');
							$login_user_uid_arr = $this->gameM->query_data($sql);
							if(empty($login_user_uid_arr)){
								$num_arr[]=0;
							}else{
								foreach($login_user_uid_arr as $v){
									$game_uid=$v->game_uid;
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$game_uid}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										continue;
									}
									else{
										$number=$number+1;
									}
								}
								$num_arr[]=$number;

							}
					
					}
					$numbe=array_sum($num_arr);
					$sql = "insert into lt_day_user_online values ('', {$system}, 0, {$server}, {$numbe}, {$yesterday_time_1}, {$now})";
					$in = $this->indexM->query_sql($sql);
					
				}
				
			}
			
		}
		
		
		//按渠道按服
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($server_arr as $v3){
				$server = $v3['number'];
				
				for($i=0;$i<24;$i++){
					$num_arr=array();
					$yesterday_time_1 = $yesterday_unix+3600*$i;
					$yesterday_time_2 = $yesterday_unix+3600*($i+1);
						foreach($system_arr as $v1){
							$system = $v1['number'];
							$number = 0;
							
							$sql="select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_time_1} and online_time<{$yesterday_time_2}";
							 $this->load->model('game_model_'.$server, 'gameM');
							$login_user_uid_arr = $this->gameM->query_data($sql);
							if(empty($login_user_uid_arr)){
								$num_arr[]=0;
							}else{
								foreach($login_user_uid_arr as $v){
									$game_uid=$v->game_uid;
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$game_uid}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										continue;
									}
									else{
										$number=$number+1;
									}
								}
								$num_arr[]=$number;

							}
	
						}
						$numbe=array_sum($num_arr);
						$sql = "insert into lt_day_user_online values ('', 0, {$channel}, {$server},{$numbe}, {$yesterday_time_1}, {$now})";
						$in = $this->indexM->query_sql($sql);
				}
				
				
			}
				
		}
			
		
		
		//按系统按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			foreach($system_arr as $v1){
				$system = $v1['number'];
				
				for($i=0;$i<24;$i++){
					$num_arr=array();
					$yesterday_time_1 = $yesterday_unix+3600*$i;
					$yesterday_time_2 = $yesterday_unix+3600*($i+1);
					foreach($server_arr as $v3){
							$server = $v3['number'];
							$number = 0;
							
							$sql="select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_time_1} and online_time<{$yesterday_time_2}";
							 $this->load->model('game_model_'.$server, 'gameM');
							$login_user_uid_arr = $this->gameM->query_data($sql);
							if(empty($login_user_uid_arr)){
								$num_arr[]=0;
							}else{
								foreach($login_user_uid_arr as $v){
									$game_uid=$v->game_uid;
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$game_uid}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										continue;
									}
									else{
										$number=$number+1;
									}
								}
								$num_arr[]=$number;

							}
					}
					$numbe=array_sum($num_arr);
					$sql = "insert into lt_day_user_online values ('', {$system},{$channel}, 0, {$numbe}, {$yesterday_time_1}, {$now})";
					$in = $this->indexM->query_sql($sql);
				
				}
				
			}
			
		}
		

		//按渠道
		foreach($channel_arr as $v2){
			$table = $v2['table'];
			$channel = $v2['number'];
			
			for($i=0;$i<24;$i++){
				$num_arr=array();
				$yesterday_time_1 = $yesterday_unix+3600*$i;
				$yesterday_time_2 = $yesterday_unix+3600*($i+1);
				foreach($system_arr as $v1){
					$system = $v1['number'];
					foreach($server_arr as $v3){
							$server = $v3['number'];
							echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
							$number = 0;
							$sql="select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_time_1} and online_time<{$yesterday_time_2}";
							 $this->load->model('game_model_'.$server, 'gameM');
							$login_user_uid_arr = $this->gameM->query_data($sql);
							if(empty($login_user_uid_arr)){
								$num_arr[]=0;
							}else{
								foreach($login_user_uid_arr as $v){
									$game_uid=$v->game_uid;
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$game_uid}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										continue;
									}
									else{
										$number=$number+1;
									}
								}
								$num_arr[]=$number;

							}
					}
				
				}
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_user_online values ('',0,{$channel}, 0, {$numbe}, {$yesterday_time_1}, {$now})";
				$in = $this->indexM->query_sql($sql);

			}
			
		}
		
		//按系统
		foreach($system_arr as $v1){
			$system = $v1['number'];
			
			for($i=0;$i<24;$i++){
				$num_arr=array();
				$yesterday_time_1 = $yesterday_unix+3600*$i;
				$yesterday_time_2 = $yesterday_unix+3600*($i+1);
				foreach($channel_arr as $v2){
					$channel = $v2['number'];
					foreach($server_arr as $v3){
							$server = $v3['number'];
							echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
							$number = 0;
							$sql="select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_time_1} and online_time<{$yesterday_time_2}";
							 $this->load->model('game_model_'.$server, 'gameM');
							$login_user_uid_arr = $this->gameM->query_data($sql);
							if(empty($login_user_uid_arr)){
								$num_arr[]=0;
							}else{
								foreach($login_user_uid_arr as $v){
									$game_uid=$v->game_uid;
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$game_uid}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										continue;
									}
									else{
										$number=$number+1;
									}
								}
								$num_arr[]=$number;

							}
					}
				
				}
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_user_online values ('',{$system},0, 0, {$numbe}, {$yesterday_time_1}, {$now})";
				$in = $this->indexM->query_sql($sql);
			
			}
		
		}
		
		//按服
		foreach($server_arr as $v3){
			$server = $v3['number'];
			
			for($i=0;$i<24;$i++){
				$num_arr=array();
				$yesterday_time_1 = $yesterday_unix+3600*$i;
				$yesterday_time_2 = $yesterday_unix+3600*($i+1);
				foreach($channel_arr as $v2){
					
					$channel = $v2['number'];
					foreach($system_arr as $v1){
							$system = $v1['number'];
							echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
							$number = 0;
							$sql="select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_time_1} and online_time<{$yesterday_time_2}";
							 $this->load->model('game_model_'.$server, 'gameM');
							$login_user_uid_arr = $this->gameM->query_data($sql);
							if(empty($login_user_uid_arr)){
								$num_arr[]=0;
							}else{
								foreach($login_user_uid_arr as $v){
									$game_uid=$v->game_uid;
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$game_uid}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										continue;
									}
									else{
										$number=$number+1;
									}
								}
								$num_arr[]=$number;

							}
					}
					
				}
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_user_online values ('',0,0, {$server}, {$numbe}, {$yesterday_time_1}, {$now})";
				$in = $this->indexM->query_sql($sql);
			}
			
		}
		
		
		//所有的出来
		
		for($i=0;$i<24;$i++){
				$num_arr=array();
				$yesterday_time_1 = $yesterday_unix+3600*$i;
				$yesterday_time_2 = $yesterday_unix+3600*($i+1);
				foreach($channel_arr as $v2){
					$channel = $v2['number'];
					foreach($system_arr as $v1){
						$system = $v1['number'];
						foreach($server_arr as $v3){
							$server = $v3['number'];
							echo '<br/>'.$system.','.$channel.','.$server.','.$table.'===>';
							$number = 0;
							$sql="select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_time_1} and online_time<{$yesterday_time_2}";
							 $this->load->model('game_model_'.$server, 'gameM');
							$login_user_uid_arr = $this->gameM->query_data($sql);
							if(empty($login_user_uid_arr)){
								$num_arr[]=0;
							}else{
								foreach($login_user_uid_arr as $v){
									$game_uid=$v->game_uid;
									$table = 'lt_account';
									$sql = "select game_uid from {$table} where system={$system} and area={$server} and channel={$channel} and game_uid={$game_uid}";
									$acc_arr = $this->accountM->query_data($sql);
									if(empty($acc_arr)){
										continue;
									}
									else{
										$number=$number+1;
									}
								}
								$num_arr[]=$number;

							}
						}
						
					}
				
				
				}
			
				$numbe=array_sum($num_arr);
				$sql = "insert into lt_day_user_online values ('',0,0, 0, {$numbe}, {$yesterday_time_1}, {$now})";
				$in = $this->indexM->query_sql($sql);
		}
		
    }
	
	
	
	
	
    //次日留存
    public function next_day_retention()
    {
        $day_before_yesterday_unix = strtotime(date("Y-m-d",strtotime("-2 day")));
        $yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));
        $retention_table = 'lt_nextday_retention';


        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');

        //按系统按渠道按服的留存
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
                    $table = 'lt_account';
                    $sql = "select game_uid from {$table} where system={$system} and channel={$channel} and area={$server} and create_time>={$day_before_yesterday_unix} and create_time<{$yesterday_unix}";
                    $create_user_uid_arr = $this->accountM->query_data($sql);
                    foreach($create_user_uid_arr as $v){
                        $create_user_uid[] = $v->game_uid;
                    }

                    if(empty($create_user_uid)){
                        $create_user_num = 0;
                        $login_user_num  = 0;
                        $retention = 0;
                    }
                    else{
                        $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                        $this->load->model('game_model_'.$server, 'gameM');
                        $login_user_uid_arr = $this->gameM->query_data($sql);
                        $retention = $this->retention($create_user_uid, $login_user_uid_arr);
                        $login_user_num = $this->login_user_num($create_user_uid, $login_user_uid_arr);
                        $create_user_num = count($create_user_uid);
                    }
                    $sql = "insert into {$retention_table} values ('', {$system}, {$channel}, {$server}, {$login_user_num}, {$create_user_num},
                           '{$retention}', {$day_before_yesterday_unix}, {$today_unix})";
                    $in = $this->indexM->query_sql($sql);
                }
            }
        }


        //按系统按服的留存
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($server_arr as $v3){
                $server = $v3['number'];
                $table = 'lt_account';
                $sql = "select game_uid from {$table} where system={$system} and area={$server} and create_time>={$day_before_yesterday_unix} and create_time<{$yesterday_unix}";
                $create_user_uid_arr = $this->accountM->query_data($sql);
                foreach($create_user_uid_arr as $v){
                    $system_create_user_uid[] = $v->game_uid;
                }

                if(empty($system_create_user_uid)){
                    $create_user_num = 0;
                    $login_user_num  = 0;
                    $retention = 0;
                }
                else{
                    $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
                    $retention = $this->retention($system_create_user_uid, $login_user_uid_arr);
                    $login_user_num = $this->login_user_num($system_create_user_uid, $login_user_uid_arr);
                    $create_user_num = count($system_create_user_uid);
                }
                $sql = "insert into {$retention_table} values ('', {$system}, 0, {$server}, {$login_user_num}, {$create_user_num},
                       '{$retention}', {$day_before_yesterday_unix}, {$today_unix})";
                $in = $this->indexM->query_sql($sql);
            }
        }

        //按渠道分服的留存
        foreach($channel_arr as $v2){
            $channel = $v2['number'];
            foreach($server_arr as $v3){
                $server = $v3['number'];
                $table = 'lt_account';
                $sql = "select game_uid from {$table} where channel={$channel} and area={$server} and create_time>={$day_before_yesterday_unix} and create_time<{$yesterday_unix}";
                $create_user_uid_arr = $this->accountM->query_data($sql);
                foreach($create_user_uid_arr as $v){
                    $channel_create_user_uid[] = $v->game_uid;
                }

                if(empty($channel_create_user_uid)){
                    $create_user_num = 0;
                    $login_user_num  = 0;
                    $retention = 0;
                }
                else{
                    $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
                    $retention = $this->retention($channel_create_user_uid, $login_user_uid_arr);
                    $login_user_num = $this->login_user_num($channel_create_user_uid, $login_user_uid_arr);
                    $create_user_num = count($channel_create_user_uid);
                }
                $sql = "insert into {$retention_table} values ('', 0, {$channel}, {$server}, {$login_user_num}, {$create_user_num},
                           '{$retention}', {$day_before_yesterday_unix}, {$today_unix})";
                $in = $this->indexM->query_sql($sql);
            }
        }


        //按服的留存
        foreach($server_arr as $v3){
            $server = $v3['number'];
            $table = 'lt_account';
            $sql = "select game_uid from {$table} where create_time>={$day_before_yesterday_unix} and create_time<{$yesterday_unix}";
            $create_user_uid_arr = $this->accountM->query_data($sql);
            foreach($create_user_uid_arr as $v){
                $server_create_user_uid[] = $v->game_uid;
            }

            if(empty($server_create_user_uid)){
                $create_user_num = 0;
                $login_user_num  = 0;
                $retention = 0;
            }
            else{
                $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                $this->load->model('game_model_'.$server, 'gameM');
                $login_user_uid_arr = $this->gameM->query_data($sql);
                $retention = $this->retention($server_create_user_uid, $login_user_uid_arr);
                $login_user_num = $this->login_user_num($server_create_user_uid, $login_user_uid_arr);
                $create_user_num = count($server_create_user_uid);
            }
            $sql = "insert into {$retention_table} values ('', 0, 0, {$server}, {$login_user_num}, {$create_user_num},
                           '{$retention}', {$day_before_yesterday_unix}, {$today_unix})";
            $in = $this->indexM->query_sql($sql);
        }


        //所有留存
        foreach($server_arr as $v3){
            $server = $v3['number'];
            $table = 'lt_account';
            $sql = "select game_uid from {$table} where create_time>={$day_before_yesterday_unix} and create_time<{$yesterday_unix}";
            $all_create_user_uid_arr = $this->accountM->query_data($sql);
            foreach($all_create_user_uid_arr as $v){
                $all_create_user_uid[] = $v->game_uid;
            }

            if(empty($all_create_user_uid)){
                $create_user_num = 0;
                $login_user_num  = 0;
            }
            else{
                $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                $this->load->model('game_model_'.$server, 'gameM');
                $login_user_uid_arr = $this->gameM->query_data($sql);
                $login_user_num = $this->login_user_num($all_create_user_uid, $login_user_uid_arr);
                $create_user_num = count($create_user_uid);
            }
            $final_arr[] = array('login_user_num'=>$login_user_num, 'create_user_num'=>$create_user_num);
        }

        $final_create_user_num='';
        $final_login_user_num='';
        foreach($final_arr as $values){
            $final_create_user_num += $values['create_user_num'];
            $final_login_user_num += $values['login_user_num'];
        }
        $finale_retention = round($final_login_user_num/$final_create_user_num*100, 2);
        $sql = "insert into {$retention_table} values ('', 0, 0, 0, {$final_login_user_num}, {$final_create_user_num},
                           '{$finale_retention}', {$day_before_yesterday_unix}, {$today_unix})";
        $in = $this->indexM->query_sql($sql);




        //按系统所有的留存
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($server_arr as $v3){
                $server = $v3['number'];
                $table = 'lt_account';
                $sql = "select game_uid from {$table} where system={$system} and create_time>={$day_before_yesterday_unix} and create_time<{$yesterday_unix}";
                $system_server_create_user_uid_arr = $this->accountM->query_data($sql);
                foreach($system_server_create_user_uid_arr as $v){
                    $system_server_create_user_uid[] = $v->game_uid;
                }

                if(empty($system_server_create_user_uid)){
                    $create_user_num = 0;
                    $login_user_num  = 0;
                }
                else{
                    $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
                    $login_user_num = $this->login_user_num($system_server_create_user_uid, $login_user_uid_arr);
                    $create_user_num = count($system_server_create_user_uid);
                }
                $new_final_arr[] = array('login_user_num'=>$login_user_num, 'create_user_num'=>$create_user_num);
            }
        }

        $final_create_user_num='';
        $final_login_user_num='';
        foreach($new_final_arr as $values){
            $final_create_user_num += $values['create_user_num'];
            $final_login_user_num += $values['login_user_num'];
        }
        foreach($system_arr as $v){
            $system = $v['number'];
            $finale_retention = round($final_login_user_num/$final_create_user_num*100, 2);
            $sql = "insert into {$retention_table} values ('', {$system}, 0, 0, {$final_login_user_num}, {$final_create_user_num},
                       '{$finale_retention}', {$day_before_yesterday_unix}, {$today_unix})";
            $in = $this->indexM->query_sql($sql);
        }



        //按渠道所有服的留存
        foreach($channel_arr as $v1){
            $channel = $v1['number'];
            foreach($server_arr as $v3){
                $server = $v3['number'];
                $table = 'lt_account';
                $sql = "select game_uid from {$table} where channel={$channel} and create_time>={$day_before_yesterday_unix} and create_time<{$yesterday_unix}";
                $channel_server_create_user_uid_arr = $this->accountM->query_data($sql);
                foreach($channel_server_create_user_uid_arr as $v){
                    $channel_server_create_user_uid[] = $v->game_uid;
                }

                if(empty($channel_server_create_user_uid)){
                    $create_user_num = 0;
                    $login_user_num  = 0;
                }
                else{
                    $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
                    $login_user_num = $this->login_user_num($channel_server_create_user_uid, $login_user_uid_arr);
                    $create_user_num = count($channel_server_create_user_uid);
                }
                $channel_final_arr[] = array('login_user_num'=>$login_user_num, 'create_user_num'=>$create_user_num);
            }
        }

        $final_create_user_num='';
        $final_login_user_num='';
        foreach($channel_final_arr as $values){
            $final_create_user_num += $values['create_user_num'];
            $final_login_user_num += $values['login_user_num'];
        }
        foreach($channel_arr as $v){
            $channel = $v['number'];
            $finale_retention = round($final_login_user_num/$final_create_user_num*100, 2);
            $sql = "insert into {$retention_table} values ('', 0, {$channel}, 0, {$final_login_user_num}, {$final_create_user_num},
                       '{$finale_retention}', {$day_before_yesterday_unix}, {$today_unix})";
            $in = $this->indexM->query_sql($sql);
        }




        //按系统按渠道所有服的留存
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
                    $table = 'lt_account';
                    $sql = "select game_uid from {$table} where system={$system} and channel={$channel} and create_time>={$day_before_yesterday_unix} and create_time<{$yesterday_unix}";
                    $system_channel_server_create_user_uid_arr = $this->accountM->query_data($sql);
                    foreach($system_channel_server_create_user_uid_arr as $v){
                        $system_channel_server_create_user_uid[] = $v->game_uid;
                    }

                    if(empty($system_channel_server_create_user_uid)){
                        $create_user_num = 0;
                        $login_user_num  = 0;
                    }
                    else{
                        $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                        $this->load->model('game_model_'.$server, 'gameM');
                        $login_user_uid_arr = $this->gameM->query_data($sql);
                        $login_user_num = $this->login_user_num($system_channel_server_create_user_uid, $login_user_uid_arr);
                        $create_user_num = count($system_channel_server_create_user_uid);
                    }
                    $system_channel_final_arr[] = array('login_user_num'=>$login_user_num, 'create_user_num'=>$create_user_num);
                }
            }
        }


        $final_create_user_num='';
        $final_login_user_num='';
        foreach($system_channel_final_arr as $values){
            $final_create_user_num += $values['create_user_num'];
            $final_login_user_num += $values['login_user_num'];
        }
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                $finale_retention = round($final_login_user_num/$final_create_user_num*100, 2);
                $sql = "insert into {$retention_table} values ('', {$system}, {$channel}, 0, {$final_login_user_num}, {$final_create_user_num},
                       '{$finale_retention}', {$day_before_yesterday_unix}, {$today_unix})";
                $in = $this->indexM->query_sql($sql);
            }
        }
    }



    //三日留存
    public function three_day_retention()
    {
        $more_day_before_yesterday_unix = strtotime(date("Y-m-d",strtotime("-3 day")));
        $day_before_yesterday_unix = strtotime(date('Ymd',strtotime('-2 day')));
        $yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));
        $retention_table = 'lt_threeday_retention';


        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');

        //按系统按渠道按服的留存
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
                    $table = 'lt_account';
                    $sql = "select game_uid from {$table} where system={$system} and channel={$channel} and area={$server} and create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
                    $create_user_uid_arr = $this->accountM->query_data($sql);
                    foreach($create_user_uid_arr as $v){
                        $create_user_uid[] = $v->game_uid;
                    }

                    if(empty($create_user_uid)){
                        $create_user_num = 0;
                        $login_user_num  = 0;
                        $retention = 0;
                    }
                    else{
                        $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                        $this->load->model('game_model_'.$server, 'gameM');
                        $login_user_uid_arr = $this->gameM->query_data($sql);
                        $retention = $this->retention($create_user_uid, $login_user_uid_arr);
                        $login_user_num = $this->login_user_num($create_user_uid, $login_user_uid_arr);
                        $create_user_num = count($create_user_uid);
                    }
                    $sql = "insert into {$retention_table} values ('', {$system}, {$channel}, {$server}, {$login_user_num}, {$create_user_num},
                           '{$retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
                    $in = $this->indexM->query_sql($sql);
                }
            }
        }


        //按系统按服的留存
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($server_arr as $v3){
                $server = $v3['number'];
                $table = 'lt_account';
                $sql = "select game_uid from {$table} where system={$system} and area={$server} and create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
                $create_user_uid_arr = $this->accountM->query_data($sql);
                foreach($create_user_uid_arr as $v){
                    $system_create_user_uid[] = $v->game_uid;
                }

                if(empty($system_create_user_uid)){
                    $create_user_num = 0;
                    $login_user_num  = 0;
                    $retention = 0;
                }
                else{
                    $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
                    $retention = $this->retention($system_create_user_uid, $login_user_uid_arr);
                    $login_user_num = $this->login_user_num($system_create_user_uid, $login_user_uid_arr);
                    $create_user_num = count($system_create_user_uid);
                }
                $sql = "insert into {$retention_table} values ('', {$system}, 0, {$server}, {$login_user_num}, {$create_user_num},
                       '{$retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
                $in = $this->indexM->query_sql($sql);
            }
        }

        //按渠道分服的留存
        foreach($channel_arr as $v2){
            $channel = $v2['number'];
            foreach($server_arr as $v3){
                $server = $v3['number'];
                $table = 'lt_account';
                $sql = "select game_uid from {$table} where channel={$channel} and area={$server} and create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
                $create_user_uid_arr = $this->accountM->query_data($sql);
                foreach($create_user_uid_arr as $v){
                    $channel_create_user_uid[] = $v->game_uid;
                }

                if(empty($channel_create_user_uid)){
                    $create_user_num = 0;
                    $login_user_num  = 0;
                    $retention = 0;
                }
                else{
                    $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
                    $retention = $this->retention($channel_create_user_uid, $login_user_uid_arr);
                    $login_user_num = $this->login_user_num($channel_create_user_uid, $login_user_uid_arr);
                    $create_user_num = count($channel_create_user_uid);
                }
                $sql = "insert into {$retention_table} values ('', 0, {$channel}, {$server}, {$login_user_num}, {$create_user_num},
                           '{$retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
                $in = $this->indexM->query_sql($sql);
            }
        }


        //按服的留存
        foreach($server_arr as $v3){
            $server = $v3['number'];
            $table = 'lt_account';
            $sql = "select game_uid from {$table} where create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
            $create_user_uid_arr = $this->accountM->query_data($sql);
            foreach($create_user_uid_arr as $v){
                $server_create_user_uid[] = $v->game_uid;
            }

            if(empty($server_create_user_uid)){
                $create_user_num = 0;
                $login_user_num  = 0;
                $retention = 0;
            }
            else{
                $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                $this->load->model('game_model_'.$server, 'gameM');
                $login_user_uid_arr = $this->gameM->query_data($sql);
                $retention = $this->retention($server_create_user_uid, $login_user_uid_arr);
                $login_user_num = $this->login_user_num($server_create_user_uid, $login_user_uid_arr);
                $create_user_num = count($server_create_user_uid);
            }
            $sql = "insert into {$retention_table} values ('', 0, 0, {$server}, {$login_user_num}, {$create_user_num},
                           '{$retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
            $in = $this->indexM->query_sql($sql);
        }


        //所有留存
        foreach($server_arr as $v3){
            $server = $v3['number'];
            $table = 'lt_account';
            $sql = "select game_uid from {$table} where create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
            $all_create_user_uid_arr = $this->accountM->query_data($sql);
            foreach($all_create_user_uid_arr as $v){
                $all_create_user_uid[] = $v->game_uid;
            }

            if(empty($all_create_user_uid)){
                $create_user_num = 0;
                $login_user_num  = 0;
            }
            else{
                $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                $this->load->model('game_model_'.$server, 'gameM');
                $login_user_uid_arr = $this->gameM->query_data($sql);
                $login_user_num = $this->login_user_num($all_create_user_uid, $login_user_uid_arr);
                $create_user_num = count($create_user_uid);
            }
            $final_arr[] = array('login_user_num'=>$login_user_num, 'create_user_num'=>$create_user_num);
        }

        $final_create_user_num='';
        $final_login_user_num='';
        foreach($final_arr as $values){
            $final_create_user_num += $values['create_user_num'];
            $final_login_user_num += $values['login_user_num'];
        }
        $finale_retention = round($final_login_user_num/$final_create_user_num*100, 2);
        $sql = "insert into {$retention_table} values ('', 0, 0, 0, {$final_login_user_num}, {$final_create_user_num},
                           '{$finale_retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
        $in = $this->indexM->query_sql($sql);




        //按系统所有的留存
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($server_arr as $v3){
                $server = $v3['number'];
                $table = 'lt_account';
                $sql = "select game_uid from {$table} where system={$system} and create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
                $system_server_create_user_uid_arr = $this->accountM->query_data($sql);
                foreach($system_server_create_user_uid_arr as $v){
                    $system_server_create_user_uid[] = $v->game_uid;
                }

                if(empty($system_server_create_user_uid)){
                    $create_user_num = 0;
                    $login_user_num  = 0;
                }
                else{
                    $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
                    $login_user_num = $this->login_user_num($system_server_create_user_uid, $login_user_uid_arr);
                    $create_user_num = count($system_server_create_user_uid);
                }
                $new_final_arr[] = array('login_user_num'=>$login_user_num, 'create_user_num'=>$create_user_num);
            }
        }

        $final_create_user_num='';
        $final_login_user_num='';
        foreach($new_final_arr as $values){
            $final_create_user_num += $values['create_user_num'];
            $final_login_user_num += $values['login_user_num'];
        }
        foreach($system_arr as $v){
            $system = $v['number'];
            $finale_retention = round($final_login_user_num/$final_create_user_num*100, 2);
            $sql = "insert into {$retention_table} values ('', {$system}, 0, 0, {$final_login_user_num}, {$final_create_user_num},
                       '{$finale_retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
            $in = $this->indexM->query_sql($sql);
        }



        //按渠道所有服的留存
        foreach($channel_arr as $v1){
            $channel = $v1['number'];
            foreach($server_arr as $v3){
                $server = $v3['number'];
                $table = 'lt_account';
                $sql = "select game_uid from {$table} where channel={$channel} and create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
                $channel_server_create_user_uid_arr = $this->accountM->query_data($sql);
                foreach($channel_server_create_user_uid_arr as $v){
                    $channel_server_create_user_uid[] = $v->game_uid;
                }

                if(empty($channel_server_create_user_uid)){
                    $create_user_num = 0;
                    $login_user_num  = 0;
                }
                else{
                    $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
                    $login_user_num = $this->login_user_num($channel_server_create_user_uid, $login_user_uid_arr);
                    $create_user_num = count($channel_server_create_user_uid);
                }
                $channel_final_arr[] = array('login_user_num'=>$login_user_num, 'create_user_num'=>$create_user_num);
            }
        }

        $final_create_user_num='';
        $final_login_user_num='';
        foreach($channel_final_arr as $values){
            $final_create_user_num += $values['create_user_num'];
            $final_login_user_num += $values['login_user_num'];
        }
        foreach($channel_arr as $v){
            $channel = $v['number'];
            $finale_retention = round($final_login_user_num/$final_create_user_num*100, 2);
            $sql = "insert into {$retention_table} values ('', 0, {$channel}, 0, {$final_login_user_num}, {$final_create_user_num},
                       '{$finale_retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
            $in = $this->indexM->query_sql($sql);
        }




        //按系统按渠道所有服的留存
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
                    $table = 'lt_account';
                    $sql = "select game_uid from {$table} where system={$system} and channel={$channel} and create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
                    $system_channel_server_create_user_uid_arr = $this->accountM->query_data($sql);
                    foreach($system_channel_server_create_user_uid_arr as $v){
                        $system_channel_server_create_user_uid[] = $v->game_uid;
                    }

                    if(empty($system_channel_server_create_user_uid)){
                        $create_user_num = 0;
                        $login_user_num  = 0;
                    }
                    else{
                        $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                        $this->load->model('game_model_'.$server, 'gameM');
                        $login_user_uid_arr = $this->gameM->query_data($sql);
                        $login_user_num = $this->login_user_num($system_channel_server_create_user_uid, $login_user_uid_arr);
                        $create_user_num = count($system_channel_server_create_user_uid);
                    }
                    $system_channel_final_arr[] = array('login_user_num'=>$login_user_num, 'create_user_num'=>$create_user_num);
                }
            }
        }


        $final_create_user_num='';
        $final_login_user_num='';
        foreach($system_channel_final_arr as $values){
            $final_create_user_num += $values['create_user_num'];
            $final_login_user_num += $values['login_user_num'];
        }
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                $finale_retention = round($final_login_user_num/$final_create_user_num*100, 2);
                $sql = "insert into {$retention_table} values ('', {$system}, {$channel}, 0, {$final_login_user_num}, {$final_create_user_num},
                       '{$finale_retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
                $in = $this->indexM->query_sql($sql);
            }
        }
    }

   



   //七日留存
    public function seven_day_retention()
    {
        $more_day_before_yesterday_unix = strtotime(date("Y-m-d",strtotime("-7 day")));
        $day_before_yesterday_unix = strtotime(date('Ymd',strtotime('-2 day')));
        $yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));
        $retention_table = 'lt_sevenday_retention';


        $system_arr = $this->config->item('system');
        $channel_arr = $this->config->item('channel');
        $server_arr = $this->config->item('server');

        //按系统按渠道按服的留存
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
                    $table = 'lt_account';
                    $sql = "select game_uid from {$table} where system={$system} and channel={$channel} and area={$server} and create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
                    $create_user_uid_arr = $this->accountM->query_data($sql);
                    foreach($create_user_uid_arr as $v){
                        $create_user_uid[] = $v->game_uid;
                    }

                    if(empty($create_user_uid)){
                        $create_user_num = 0;
                        $login_user_num  = 0;
                        $retention = 0;
                    }
                    else{
                        $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                        $this->load->model('game_model_'.$server, 'gameM');
                        $login_user_uid_arr = $this->gameM->query_data($sql);
                        $retention = $this->retention($create_user_uid, $login_user_uid_arr);
                        $login_user_num = $this->login_user_num($create_user_uid, $login_user_uid_arr);
                        $create_user_num = count($create_user_uid);
                    }
                    $sql = "insert into {$retention_table} values ('', {$system}, {$channel}, {$server}, {$login_user_num}, {$create_user_num},
                           '{$retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
                    $in = $this->indexM->query_sql($sql);
                }
            }
        }


        //按系统按服的留存
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($server_arr as $v3){
                $server = $v3['number'];
                $table = 'lt_account';
                $sql = "select game_uid from {$table} where system={$system} and area={$server} and create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
                $create_user_uid_arr = $this->accountM->query_data($sql);
                foreach($create_user_uid_arr as $v){
                    $system_create_user_uid[] = $v->game_uid;
                }

                if(empty($system_create_user_uid)){
                    $create_user_num = 0;
                    $login_user_num  = 0;
                    $retention = 0;
                }
                else{
                    $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
                    $retention = $this->retention($system_create_user_uid, $login_user_uid_arr);
                    $login_user_num = $this->login_user_num($system_create_user_uid, $login_user_uid_arr);
                    $create_user_num = count($system_create_user_uid);
                }
                $sql = "insert into {$retention_table} values ('', {$system}, 0, {$server}, {$login_user_num}, {$create_user_num},
                       '{$retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
                $in = $this->indexM->query_sql($sql);
            }
        }

        //按渠道分服的留存
        foreach($channel_arr as $v2){
            $channel = $v2['number'];
            foreach($server_arr as $v3){
                $server = $v3['number'];
                $table = 'lt_account';
                $sql = "select game_uid from {$table} where channel={$channel} and area={$server} and create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
                $create_user_uid_arr = $this->accountM->query_data($sql);
                foreach($create_user_uid_arr as $v){
                    $channel_create_user_uid[] = $v->game_uid;
                }

                if(empty($channel_create_user_uid)){
                    $create_user_num = 0;
                    $login_user_num  = 0;
                    $retention = 0;
                }
                else{
                    $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
                    $retention = $this->retention($channel_create_user_uid, $login_user_uid_arr);
                    $login_user_num = $this->login_user_num($channel_create_user_uid, $login_user_uid_arr);
                    $create_user_num = count($channel_create_user_uid);
                }
                $sql = "insert into {$retention_table} values ('', 0, {$channel}, {$server}, {$login_user_num}, {$create_user_num},
                           '{$retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
                $in = $this->indexM->query_sql($sql);
            }
        }


        //按服的留存
        foreach($server_arr as $v3){
            $server = $v3['number'];
            $table = 'lt_account';
            $sql = "select game_uid from {$table} where create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
            $create_user_uid_arr = $this->accountM->query_data($sql);
            foreach($create_user_uid_arr as $v){
                $server_create_user_uid[] = $v->game_uid;
            }

            if(empty($server_create_user_uid)){
                $create_user_num = 0;
                $login_user_num  = 0;
                $retention = 0;
            }
            else{
                $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                $this->load->model('game_model_'.$server, 'gameM');
                $login_user_uid_arr = $this->gameM->query_data($sql);
                $retention = $this->retention($server_create_user_uid, $login_user_uid_arr);
                $login_user_num = $this->login_user_num($server_create_user_uid, $login_user_uid_arr);
                $create_user_num = count($server_create_user_uid);
            }
            $sql = "insert into {$retention_table} values ('', 0, 0, {$server}, {$login_user_num}, {$create_user_num},
                           '{$retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
            $in = $this->indexM->query_sql($sql);
        }


        //所有留存
        foreach($server_arr as $v3){
            $server = $v3['number'];
            $table = 'lt_account';
            $sql = "select game_uid from {$table} where create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
            $all_create_user_uid_arr = $this->accountM->query_data($sql);
            foreach($all_create_user_uid_arr as $v){
                $all_create_user_uid[] = $v->game_uid;
            }

            if(empty($all_create_user_uid)){
                $create_user_num = 0;
                $login_user_num  = 0;
            }
            else{
                $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                $this->load->model('game_model_'.$server, 'gameM');
                $login_user_uid_arr = $this->gameM->query_data($sql);
                $login_user_num = $this->login_user_num($all_create_user_uid, $login_user_uid_arr);
                $create_user_num = count($create_user_uid);
            }
            $final_arr[] = array('login_user_num'=>$login_user_num, 'create_user_num'=>$create_user_num);
        }

        $final_create_user_num='';
        $final_login_user_num='';
        foreach($final_arr as $values){
            $final_create_user_num += $values['create_user_num'];
            $final_login_user_num += $values['login_user_num'];
        }
        $finale_retention = round($final_login_user_num/$final_create_user_num*100, 2);
        $sql = "insert into {$retention_table} values ('', 0, 0, 0, {$final_login_user_num}, {$final_create_user_num},
                           '{$finale_retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
        $in = $this->indexM->query_sql($sql);




        //按系统所有的留存
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($server_arr as $v3){
                $server = $v3['number'];
                $table = 'lt_account';
                $sql = "select game_uid from {$table} where system={$system} and create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
                $system_server_create_user_uid_arr = $this->accountM->query_data($sql);
                foreach($system_server_create_user_uid_arr as $v){
                    $system_server_create_user_uid[] = $v->game_uid;
                }

                if(empty($system_server_create_user_uid)){
                    $create_user_num = 0;
                    $login_user_num  = 0;
                }
                else{
                    $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
                    $login_user_num = $this->login_user_num($system_server_create_user_uid, $login_user_uid_arr);
                    $create_user_num = count($system_server_create_user_uid);
                }
                $new_final_arr[] = array('login_user_num'=>$login_user_num, 'create_user_num'=>$create_user_num);
            }
        }

        $final_create_user_num='';
        $final_login_user_num='';
        foreach($new_final_arr as $values){
            $final_create_user_num += $values['create_user_num'];
            $final_login_user_num += $values['login_user_num'];
        }
        foreach($system_arr as $v){
            $system = $v['number'];
            $finale_retention = round($final_login_user_num/$final_create_user_num*100, 2);
            $sql = "insert into {$retention_table} values ('', {$system}, 0, 0, {$final_login_user_num}, {$final_create_user_num},
                       '{$finale_retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
            $in = $this->indexM->query_sql($sql);
        }



        //按渠道所有服的留存
        foreach($channel_arr as $v1){
            $channel = $v1['number'];
            foreach($server_arr as $v3){
                $server = $v3['number'];
                $table = 'lt_account';
                $sql = "select game_uid from {$table} where channel={$channel} and create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
                $channel_server_create_user_uid_arr = $this->accountM->query_data($sql);
                foreach($channel_server_create_user_uid_arr as $v){
                    $channel_server_create_user_uid[] = $v->game_uid;
                }

                if(empty($channel_server_create_user_uid)){
                    $create_user_num = 0;
                    $login_user_num  = 0;
                }
                else{
                    $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                    $this->load->model('game_model_'.$server, 'gameM');
                    $login_user_uid_arr = $this->gameM->query_data($sql);
                    $login_user_num = $this->login_user_num($channel_server_create_user_uid, $login_user_uid_arr);
                    $create_user_num = count($channel_server_create_user_uid);
                }
                $channel_final_arr[] = array('login_user_num'=>$login_user_num, 'create_user_num'=>$create_user_num);
            }
        }

        $final_create_user_num='';
        $final_login_user_num='';
        foreach($channel_final_arr as $values){
            $final_create_user_num += $values['create_user_num'];
            $final_login_user_num += $values['login_user_num'];
        }
        foreach($channel_arr as $v){
            $channel = $v['number'];
            $finale_retention = round($final_login_user_num/$final_create_user_num*100, 2);
            $sql = "insert into {$retention_table} values ('', 0, {$channel}, 0, {$final_login_user_num}, {$final_create_user_num},
                       '{$finale_retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
            $in = $this->indexM->query_sql($sql);
        }




        //按系统按渠道所有服的留存
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                foreach($server_arr as $v3){
                    $server = $v3['number'];
                    $table = 'lt_account';
                    $sql = "select game_uid from {$table} where system={$system} and channel={$channel} and create_time>={$more_day_before_yesterday_unix} and create_time<{$day_before_yesterday_unix}";
                    $system_channel_server_create_user_uid_arr = $this->accountM->query_data($sql);
                    foreach($system_channel_server_create_user_uid_arr as $v){
                        $system_channel_server_create_user_uid[] = $v->game_uid;
                    }

                    if(empty($system_channel_server_create_user_uid)){
                        $create_user_num = 0;
                        $login_user_num  = 0;
                    }
                    else{
                        $sql = "select distinct(game_uid) from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
                        $this->load->model('game_model_'.$server, 'gameM');
                        $login_user_uid_arr = $this->gameM->query_data($sql);
                        $login_user_num = $this->login_user_num($system_channel_server_create_user_uid, $login_user_uid_arr);
                        $create_user_num = count($system_channel_server_create_user_uid);
                    }
                    $system_channel_final_arr[] = array('login_user_num'=>$login_user_num, 'create_user_num'=>$create_user_num);
                }
            }
        }


        $final_create_user_num='';
        $final_login_user_num='';
        foreach($system_channel_final_arr as $values){
            $final_create_user_num += $values['create_user_num'];
            $final_login_user_num += $values['login_user_num'];
        }
        foreach($system_arr as $v1){
            $system = $v1['number'];
            foreach($channel_arr as $v2){
                $channel = $v2['number'];
                $finale_retention = round($final_login_user_num/$final_create_user_num*100, 2);
                $sql = "insert into {$retention_table} values ('', {$system}, {$channel}, 0, {$final_login_user_num}, {$final_create_user_num},
                       '{$finale_retention}', {$more_day_before_yesterday_unix}, {$today_unix})";
                $in = $this->indexM->query_sql($sql);
            }
        }
    }



    public function day_consume()
    {
        $yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));

        $server_arr = $this->config->item('server');
        $consume_mode_arr = $this->config->item('consume_mode');
        $consume_table = 'lt_use_goods_log';//1=>goods,2=>gold,3=>jewel,4=>dragon,5=>role

        //分服
        foreach($consume_mode_arr as $v1){
            $consume_mode = $v1['number'];
            foreach($server_arr as $v3){
                $server = $v3['number'];
                $this->load->model('log_model_'.$server, 'logM');
                $sql = "select count(distinct(uid)) as num from {$consume_table} where goods_type=3 and interface={$consume_mode} and time>{$yesterday_unix} and time<{$today_unix}";
                $user_num_arr = $this->logM->query_data($sql);
                foreach($user_num_arr as $v){
                    $user_num = $v->num;
                }
                if(empty($user_num)){
                    $user_num = 0;
                }
                $sql = "select count(1) as times from {$consume_table} where goods_type=3 and interface={$consume_mode} and time>{$yesterday_unix} and time<{$today_unix}";
                $buy_times_arr = $this->logM->query_data($sql);
                foreach($buy_times_arr as $v){
                    $buy_times = $v->times;
                }
                if(empty($buy_times)){
                    $buy_times = 0;
                }
                $sql = "select sum(goods_num) as jewel from {$consume_table} where goods_type=3 and interface={$consume_mode} and time>{$yesterday_unix} and time<{$today_unix}";
                $jewel_arr = $this->logM->query_data($sql);
                foreach($jewel_arr as $v){
                    $jewel = $v->jewel;
                }
                if(empty($jewel)){
                    $jewel = 0;
                }
                $now = time();
                $sql = "insert into lt_consume values ('', {$server}, {$consume_mode}, {$user_num}, {$buy_times}, {$jewel}, {$yesterday_unix}, {$now})";
                $in = $this->indexM->query_sql($sql);
            }
        }

        //所有服
        foreach($consume_mode_arr as $v1){
            $consume_mode = $v1['number'];
            $sql = "select buy_user_num,buy_times,jewel from lt_consume where consume_mode={$consume_mode} and date = {$yesterday_unix}";
            $arr = $this->indexM->query_data($sql);
            $buy_user_num = '';
            $buy_times = '';
            $jewel = '';
            foreach($arr as $values){
                $buy_user_num += $values->buy_user_num;
                $buy_times += $values->buy_times;
                $jewel += $values->jewel;
            }
            $now = time();
            $sql = "insert into lt_consume values ('', 0, {$consume_mode}, {$buy_user_num}, {$buy_times}, {$jewel}, {$yesterday_unix}, {$now})";
            $in = $this->indexM->query_sql($sql);
        }
    }



    public function day_reward()
    {
        $yesterday_unix = strtotime(date('Ymd',strtotime('-1 day')));
        $today_unix = strtotime(date('Ymd'));

        $server_arr = $this->config->item('server');
        $reward_table = 'lt_get_goods_log';//1=>goods,2=>gold,3=>jewel,4=>dragon,5=>role

        //分服
        foreach($server_arr as $v1){
            $server = $v1['number'];
            $sql = "select count(distinct(game_uid)) as num from lt_online where login=1 and online_time>={$yesterday_unix} and online_time<{$today_unix}";
            $this->load->model('game_model_'.$server, 'gameM');
            $login_user_num_arr = $this->gameM->query_data($sql);
            if(!empty($login_user_num_arr)){
                foreach($login_user_num_arr as $v){
                    $login_user_num = $v->num;
                }
            }
            else{
                $login_user_num = 0;
            }

            $this->load->model('log_model_'.$server, 'logM');
            $sql = "select sum(goods_num) as num from {$reward_table} where goods_type=2 and time>{$yesterday_unix} and time<{$today_unix}";
            $gold_num_arr = $this->logM->query_data($sql);
            if(!empty($gold_num_arr)){
                foreach($gold_num_arr as $v){
                    $gold_num = $v->num;
                }
            }
            else{
                $gold_num = 0;
            }
            $sql = "select sum(goods_num) as num from {$reward_table} where goods_type=3 and time>{$yesterday_unix} and time<{$today_unix}";
            $jewel_num_arr = $this->logM->query_data($sql);
            if(!empty($jewel_num_arr)){
                foreach($jewel_num_arr as $v){
                    $jewel_num = $v->num;
                }
            }
            else{
                $jewel_num = 0;
            }
            $sql = "select sum(goods_num) as num from {$reward_table} where goods_type=1 and time>{$yesterday_unix} and time<{$today_unix}";
            $material_num_arr = $this->logM->query_data($sql);
            if(!empty($material_num_arr)){
                foreach($material_num_arr as $v){
                    $material_num = $v->num;
                }
            }
            else{
                $material_num = 0;
            }
            $gold_average_num  = round($gold_num/$login_user_num, 2);
            $jewel_average_num = round($jewel_num/$login_user_num, 2);
            $material_average_num = round($material_num/$login_user_num, 2);

            $now = time();
            $sql = "insert into lt_reward values ('', {$server}, {$login_user_num}, {$gold_num}, {$gold_average_num}, {$jewel_num},
                    {$jewel_average_num}, {$material_num}, {$material_average_num}, {$yesterday_unix}, {$now})";
            $in = $this->indexM->query_sql($sql);
        }

        //所有服
        foreach($server_arr as $v1){
            $server  = $v1['number'];
            $sql = "select login_user,gold,gold_average,jewel,jewel_average,material,material_average from lt_reward where server={$server} and date = {$yesterday_unix}";
            $arr = $this->indexM->query_data($sql);
            $login_user = '';
            $gold = '';
            $gold_average = '';
            $jewel = '';
            $jewel_average = '';
            $material = '';
            $material_average = '';
            foreach($arr as $values){
                $login_user += $values->login_user;
                $gold += $values->gold;
                $gold_average += $values->gold_average;
                $jewel += $values->jewel;
                $jewel_average += $values->jewel_average;
                $material += $values->material;
                $material_average += $values->material_average;
            }
            $now = time();
            $sql = "insert into lt_reward values ('', 0, {$login_user}, {$gold}, {$gold_average}, {$jewel},
                    {$jewel_average}, {$material}, {$material_average}, {$yesterday_unix}, {$now})";
            $in = $this->indexM->query_sql($sql);
        }

    }



    public function loss_level()
    {
        $last_week_start_time = strtotime(date('Ymd',strtotime('-14 day')));//上周第一天
        $last_week_end_time = strtotime(date('Ymd',strtotime('-7 day')));//上周第七天，本周第一天
        $yesterday = strtotime(date('Ymd',strtotime('-1 day')));//本周最后一天
        $today = strtotime(date('Ymd'));

        $server_arr = $this->config->item('server');
        $now = time();

        //分服
        foreach($server_arr as $v1){
            $server = $v1['number'];
            $this->load->model('game_model_'.$server, 'gameM');
            $sql = "select game_uid from lt_online where login=1 and online_time>={$last_week_start_time} and online_time<{$last_week_end_time}";
            $last_week_login_user_arr = $this->gameM->query_data($sql);
            $sql = "select game_uid from lt_online where login=1 and online_time>={$last_week_end_time} and online_time<{$yesterday}";
            $this_week_login_user_arr = $this->gameM->query_data($sql);


            if(!empty($last_week_login_user_arr)){
                foreach($last_week_login_user_arr as $v){
                    $last_week_login_uid_arr[] = $v->game_uid;
                }
                $last_week_login_uid_arr = array_unique($last_week_login_uid_arr);
            }

            if(!empty($this_week_login_user_arr)){
                foreach($this_week_login_user_arr as $v){
                    $this_week_login_uid_arr[] = $v->game_uid;
                }
                $this_week_login_uid_arr = array_unique($this_week_login_uid_arr);
            }

            foreach($last_week_login_uid_arr as $v){
                if(!in_array($v, $this_week_login_uid_arr)){
                    $arr[] = $v;
                }
            }


            foreach($server_arr as $v){
                $server = $v['number'];
                $sql = "insert into lt_loss_level values ('', {$server}, 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,
                        {$yesterday}, {$now})";
                $in = $this->indexM->query_sql($sql);
            }

            if(!empty($arr)){
                $uid_string = '';
                foreach($arr as $v){
                    $uid_string .= $v.',';
                }
                $uid_string = substr($uid_string,0,strlen($uid_string)-1);

                $sql = "select level from lt_user where id in ({$uid_string})";
                $level_arr = $this->gameM->query_data($sql);

                foreach($level_arr as $v){
                    $sql = "update lt_loss_level set loss_level_{$v->level} = loss_level_{$v->level} + 1 where server = {$server} and createtime = {$now}";
                    $up = $this->indexM->query_sql($sql);
                }
            }
        }
        //所有服
        $sql = "select loss_level_1,loss_level_2,loss_level_3,loss_level_4,loss_level_5,loss_level_6,loss_level_7,loss_level_8,loss_level_9,loss_level_10,
            loss_level_11,loss_level_12,loss_level_13,loss_level_14,loss_level_15,loss_level_16,loss_level_17,loss_level_18,loss_level_19,loss_level_20,
            loss_level_21,loss_level_22,loss_level_23,loss_level_24,loss_level_25,loss_level_26,loss_level_27,loss_level_28,loss_level_29,loss_level_30,
            loss_level_31,loss_level_32,loss_level_33,loss_level_34,loss_level_35,loss_level_36,loss_level_37,loss_level_38,loss_level_39,loss_level_40,
            loss_level_41,loss_level_42,loss_level_43,loss_level_44,loss_level_45,loss_level_46,loss_level_47,loss_level_48,loss_level_49,loss_level_50
            from lt_loss_level where createtime = {$now}";
        $level_arr = $this->indexM->query_data($sql);

        $loss_level_1='';$loss_level_2='';$loss_level_3='';$loss_level_4='';$loss_level_5='';$loss_level_6='';$loss_level_7='';$loss_level_8='';$loss_level_9='';$loss_level_10='';
        $loss_level_11='';$loss_level_12='';$loss_level_13='';$loss_level_14='';$loss_level_15='';$loss_level_16='';
        $loss_level_17='';$loss_level_18='';$loss_level_19='';$loss_level_20='';$loss_level_21='';$loss_level_22='';
        $loss_level_23='';$loss_level_24='';$loss_level_25='';$loss_level_26='';$loss_level_27='';$loss_level_28='';
        $loss_level_29='';$loss_level_30='';$loss_level_31='';$loss_level_32='';$loss_level_33='';$loss_level_34='';
        $loss_level_35='';$loss_level_36='';$loss_level_37='';$loss_level_38='';$loss_level_39='';$loss_level_40='';
        $loss_level_41='';$loss_level_42='';$loss_level_43='';$loss_level_44='';$loss_level_45='';$loss_level_46='';
        $loss_level_47='';$loss_level_48='';$loss_level_49='';$loss_level_50='';

        foreach($level_arr as $v){
            $loss_level_1+=$v->loss_level_1;$loss_level_2+=$v->loss_level_2;$loss_level_3+=$v->loss_level_3;
            $loss_level_4+=$v->loss_level_4;$loss_level_5+=$v->loss_level_5;$loss_level_6+=$v->loss_level_6;
            $loss_level_7+=$v->loss_level_7;$loss_level_8+=$v->loss_level_8;$loss_level_9+=$v->loss_level_9;
            $loss_level_10+=$v->loss_level_10;$loss_level_11+=$v->loss_level_11;$loss_level_12+=$v->loss_level_12;
            $loss_level_13+=$v->loss_level_13;$loss_level_14+=$v->loss_level_14;$loss_level_15+=$v->loss_level_15;
            $loss_level_16+=$v->loss_level_16;$loss_level_17+=$v->loss_level_17;$loss_level_18+=$v->loss_level_18;
            $loss_level_19+=$v->loss_level_19;$loss_level_20+=$v->loss_level_20;$loss_level_21+=$v->loss_level_21;
            $loss_level_22+=$v->loss_level_22;$loss_level_23+=$v->loss_level_23;$loss_level_24+=$v->loss_level_24;
            $loss_level_25+=$v->loss_level_25;$loss_level_26+=$v->loss_level_26;$loss_level_27+=$v->loss_level_27;
            $loss_level_28+=$v->loss_level_28;$loss_level_29+=$v->loss_level_29;$loss_level_30+=$v->loss_level_30;
            $loss_level_31+=$v->loss_level_31;$loss_level_32+=$v->loss_level_32;$loss_level_33+=$v->loss_level_33;
            $loss_level_34+=$v->loss_level_34;$loss_level_35+=$v->loss_level_35;$loss_level_36+=$v->loss_level_36;
            $loss_level_37+=$v->loss_level_37;$loss_level_38+=$v->loss_level_38;$loss_level_39+=$v->loss_level_39;
            $loss_level_40+=$v->loss_level_40;$loss_level_41+=$v->loss_level_41;$loss_level_42+=$v->loss_level_42;
            $loss_level_43+=$v->loss_level_43;$loss_level_44+=$v->loss_level_44;$loss_level_45+=$v->loss_level_45;
            $loss_level_46+=$v->loss_level_46;$loss_level_47+=$v->loss_level_47;$loss_level_48+=$v->loss_level_48;
            $loss_level_49+=$v->loss_level_49;$loss_level_50+=$v->loss_level_50;
        }

        $sql = "insert into lt_loss_level values ('', 0,
{$loss_level_1},{$loss_level_2},{$loss_level_3},{$loss_level_4},{$loss_level_5},{$loss_level_6},{$loss_level_7},{$loss_level_8},
{$loss_level_9},{$loss_level_10},{$loss_level_11},{$loss_level_12},{$loss_level_13},{$loss_level_14},{$loss_level_15},
{$loss_level_16},{$loss_level_17},{$loss_level_18},{$loss_level_19},{$loss_level_20},{$loss_level_21},{$loss_level_22},
{$loss_level_23},{$loss_level_24},{$loss_level_25},{$loss_level_26},{$loss_level_27},{$loss_level_28},{$loss_level_29},
{$loss_level_30},{$loss_level_31},{$loss_level_32},{$loss_level_33},{$loss_level_34},{$loss_level_35},{$loss_level_36},
{$loss_level_37},{$loss_level_38},{$loss_level_39},{$loss_level_40},{$loss_level_41},{$loss_level_42},{$loss_level_43},
{$loss_level_44},{$loss_level_45},{$loss_level_46},{$loss_level_47},{$loss_level_48},{$loss_level_49},{$loss_level_50},
        {$yesterday}, {$now})";
        $this->indexM->query_sql($sql);
    }


    //stdClass Object转array
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


    private function retention($logs_0610, $logs_0611){
        $not_login_uid = array_diff($logs_0610, $logs_0611);
        $login_uid = count($logs_0610)-count($not_login_uid);
        return $next_retention_0610 = round($login_uid/count($logs_0610)*100 ,2);
    }

    private function login_user_num($logs_0610, $logs_0611){
        $not_login_uid = array_diff($logs_0610, $logs_0611);
        return $login_num = count($logs_0610)-count($not_login_uid);
    }
}
