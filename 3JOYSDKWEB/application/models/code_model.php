<?php
class Code_model extends CI_Model {
    protected $pfdb;
    protected $pfbbs;
    function __construct() {
        parent::__construct();
        $this->pfdb = $this->load->database('default', TRUE);
        $this->pfbbs = $this->load->database('pfbbs', TRUE);
    }
    /* *
     * 用户中心 统计消息条数
     * @access  public
     * @param   int $type 消息类型
     * @param   int $uid  当前用户ID
     * @return  integer
     */
    public function count_code($type){
                  $this->pfdb->from($this->pfdb->dbprefix('code'));
                  if($type!=0){
                      $this->pfdb->where(array('pf_type'=>$type,'pf_status'=>1));
                  }
                  $res = $this->pfdb->count_all_results();
                  //echo $this->pfbbs->last_query().'<br>'; 
                  return $res; 
    }
	
	//统计游戏对应的礼包总数
	public function count_game_code($package,$type){
                  $this->pfdb->from($this->pfdb->dbprefix('code'));
                  if($type==0){
                      $this->pfdb->where(array('pf_game_package'=>$package,'pf_status'=>1));
                  }else{
                      $this->pfdb->where(array('pf_game_package'=>$package,'pf_type'=>$type,'pf_status'=>1));
                  }
                  $res = $this->pfdb->count_all_results();
                  //echo $this->pfbbs->last_query().'<br>'; 
                  return $res; 
    }
	
