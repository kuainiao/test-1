<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->data['viewspath'] = base_url() . APPPATH . 'views';
        $this->load->model('test_model', 'testM');
        $this->data['title'] = '龙塔数据后台';
        $this->data['urlpath'] = base_url().'/';
        $this->load->library('session');
        //$this->data['user'] = $this->session->userdata('user');
		//$this->load->model('account_model', 'accountM');
    }
	
	public function testcase(){
		$sql = "select *    from account where name='test26' limit 8";
					
		$user_arr = $this->testM->query_data($sql);
		var_dump($user_arr);
	
	}
	
	function getminute(){
		$d= '2015-03-15';
        $tarr = array();
        for($i=0;$i<24;$i++){
            if($i < 10){
				//2015-12-1503
				//2015-12-1514
                $t = $d.' 0'.$i;
            }else{
                $t = $d.' '.$i;
            }
			//2015-12-1504:03
			//2015-12-1505:59
            for($j=0;$j<60;$j++){
                if($j < 10){
                    $t1 = $t.':0'.$j;
                }else{
                    $t1 = $t.':'.$j;
                }
                $tarr[] = $t1;
            } 
        }
		print_r($tarr);
      
    }
}