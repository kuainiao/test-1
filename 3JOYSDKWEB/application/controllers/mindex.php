<?php if (!defined('BASEPATH'))exit('No direct script access allowed');
class Mindex extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->data['viewspath'] = base_url() . 'public/';
		$this->data['urlpath'] = 'http://www.3joy.cn/';
        $this->load->model('Mindex_model', 'ml');
        $this->data['title'] = '中国手机游戏公会门户—3卓网(www.3joy.cn)';
		$this->data['page_title'] = '账户';
		$this->data['class'] = 'm_logo';
		$this->load->library('session');

    }

    public function index($username) {
    	$this->data['user_info'] = $this->ml->get_userinfo('ucenter_members',array('username'=>$username));
		$user_info = $this->ml->get_userinfo('ucenter_members',array('username'=>$username));
		$uid = $user_info->uid;
		$this->data['coin'] = $this->ml->get_data('user_money',array('uid'=>$uid),null,null,'pfbbs');
		//print_r($this->data['coin']);
        $this->data['money'] = $this->ml->get_data('common_member_count',array('uid'=>$uid),null,null,'pfbbs');
		//print_r($this->data['money']);
		$this->data['uid'] = $uid;
       	$this->load->view('zhanghao',$this->data);
    }
     
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */