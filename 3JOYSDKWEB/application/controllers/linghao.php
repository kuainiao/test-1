<?php if (!defined('BASEPATH'))exit('No direct script access allowed');
class Linghao extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->data['viewspath'] = base_url() . 'public/';
		$this->data['urlpath'] = 'http://www.3joy.cn/';
		$this->load->model('Package_actcode_getnumber_model', 'pagm');
		$this->load->model('Code_model', 'cm');
        $this->load->model('Mindex_model', 'ml');
        $this->data['title'] = '中国手机游戏公会门户—3卓网(www.3joy.cn)';
		$this->data['page_title'] = '礼包';
		$this->data['class'] = 'm_logo2';
		$this->load->library('session');

    }

    public function index($id=0) {
        $code_info = $this->pagm->get_info('code',array('pf_id'=>$id));
		$code_num_total = $this->pagm->get_count_data('code_num',array('pf_code_id'=>$code_info->pf_id,'pf_status !='=>0));
	   	$code_num_used = $this->pagm->get_count_data('code_num',array('pf_code_id'=>$code_info->pf_id,'pf_status'=>2));
		$last_rates = round(($code_num_total-$code_num_used)/$code_num_total,2)*100;
		$code_game = $this->pagm->get_info('game',array('pf_name'=>$code_info->pf_game_name));
		$code_game_file = $this->pagm->get_info('game_file',array('pf_game_package'=>$code_info->pf_game_package));
		$uid = 14;
        if($uid){
	        $is_get_num = $this->cm->get_code_num($id,$uid);
	        $code_num = empty($is_get_num)?'':$is_get_num['pf_number']; 
        }else{
        	$code_num = '';
        }
		$this->data['code_num'] = $code_num;
		$this->data['code_info'] = $code_info;
		$this->data['rates'] = $last_rates;
		$this->data['code_num_total'] = $code_num_total;
		$this->data['code_num_unused'] = $code_num_total-$code_num_used;
		$this->data['iconurl'] = $code_game_file->pf_iconurl;
		$this->data['upload_logo'] = $code_game->pf_upload_logo;
		$this->data['pf_game_name'] = $code_game->pf_name;
		
    	$this->load->view('linghao', $this->data);
    }

	//领取礼包号码
    public function code_get(){
        $pf_id = $this->input->post('id', TRUE); //ID
        //if(!$this->data['user']){
        //    echo json_encode(array('code'=>0,'message'=>'请先登录才能领取！'));
        //    exit;
        //}
        $code_info = $this->cm->get_code_info($pf_id);
        if(!$code_info){
            //$this->_message('message/index','请求错误！');
			echo json_encode(array('code'=>0,'message'=>'请求错误！'));
            exit; 
        }
        $uid = 14;
		$user_info = $this->ml->get_userinfo('ucenter_members',array('uid'=>$uid));
		$username = $user_info->username;
        $code_num_info = $this->cm->get_code_num($pf_id);
        $code_num = $code_num_info['pf_number'];
        $code_id  = $code_num_info['pf_id'];
        $code_type  = $code_info[0]['pf_type'];
        if($code_type==1){
            $message = '激活码';
        }elseif($code_type==2){
            $message = '公会礼包';
        }else{
            $message = '新服礼包';
        }
        $is_get_num = $this->cm->get_code_num($pf_id,$uid);
        if(!empty($is_get_num)){
            echo json_encode(array('code'=>0,'message'=>'您已领取过'.$message.'，不能重复领取！'));
            exit;
        }
        $update_num = $this->cm->update_code_num($code_id,$code_num,$uid,$username);
        if($update_num){
            echo json_encode(array('code'=>1,'message'=>'您已领取'.$message.'成功,请到存号箱查看激活码!'));
            exit; 
        }else{
            echo json_encode(array('code'=>0,'message'=>'领取'.$message.'失败！'));
            exit; 
        }
    }
     
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */