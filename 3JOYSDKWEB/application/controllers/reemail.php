<?php if (!defined('BASEPATH'))exit('No direct script access allowed');
class Reemail extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->data['viewspath'] = base_url() . 'public/';
		$this->data['urlpath'] = 'http://www.3joy.cn/';
		$this->load->helper(array('form', 'url'));
        $this->load->model('Mindex_model', 'ml');
        $this->data['title'] = '中国手机游戏公会门户—3卓网(www.3joy.cn)';
		$this->data['page_title'] = '更换邮箱';
		$this->data['class'] = 'm_logo2';
		$this->load->library('session');
		$this->data['code'] = strtolower($this->session->userdata('captcha'));
		include APPPATH . 'config/ucenter.php';
        include APPPATH . '../uc_client/client.php';

    }

    public function index($username) {
    	//$user_info = $this->ml->get_userinfo('ucenter_members',array('username'=>$username));
		//$uid = $user_info->uid;
    	if(!empty($_POST)){
                //验证码
                if($this->input->post('code')!=$this->data['code']){
                    $this->_message('reemail/index/'.$username,"验证码错误",base_url('public/images/fail.png'));
                }
                $emailnew = $this->input->post('new_email');
                $oldpassword = $this->input->post('password');

                    
                    $newpassword = '';
                    $ucresult = uc_user_edit($username,$oldpassword, $newpassword, $emailnew);
                    if($ucresult == -1) {
                        $this->_message('reemail/index/'.$username,'旧密码不正确',base_url('public/images/fail.png'));
                    } elseif($ucresult == -4) {
                        $this->_message('reemail/index/'.$username,'Email 格式有误',base_url('public/images/fail.png'));
                    } elseif($ucresult == -5) {
                        $this->_message('reemail/index/'.$username,'Email 不允许注册',base_url('public/images/fail.png'));
                    } elseif($ucresult == -6) {
                         $this->_message('reemail/index/'.$username,'该 Email 已经被注册',base_url('public/images/fail.png'));
                        
                    }elseif($ucresult == -7) {
                        
                        $this->_message('reemail/index/'.$username,'没有做任何修改',base_url('public/images/fail.png'));
                    }elseif($ucresult == -8) {
                        $this->_message('reemail/index/'.$username,'该用户受保护无权限更改',base_url('public/images/fail.png'));
                    }
                    $insert_data = array(
                        'pf_username'=>$username,
                        'pf_uid'=>$uid,
                        'pf_edit_email'=>$emailnew,
                    );
                    $this->ml->insert_data('user_email',$insert_data);
                    $this->_message('mindex/index/'.$username,"{$username},更改邮箱成功{$ucresult}，请注意查收邮件！",base_url('public/images/success.png'));
        }else{
			$this->data['username'] = $username;
			$this->data['get_user'] = uc_get_user($username);
			//print_r($this->data['get_user']);
            $this->load->view('reemail', $this->data);

        }
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