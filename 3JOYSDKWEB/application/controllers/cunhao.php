<?php if (!defined('BASEPATH'))exit('No direct script access allowed');
class Cunhao extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->data['viewspath'] = base_url() . 'public/';
		$this->data['urlpath'] = 'http://www.3joy.cn/';
		$this->load->model('Code_box_model', 'cm');
        $this->load->model('Mindex_model', 'ml');
        $this->data['title'] = '中国手机游戏公会门户—3卓网(www.3joy.cn)';
		$this->data['page_title'] = '存号箱';
		$this->data['class'] = 'm_logo';
		$this->load->library('session');

    }

    public function index($username,$gameid) {
        $this->load->helper('page');
		$user_info = $this->ml->get_userinfo('ucenter_members',array('username'=>$username));
		$uid = $user_info->uid;
        $this->data['total_rows'] = $this->cm->count_code_num($uid); //总条数
        page_html('cunhao/index/'.$username, $this->data['total_rows'] ,10 );
        $this->data['list'] = $this->cm->get_code_num($uid,10,$this->uri->segment(4,0));
        foreach ($this->data['list'] as $key=>$value){
            $code_info = $this->cm->get_code_info($value['pf_code_id']);
            $this->data['list'][$key]['type'] = $code_info['pf_type']; 
            $this->data['list'][$key]['title'] = $code_info['pf_title']; 
            $this->data['list'][$key]['pf_start_time'] = $code_info['pf_start_time'];
            $this->data['list'][$key]['pf_end_time'] = $code_info['pf_end_time'];
            $game_info = $this->cm->get_game($code_info['pf_game_package']);
            $this->data['list'][$key]['platform'] = $game_info['pf_platform'];
            $this->data['list'][$key]['pf_game_name'] = $game_info['pf_name'];
        }
        //print_r($this->data['list']);
        $this->data['username'] = $username;
		$this->data['gameid'] = $gameid;
       	$this->load->view('cunhao',$this->data);
    }
     
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */