<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 发号中心 后台控制器基类
 * @subpackage  core
 * @category    core
 */		
abstract class MY_Controller extends CI_Controller{
    /**
     * 构造函数
     *
     * @access  public
     * @return  void
     */
    public function __construct(){
		parent::__construct();
                ini_set('date.timezone','Asia/Shanghai');
                //$this->output->enable_profiler(TRUE);
                $this->load->library('session');
                //$this->data['user'] = array('uid'=>1,'username'=>'admin');//$this->session->userdata('user'); 本机测试
                $this->data['user'] = $this->session->userdata('user'); 
    }
    // ------------------------------------------------------------------------
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
                 $this->data['msg_img'] = $msg_img == ''?base_url('public/images/index/icon/tuichu.png'):$msg_img;
                 $this->data['msg_text'] = $msg_text==''?'操作失败':$msg_text;
                 $this->load->view("sys_message.html",$this->data);
                 echo $this->output->get_output();
		 exit();
    }
    
    // ------------------------------------------------------------------------

}

/* End of file MY_Controller.php */
/* Location: ./applicarion/core/MY_Controller.php */
	