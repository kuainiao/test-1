<?php if (!defined('BASEPATH'))exit('No direct script access allowed');
class Forpassword extends MY_Controller {
    public function __construct() {
        parent::__construct();
		
        $this->data['viewspath'] = base_url() . 'public/';
		$this->data['urlpath'] = 'http://sdkweb.3joy.cn/';
		$this->load->helper(array('form', 'url'));
        $this->load->model('Mindex_model', 'ml');
		 $this->load->model('uhome_model', 'um');
        $this->data['title'] = '中国游戏SDK';
		$this->data['page_title'] = 'SDKWEB';
		$this->data['class'] = 'm_logo2';
		$this->load->library('session');
		$this->data['code'] = strtolower($this->session->userdata('captcha'));
		include APPPATH . 'config/ucenter.php';
        include APPPATH . '../uc_client/client.php';

    }
		
	public function generate_password( $length = 8 ) {
            
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $password = '';
            for ( $i = 0; $i < $length; $i++ ){
                $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
            }
            return $password;
    }

    public function index() {
		header("Content-type:text/html;charset=utf-8");
    	if(!empty($_POST)){
             
                $email = $this->input->post('email', TRUE);
				$username = $this->input->post('username', TRUE);
				$code = $this->input->post('code', TRUE);
				if($this->data['code']!=$code){
					$this->_message('forpassword/index',"验证码错误",base_url('public/images/fail.png'));
				}
				if($user_info = uc_get_user($username)) { //获取用户数据
					list($uid, $username, $user_email) = $user_info;
					if($email!=$user_email){
						$this->_message('forpassword/index',"邮箱不正确",base_url('public/images/fail.png'));
					}else{
						$new_pwd = $this->generate_password();

						$email_title = '3卓网用户找回密码';
						$email_logo = base_url('public/images/logo.png');
						$email_saoma = base_url('public/images/saoma.jpg.png');
						$email_msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><link rel="stylesheet" type="text/css" href="all.css"><title>无标题文档</title><style type="text/css">@charset "utf-8";body{font-size:14px;margin:0;background:#e5dce1;padding:0;color:#4f4f4f;font-family:微软雅黑}.main p{margin:30px 0}a{color:#1f90ca;text-decoration:none}.main{height:100%;margin:auto;width:1000px}.main-text{padding:20px}.all{background:none repeat scroll 0 0 #fefefe;border-radius:10px;margin:20px 0;padding:10px 20px 20px}.main h2{font-weight:400}.warn{padding:24px 0 0;width:70%;display:inline-block}.warn a{padding:0 6px}.top{border-bottom:2px solid #eee;padding:5px 0 20px}.top img{border:none;height:42px;width:132px}.btn{border-radius:6px;color:#fff;font-size:18px;height:50px;line-height:50px;width:300px;border:none;background:#90c609;padding:16px 100px}.btn1 a:hover{background:#7bad0c}.app-download{float:right;margin-right:20px;margin-top:36px;padding:0 10px 10px}.app-download img{float:right;height:130px;margin-right:13px;width:130px}.app-download p{color:#888;margin:8px 0;font-size:15px}</style></head><body><div class="main"><div class="all"><div class="top"><img src="'.$email_logo.'"></div><div class="warn"><h2>您好:<b>'.$username.'</b></h2><p>您的新密码是：<b>'.$new_pwd.'</b></p><p>此为随机密码，请及时修改。</p><p>此邮件由3卓网用户管理中心自动发送，请勿回复。</p></div><div class="app-download"><p>扫描下载3卓游戏客户端</p><img src="'.$email_saoma.'"></div></div></div></body></html>';
						if(uc_user_edit($username, '', $new_pwd , '' , 1)==1 && $this->send_pwd_email($email,$username,$email_title,$email_msg)){
							//$uid = $this->data['user']['uid'];
							$this->um->update_password($uid,$username,md5(md5($new_pwd)));
							$this->_message(' ',"找回密码成功，您的新密码已发送到邮箱，请注意查收！",base_url('public/images/success.png'));
						}else{
							$this->_message(' ',"找回密码失败",base_url('public/images/fail.png'));
						}
						
					}
				} else {
					$this->_message('forpassword/index',"用户不存在",base_url('public/images/fail.png'));
                
				}
			
              
       }else{
                
                $this->load->view('forgetpassword.html', $this->data);
       }
       
    }
	
	 //发送邮件
    public function send_pwd_email($email,$username,$email_title,$email_msg){
            //var_dump(func_get_args());
            //$email_msg = 'test_centent'.date('Y-m-d H:i:s');
			

            $this->load->library('email');
			$config['mailtype'] = 'html';

			$this->email->initialize($config);
            $this->email->from('service@3joy.cn', '3卓网用户管理中心');
            $this->email->to($email); 
            $this->email->subject($email_title);
            $this->email->message($email_msg); 
            if ( ! $this->email->send()){
                return false;
            }else{
                return true;
            }
             
     }
	
	
	
	
	

	
    public function captcha(){
		header("Content-type:text/html;charset=utf-8");
        $this->load->helper('captcha');
        $vals = array(
            'word' => rand(1000, 9999),
            'img_path' => './public/captcha/',
            'img_url' => base_url().'public/captcha/',
            'font_path' => './public/fonts/songti.ttf',
            'img_width' => 85,
            'img_height' => 35,
            'expiration' => 7200
        );
        $cap = create_captcha($vals);
        $this->session->set_userdata(array('captcha' => $cap['word']));

    }
	
	/**
     * 
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