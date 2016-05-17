<?php
class Code_box_model extends CI_Model {
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
    public function count_code_num($uid){
                  $this->pfdb->from($this->pfdb->dbprefix('code_num'));
                  $this->pfdb->where(array('pf_uid'=>$uid,'pf_status'=>2));
                  $res = $this->pfdb->count_all_results();
                  //echo $this->pfbbs->last_query().'<br>'; 
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
    public function get_code_num($uid , $per_page , $page){
                  $this->pfdb->select('*');
                  $this->pfdb->from($this->pfdb->dbprefix('code_num'));
                  $this->pfdb->where(array('pf_uid'=>$uid,'pf_status'=>2));
                  
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
                  $this->pfdb->where(array('pf_status !='=>0,'pf_id'=>$pf_id));
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
    public function get_game($package){
                  $this->pfdb->select('*');
                  $this->pfdb->from($this->pfdb->dbprefix('game'));
                  $this->pfdb->where(array('pf_game_package'=>$package));
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
    public function get_game_file($package){
                  $this->pfdb->select('*');
                  $this->pfdb->from($this->pfdb->dbprefix('game_file'));
                  $this->pfdb->where(array('pf_game_package'=>$package));
                  $res = $this->pfdb->get()->result_array();
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
   
 
   
    
}
