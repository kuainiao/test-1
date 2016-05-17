<?php if (!defined('BASEPATH'))exit('No direct script access allowed');
class Repassword extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->data['viewspath'] = base_url() . 'public/';
		$this->data['urlpath'] = 'http://www.3joy.cn/';
		$this->load->helper(array('form', 'url'));
        $this->load->model('Mindex_model', 'ml');
        $this->data['title'] = '中国手机游戏公会门户—3卓网(www.3joy.cn)';
		$this->data['page_title'] = '修改密码';
		$this->data['class'] = 'm_logo2';
		$this->load->library('session');
		$this->data['code'] = strtolower($this->session->userdata('captcha'));
		include APPPATH . 'config/ucenter.php';
        include APPPATH . '../uc_client/client.php';

    }

    public function index($username) {
    	if(!empty($_POST)){
                //验证码
                if($this->input->post('code')!=$this->data['code']){
                    $this->_message('repassword/index/'.$username,"验证码错误",base_url('public/images/fail.png'));
                }
				$user_info = $this->ml->get_userinfo('ucenter_members',array('username'=>$username));
				$uid = $user_info->uid;
                $oldpassword = $this->input->post('oldpassword');
                $newpassword = $this->input->post('newpassword');
                $emailnew = '';// $this->input->post('emailnew');
                $ignoreoldpw = 0; //1:忽略，更改资料不需要验证密码 0:(默认值) 不忽略，更改资料需要验证密码
                $questionid  = '';//$this->input->post('questionidnew');
                $answer = '';//$this->input->post('answernew');
                $ucresult = uc_user_edit($username,$oldpassword, $newpassword, $emailnew,$ignoreoldpw,$questionid,$answer);
                if($ucresult == -1) {
                    
                    $this->_message('repassword/index/'.$username,"旧密码不正确",base_url('public/images/fail.png'));
                } elseif($ucresult == -7) {
                    
                    $this->_message('repassword/index/'.$username,"没有做任何修改",base_url('public/images/fail.png'));
                }elseif($ucresult == -8) {
                    
                    $this->_message('repassword/index/'.$username,"该用户受保护无权限更改",base_url('public/images/fail.png'));
                }
				
				$data['NS_userpass'] = md5(md5($newpassword));
                $this->ml->edit_info('user',array('NS_uid'=>$uid),$data);
                $this->_message('mindex/index/'.$username,"{$username},更改资料成功{$ucresult}",base_url('public/images/success.png'));
            }else{
                //echo 33;
                $this->data['username'] = $username;
                $this->load->view('repassword', $this->data);
            }
       	//$this->load->view('repassword',$this->data);
    }

	//验证码
    public function captcha(){
        $this->load->helper('captcha');
        $vals = array(
            'word' => rand(1000, 9999),
            'img_path' => './public/captcha/',
            'img_url' => base_url().'public/captcha/',
            'font_path' => './public/fonts/songti.ttf',
            'img_width' => 85,
            'img_height' => 45,
            'expiration' => 7200
        );
        $cap = create_captcha($vals);
        $this->session->set_userdata(array('captcha' => $cap['word']));

    }
	
	/**
     * 信息提示
     *
     * @access  public
     * @param   string
     * @param   string
     * @param   string
     * @param   string
     * @return  html
     */
    public function _message($msg_url='',$msg_text='',$msg_img='',$msg_back=''){

        $this->data['msg_url'] =  $msg_url==''?base_url():site_url($msg_url);
        $this->data['msg_back'] = $msg_back==''?'点此返回':$msg_back;
        $this->data['msg_img'] = $msg_img == ''?base_url('public/images/success.png'):$msg_img;
        $this->data['msg_text'] = $msg_text==''?'操作失败':$msg_text;
        $this->load->view("message",$this->data);
        echo $this->output->get_output();
        exit();
    }
     
}

/* End of file index.php */
/* Location: ./application/controllers/index.php */