	public function get_game_code($id,$type , $per_page , $page){
                  $this->pfdb->select('*');
                  $this->pfdb->from($this->pfdb->dbprefix('code'));
                  if($type==0){
                      $this->pfdb->where(array('pf_id'=>$id,'pf_status'=>1));
                  }else{
                      $this->pfdb->where(array('pf_id'=>$id,'pf_type'=>$type,'pf_status'=>1));
                  }
                  
                  $this->pfdb->order_by('pf_id','desc');
                  $this->pfdb->limit($per_page, $page);
                  $res = $this->pfdb->get()->result_array();
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
    /* *
     * 用户中心 获取消息
     * @access  public
     * @param   int $type 消息类型
     * @param   int $uid  当前用户ID
     * @param   int $per_page 分页每页条数
     * @param   int $page 分页开始条数
     * @return  array()
     */
    public function get_code($type , $per_page , $page){
                  $this->pfdb->select('*');
                  $this->pfdb->from($this->pfdb->dbprefix('code'));
                  if($type!=0){
                      $this->pfdb->where(array('pf_type'=>$type,'pf_status'=>1));
                  }else{
                      $this->pfdb->where(array('pf_status'=>1));
                  }
                  $this->pfdb->order_by('pf_id','desc');
                  $this->pfdb->limit($per_page, $page);
                  $res = $this->pfdb->get()->result_array();
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
     /* *
     * 用户中心 获取消息
     * @access  public
     * @param   int $type 消息类型
     * @param   int $uid  当前用户ID
     * @param   int $per_page 分页每页条数
     * @param   int $page 分页开始条数
     * @return  array()
     */
    public function get_code_info($pf_id){
                  $this->pfdb->select('*');
                  $this->pfdb->from($this->pfdb->dbprefix('code'));
                  $this->pfdb->where(array('pf_status'=>1,'pf_id'=>$pf_id));
                  $res = $this->pfdb->get()->result_array();
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
    /* *
     * 用户中心 获取消息
     * @access  public
     * @param   int $type 消息类型
     * @param   int $uid  当前用户ID
     * @param   int $per_page 分页每页条数
     * @param   int $page 分页开始条数
     * @return  array()
     */
    public function get_game($package){
                  $this->pfdb->select('*');
                  $this->pfdb->from($this->pfdb->dbprefix('game'));
                  $this->pfdb->where(array('pf_game_package'=>$package));
                  $res = $this->pfdb->get()->result_array();
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
    /* *
     * 用户中心 获取消息
     * @access  public
     * @param   int $type 消息类型
     * @param   int $uid  当前用户ID
     * @param   int $per_page 分页每页条数
     * @param   int $page 分页开始条数
     * @return  array()
     */
    public function get_game_file($package){
                  $this->pfdb->select('*');
                  $this->pfdb->from($this->pfdb->dbprefix('game_file'));
                  $this->pfdb->where(array('pf_game_package'=>$package));
                  $res = $this->pfdb->get()->result_array();
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
     /* *
     * 用户中心 获取消息
     * @access  public
     * @param   int $type 消息类型
     * @param   int $uid  当前用户ID
     * @param   int $per_page 分页每页条数
     * @param   int $page 分页开始条数
     * @return  array()
     */
    public function get_game_bbs($game_name){
                  $this->pfbbs->select('fid,name');
                  $this->pfbbs->from($this->pfbbs->dbprefix('forum_forum'));
                  $this->pfbbs->where_in('name',$game_name);
                  $res = $this->pfbbs->get()->row_array();
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
    /* *
     * 用户中心 获取消息
     * @access  public
     * @param   int $type 消息类型
     * @param   int $uid  当前用户ID
     * @param   int $per_page 分页每页条数
     * @param   int $page 分页开始条数
     * @return  array()
     */
    public function get_code_num_count($pf_id,$status=3){
                  $this->pfdb->select('*');
                  $this->pfdb->from($this->pfdb->dbprefix('code_num'));
                  if($status==3){
                      $this->pfdb->where(array('pf_code_id'=>$pf_id,'pf_status !='=>0));
                  }else{
                      $this->pfdb->where(array('pf_code_id'=>$pf_id,'pf_status'=>$status));
                  }
                  $res = $this->pfdb->count_all_results();
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
     /* *
     * 用户中心 获取消息
     * @access  public
     * @param   int $type 消息类型
     * @param   int $uid  当前用户ID
     * @param   int $per_page 分页每页条数
     * @param   int $page 分页开始条数
     * @return  array()
     */
    public function get_code_num($pf_id , $uid = null){
                  $this->pfdb->select('*');
                  $this->pfdb->from($this->pfdb->dbprefix('code_num'));
                  if(!empty($uid)){
                      $this->pfdb->where(array('pf_code_id'=>$pf_id,'pf_status'=>2,'pf_uid'=>$uid));
                  }else{
                      $this->pfdb->where(array('pf_code_id'=>$pf_id,'pf_status'=>1));
                  }
                  
                  $res = $this->pfdb->get()->row_array();
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
     /* *
     * 用户中心 获取消息
     * @access  public
     * @param   int $type 消息类型
     * @param   int $uid  当前用户ID
     * @param   int $per_page 分页每页条数
     * @param   int $page 分页开始条数
     * @return  array()
     */
    public function update_code_num($pf_id,$code,$uid,$username){
                  $res = $this->pfdb->where(array('pf_id'=>$pf_id,'pf_number'=>$code))->update($this->pfdb->dbprefix('code_num'), array('pf_status'=>2,'pf_uid'=>$uid,'pf_username'=>$username));
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
    /* *
     * 用户中心 查看消息详情
     * @access  public
     * @param   int $id   消息ID
     * @param   int $uid  当前用户ID
     * @return  bool
     */
    public function info_msg($id,$uid){
         //查询消息
         $this->pfbbs->select('*');
         $this->pfbbs->from($this->pfbbs->dbprefix('message'));
         $this->pfbbs->where(array('id'=>$id));
         $res = $this->pfbbs->get()->result_array(); 
         echo $this->pfdb->last_query().'<br>';
         //设为已读
         $this->pfbbs->where(array('message_id'=>$id,'uid'=>$uid))->update($this->pfbbs->dbprefix('message_new'), array('status'=>2));
         //echo $this->pfdb->last_query().'<br>';
         return $res;
    }
    /* *
     * 用户中心 删除消息
     * @access  public
     * @param   int $id   消息ID
     * @param   int $uid  当前用户ID
     * @return  bool
     */
    public function del_msg($id,$uid){
         if ($this->pfbbs->where(array('message_id'=>$id,'uid'=>$uid))->delete($this->pfbbs->dbprefix('message_new'))){
                //echo $this->pfdb->last_query().'<br>';
                return true;
        }
        return false;
    }
}
