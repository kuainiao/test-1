<?php if (!defined('BASEPATH'))exit('No direct script access allowed');
class Fahao extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->data['viewspath'] = base_url() . 'public/';
		$this->data['urlpath'] = 'http://www.3joy.cn/';
		$this->load->model('Code_box_model', 'cm');
        $this->load->model('Mindex_model', 'ml');
        $this->data['title'] = '中国手机游戏公会门户—3卓网(www.3joy.cn)';
		$this->data['page_title'] = '礼包';
		$this->data['class'] = 'm_logo';
		$this->load->library('session');

    }

    public function index($username,$gameid) {
    	$game_info = $this->ml->get_gameinfo('game',array('pf_game_id'=>$gameid));
		//print_r($game_info);
		$pf_game_package = $game_info->pf_game_package;
		
    	$this->load->helper('page');
        $this->data['total_rows'] = $this->ml->get_count_data('code',array('pf_status'=>1,'pf_game_package'=>$pf_game_package)); //总条数
        page_html('fahao/index/'.$username.'/'.$gameid, $this->data['total_rows'] ,10 );
		$code = $this->ml->get_data('code',array('pf_status'=>1,'pf_game_package'=>$pf_game_package),$this->uri->segment(5,0),10);
	   	$code_num_total = array();
		$code_num_used = array();
		$code_game_category = array();
		$this->data['game_category'] = $this->ml->get_data('game_category'); //游戏分类
		$category_arr = array();
        foreach ($this->data['game_category'] as $value) {
            $category_arr[$value->pf_category_id] = array('pf_category_name' => $value->pf_category_name);
        }
		
	   	//print_r($code);
	   	foreach($code as $v){
	   		$code_num_total[$v->pf_id] = $this->ml->get_count_data('code_num',array('pf_code_id'=>$v->pf_id,'pf_status !='=>0));
	   		$code_num_used[$v->pf_id] = $this->ml->get_count_data('code_num',array('pf_code_id'=>$v->pf_id,'pf_status'=>2));
			$code_game_category[$v->pf_id] = $this->ml->get_data('game',array('pf_game_package'=>$v->pf_game_package));		
	   	}
		//print_r($code_game_category);
		foreach($code as $key=>$v){
			$code[$key]->pf_rates = ($code_num_used[$v->pf_id])/($code_num_total[$v->pf_id]);
			$code[$key]->pf_category = $category_arr[$code_game_category[$v->pf_id][0]->pf_category_id]['pf_category_name'];
			$code[$key]->pf_number_total = $code_num_total[$v->pf_id];
			$code[$key]->pf_number_last = $code_num_total[$v->pf_id]-$code_num_used[$v->pf_id];
		}
		//print_r($code);
		$code_ranking = $this->ml->array_sort($code, 'pf_rates', 'desc');
		$code_ranking_last = $this->ml->array_sort($code_ranking, 'pf_number_last', 'desc');
		$this->data['code'] = $code_ranking_last;
		$this->data['username'] = $username;
		$this->data['gameid'] = $gameid;
		
		
       	$this->load->view('fahao',$this->data);
    }
     
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */