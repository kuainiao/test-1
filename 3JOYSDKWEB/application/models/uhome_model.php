<?php
class Uhome_model extends CI_Model {
    public $pfdb;
    public $pfbbs;
    public $sdkdb;
    function __construct() {
        parent::__construct();
        $this->pfdb = $this->load->database('default', TRUE);
        $this->pfbbs = $this->load->database('pfbbs', TRUE);
        $this->sdkdb = $this->load->database('sdkdb', TRUE);
    }
    /**
     * 获取数据信息
     */
    public function get_data($table='game',$where=array(),$page=null,$per_page=NULL,$db = 'pfdb'){
        if($db=='pfdb'){
            if(!empty($where)){
                $res = $this->pfdb->get_where($this->pfdb->dbprefix($table),$where,$per_page,$page)->result();
            }else{
                $res = $this->pfdb->get($this->pfdb->dbprefix($table),$per_page,$page)->result();
            }
            //echo $this->pfdb->last_query().'<br>';
            return $res;
        }else{
            if(!empty($where)){
                $res = $this->pfbbs->get_where($this->pfbbs->dbprefix($table),$where,$per_page,$page)->result();
            }else{ 
                $res = $this->pfbbs->get($this->pfbbs->dbprefix($table),$per_page,$page)->result();
            }
           //echo $this->pfdb->last_query().'<br>';
            return $res;
        }
            
    }

    public function get_count_data($table,$where = array(),$db = 'pfdb'){
        if($db == 'pfdb'){
            if(!empty($where)){
                $res = $this->pfdb->from($this->pfdb->dbprefix($table))->where($where)->count_all_results(); 
                //echo $this->pfdb->last_query().'<br>';
                return $res;
            }else{
                $res = $this->pfdb->count_all($this->pfdb->dbprefix($table)); 
                //echo $this->pfdb->last_query().'<br>';       
                return   $res;
            }
            
        }else{
            if(!empty($where)){
                $res = $this->pfbbs->from($this->pfbbs->dbprefix($table))->where($where)->count_all_results(); 
               // echo $this->pfdb->last_query().'<br>';
                return $res;
            }else{
                $res = $this->pfbbs->count_all($this->pfbbs->dbprefix($table)); 
               // echo $this->pfdb->last_query().'<br>';       
                return   $res;
            }
        }
        
    }
    /* *
     * 用户中心 统计消息条数
     * @access  public
     * @param   int $type 消息类型
     * @param   int $uid  当前用户ID
     * @return  integer
     */
    public function count_message($type , $uid){
                  $this->pfdb->from($this->pfdb->dbprefix('message'));
                  if($type!=0){
                      $this->pfdb->where(array('message.pf_type'=>$type,'message.pf_status'=>1,'message_new.pf_status >'=>0));
                  }else{
                      $this->pfdb->where(array('message.pf_status'=>1,'message_new.pf_status >'=>0));
                  }
                  $this->pfdb->where_in('message_new.pf_uid',array($uid)); // 0 表示系统发送消息 
                  $this->pfdb->join('message_new','message_new.pf_message_id = message.pf_id');
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
    public function get_message($type , $uid, $per_page , $page){
                  $this->pfdb->select('message_new.pf_message_id ,message.pf_title,message.pf_message ,message.pf_dateline,message_new.pf_status as new_status,message.pf_type as type,message.pf_uid as from_uid');
                  $this->pfdb->from($this->pfdb->dbprefix('message'));
                  if($type!=0){
                      $this->pfdb->where(array('message.pf_type'=>$type,'message.pf_status'=>1,'message_new.pf_status >'=>0));
                  }else{
                      $this->pfdb->where(array('message.pf_status'=>1,'message_new.pf_status >'=>0));
                  }
                  $this->pfdb->where_in('message_new.pf_uid',array($uid)); // 0 表示系统发送消息 
                  $this->pfdb->join('message_new','message_new.pf_message_id = message.pf_id');
                  $this->pfdb->limit($per_page, $page);
                  $this->pfdb->order_by('message.pf_dateline','desc');
                  $res = $this->pfdb->get()->result_array();
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
    //获取未读消息
    public function get_unread_message($uid){
                  $this->pfdb->select('message_new.pf_message_id ,message.pf_title,message.pf_message ,message.pf_dateline,message_new.pf_status as new_status,message.pf_type as type,message.pf_uid as from_uid');
                  $this->pfdb->from($this->pfdb->dbprefix('message'));
                  // if($type!=0){
                  //     $this->pfdb->where(array('message.pf_type'=>$type,'message.pf_status'=>1,'message_new.pf_status >'=>0));
                  // }else{
                      $this->pfdb->where(array('message.pf_status'=>1,'message_new.pf_status'=>1,'message_new.pf_uid'=>$uid));
                  //}
                  //$this->pfdb->where_in('message_new.pf_uid',array(0,$uid)); // 0 表示系统发送消息 
                  $this->pfdb->join('message_new','message_new.pf_message_id = message.pf_id');
                  //$this->pfdb->limit($per_page, $page);
                  $this->pfdb->order_by('message.pf_dateline','desc');
                  $res = $this->pfdb->get()->result_array();
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
    public function unread_message( $uid ){
                  $this->pfdb->select('message_new.pf_message_id ,message.pf_title,message.pf_message ,message.pf_dateline,message_new.pf_status as new_status,message.pf_type as type');
                  $this->pfdb->from($this->pfdb->dbprefix('message'));
                  $this->pfdb->where(array('message.pf_status'=>1,'message_new.pf_status'=>1));
                  
                  $this->pfdb->where_in('message_new.pf_uid',array(0,$uid)); // 0 表示系统发送消息 
                  $this->pfdb->join('message_new','message_new.pf_message_id = message.pf_id');
                  $this->pfdb->order_by('message.pf_dateline','desc');
                  $res = $this->pfdb->get()->result_array();
                  //echo $this->pfbbs->last_query().'<br>'; 
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
         $this->pfdb->select('*');
         $this->pfdb->from($this->pfdb->dbprefix('message'));
         $this->pfdb->where(array('pf_id'=>$id));
         $res = $this->pfdb->get()->result_array(); 
         //echo $this->pfdb->last_query().'<br>';
         //设为已读
         $this->pfdb->where(array('pf_message_id'=>$id,'pf_uid'=>$uid))->update($this->pfdb->dbprefix('message_new'), array('pf_status'=>2));
         //echo $this->pfdb->last_query().'<br>';
         return $res;
    }
    public function read_msg($uid){
         
         //设为已读
         $this->pfdb->where(array('pf_uid'=>$uid))->update($this->pfdb->dbprefix('message_new'), array('pf_status'=>2));
         
    }
    /* *
     * 用户中心 删除消息
     * @access  public
     * @param   int $id   消息ID
     * @param   int $uid  当前用户ID
     * @return  bool
     */
    public function del_msg($id,$uid){
         if ($this->pfdb->where(array('pf_message_id'=>$id,'pf_uid'=>$uid))->delete($this->pfdb->dbprefix('message_new'))){
                //echo $this->pfdb->last_query().'<br>';
                return true;
        }
        return false;
    }

    public function query_data($sql,$db='pfdb',$res = false){
        if($db == 'pfdb'){
             $query  = $this->pfdb->query($sql);
             //return $query->result();
        }else{
             $query = $this->pfbbs->query($sql);
             //return $query->result();
        }
        //echo $this->pfdb->last_query().'<br>';
        // echo $this->pfbbs->last_query().'<br>';
        if($res){
            return $query;
        }else{
            return $query->result();
        }
    }

    public function del_data($table,$where,$db = 'pfdb'){
        if($db == 'pfdb'){
            if($this->pfdb->delete($table, $where)){
                //echo $this->pfdb->last_query().'<br>';
                return true;
            }
        }else{
            if($this->pfbbs->delete($table, $where)){
                //echo $this->pfdb->last_query().'<br>';
                return true;
            }
        }
        return false;
    }
    public function update_password($uid,$username,$password){
        if($this->sdkdb->from($this->sdkdb->dbprefix('user'))->where(array('NS_uid'=>$uid))->count_all_results()){
            if ($this->sdkdb->where(array('NS_uid'=>$uid))->update($this->sdkdb->dbprefix('user'), array('NS_userpass'=>$password))){
                return true;
            }
        }else{
            if ($this->sdkdb->insert($this->sdkdb->dbprefix('user'),array('NS_uid'=>$uid,'NS_username'=>$username,'NS_create_time'=>time(),'NS_userpass'=>$password))){
                return true;
            }
        }
    }
    //编辑数据
    public function update_data($table , $data , $where ,$db = 'pfdb' ){
        if($db == 'pfdb'){
            if ($this->pfdb->where($where)->update($this->pfdb->dbprefix($table), $data)){
                //echo $this->pfdb->last_query().'<br>';
                return true;
            }
        }else{
            if ($this->pfbbs->where($where)->update($this->pfbbs->dbprefix($table), $data)){
                //echo $this->pfdb->last_query().'<br>';
                return true;
            }
        }
	return FALSE;	
   }
    //编辑数据
   public function update_sdk_data($table , $data , $where){
        
            if ($this->sdkdb->where($where)->update($this->sdkdb->dbprefix($table), $data)){
                //echo $this->pfdb->last_query().'<br>';
                return true;
            }
        
    return FALSE;   
   }
   //添加数据
   public function insert_data($table , $data , $db = 'pfdb',$guild=false){
       if($db == 'pfdb'){
            if ($this->pfdb->insert($this->pfdb->dbprefix($table),$data)){
                //echo $this->pfdb->last_query().'<br>';
                if($guild){
                    return $this->pfdb->insert_id();
                }
                return true;
            }
        }else{
            if ($this->pfbbs->insert($this->pfbbs->dbprefix($table),$data)){
                //echo $this->pfdb->last_query().'<br>';
                if($guild){
                    return $this->pfbbs->insert_id();
                }
                return true;
            }
        }
	return FALSE;	
   }
   //添加SDK用户表信息
   public function insert_sdk_user($data){
        
       if ($this->sdkdb->insert('sdk_user',$data)){
            //echo $this->pfdb->last_query().'<br>';
            return true;
        }
        
	return FALSE;	
   }
   public function insert_regsource_bbs($data){
        if ($this->pfbbs->insert($this->pfbbs->dbprefix('ucenter_regsource'),$data)){
            //echo $this->pfdb->last_query().'<br>';
            return true;
        }
        
	return FALSE;	
   }
    public function send_msg($table,$data,$uid){
        if ($this->pfdb->insert($this->pfdb->dbprefix($table), $data)){
            //echo $this->pfdb->last_query().'<br>';
            $message_id = $this->pfdb->insert_id();
            if($message_id){
                 $this->pfdb->insert($this->pfdb->dbprefix('message_new'), array('uid'=>$uid,'message_id'=>$message_id));
                 //echo $this->pfdb->last_query().'<br>';
                 return true;
             }
             return false;
        }
    }
    /**
     * 发送消息
     * @param int $params['type'] 消息类型 1活动消息2公会消息3发号消息4系统消息  
     * @param int $params['form_uid']  发送者ID  
     * @param string $params['title']  消息标题
     * @param string $params['message']  消息内容  
     * @param array() $params['to_uid']  发送给的用户ID
     * @return bool 
     */
    public function new_send_msg($params = array()){
                $message_array = array(
                'pf_type'  =>$params['type'],
                'pf_uid'      =>$params['form_uid'], 
                'pf_title'    =>$params['title'],
                'pf_message'  =>$params['message'],
                'pf_dateline' =>time(),
                'pf_status' =>1 //状态0删除 1正常  
            );
        if ($this->pfdb->insert($this->pfdb->dbprefix('message'), $message_array)){
            //echo $this->pfdb->last_query().'<br>';
            $message_id = $this->pfdb->insert_id();
            if($message_id){
                foreach ($params['to_uid'] as $uid){
                        $message_new_array = array(
                            'pf_uid' => $uid,
                            'pf_message_id' => $message_id,
                            'pf_type' => $params['type'], 
                            'pf_status' => 1 //消息状态 0删除 1未读 2已读 
                        );
                        $this->pfdb->insert($this->pfdb->dbprefix('message_new'),$message_new_array);
                        //echo $this->pfdb->last_query().'<br>';
                }
               return true;
             }
             return false;
        }
    }
     /**
     * 获取数据信息
     * @param string  $params['database'] 数据库名 
     * @param string  $params['table'] 表名
     * @param string  $params['select'] 查询的字段
     * @param string  $params['where'] 键值对数组格式的WHERE条件
     * @param array() $params['where_in'] ['field']字段名, ['value']对应的值
     * @param string  $params['like'] 键值对数组格式的LIKE条件
     * @param array() $params['join'] ['table']表名 ,['on']关联条件, ['type']关联类型包括(left, right, outer, inner, left outer, right outer.)
     * @param string  $params['order_by'] 排序方式 
     * @param string  $params['direction'] 排序方向 
     * @param string  $params['limit'] 查询数量
     * @param string  $params['offset'] 查询偏移量
     * @param string  $params['escape'] 查询的字段是否转义
     * @return array()
     */
    public function common_get_data($params = array()){
        
                $database  = (!empty($params['database'])) 	? $params['database']           : 'pfdb';
                $table     = (!empty($params['table'])) 	? $params['table']              : '';
                $select	   = (!empty($params['select'])) 	? $params['select']             : '*';
		$where 	   = (!empty($params['where']))         ? $params['where']              : false;
                $where_in  = (!empty($params['where_in']))      ? $params['where_in']           : false;
		$like 	   = (!empty($params['like']))          ? $params['like']               : false;
                $join      = (!empty($params['join']))          ? $params['join']               : false;
                $order_by  = (!empty($params['order_by']))      ? $params['order_by'] 		: false;
		$direction = (!empty($params['direction']))     ? $params['direction'] 		: false;
		$limit 	   = (!empty($params['limit'])) 	? intval($params['limit']) 	: 0;
		$offset    = (!empty($params['offset'])) 	? intval($params['offset'])     : 0;
		$escape    = (!empty($params['escape'])) 	? (bool)$params['escape'] 	: true;
                $this->$database->select($select,$escape);
                $this->$database->from($this->$database->dbprefix($table));
                if($where){
                    $this->$database->where($where);
                }
                if($where_in){
                    $this->$database->where_in($where_in['field'],$where_in['value']);
                }
                if($like){
                    $this->$database->like($like);
                }
                if($join){
                    $this->$database->join($join['table'],$join['on'],$join['type']);
                }
                if($limit || $offset){
                    $this->$database->limit($limit, $offset);
                }
                if($order_by){
                    $this->$database->order_by($order_by,$direction);
                }
                $res = $this->$database->get()->result_array();
                //echo $this->$database->last_query().'<br>'; 
                return $res;
    }
    /**
     * 统计记录条数
     * @param string  $params['database'] 数据库名 
     * @param string  $params['table'] 表名
     * @param string  $params['where'] 键值对数组格式的WHERE条件
     * @param array() $params['where_in'] ['field']字段名, ['value']对应的值
     * @param string  $params['like'] 键值对数组格式的LIKE条件
     * @param array() $params['join'] ['table']表名 ,['on']关联条件, ['type']关联类型包括(left, right, outer, inner, left outer, right outer.)
     * @return integer
     */
    public function common_count_data($params = array()){
        
                $database  = (!empty($params['database'])) 	? $params['database']           : 'pfdb';
                $table     = (!empty($params['table'])) 	? $params['table']              : '';
                $where 	   = (!empty($params['where']))         ? $params['where']                : false;
                $where_in  = (!empty($params['where_in']))      ? $params['where_in']           : false;
		$like 	   = (!empty($params['like']))          ? $params['like']                 : false;
                $join      = (!empty($params['join']))          ? $params['join']                 : false;
                $this->$database->from($this->$database->dbprefix($table));
                if($where){
                    $this->$database->where($where);
                }
                if($where_in){
                    $this->$database->where_in($where_in['field'],$where_in['value']);
                }
                if($like){
                    $this->$database->like($like);
                }
                if($join){
                    $this->$database->join($join['table'],$join['on'],$join['type']);
                }
                $res = $this->$database->count_all_results();
                //echo $this->$database->last_query().'<br>'; 
                return $res; 
    }
    /* *
     * 用户中心 查询加入公会信息
     * @access  public
     * @param   int $where['pf_uid']  用户ID
     * @param   string $where['pf_name']  用户名称
     * @return  integer
     */
    public function guild_join_info($where = array()){
                  $this->pfdb->select('*');
                  $this->pfdb->from($this->pfdb->dbprefix('guild_user'));
                  if(!empty($where['pf_uid'])){
                      $this->pfdb->where(array('pf_uid'=>$where['pf_uid']));
                  }
                  if(!empty($where['pf_name'])){
                      $this->pfdb->where(array('pf_name'=>$where['pf_name']));
                  }
                  $res = $this->pfdb->get()->row_array();
                 // echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
    /* *
     * 用户中心 查询创建公会信息
     * @access  public
     * @param   int $where['pf_admin_uid']  管理员ID
     * @param   string $where['pf_admin_name']  管理员名称
     * @param   int $where['pf_guild_id']   公会ID
     * @return  integer
     */
    public function guild_create_info($where = array()){
                  $this->pfdb->select('*');
                  $this->pfdb->from($this->pfdb->dbprefix('guild'));
                  if(!empty($where['pf_admin_uid'])){
                      $this->pfdb->where(array('pf_admin_uid'=>$where['pf_admin_uid']));
                  }
                  if(!empty($where['pf_admin_name'])){
                      $this->pfdb->where(array('pf_admin_name'=>$where['pf_admin_name']));
                  }
                  if(!empty($where['pf_guild_id'])){
                      $this->pfdb->where(array('pf_id'=>$where['pf_guild_id']));
                  }
                  $res = $this->pfdb->get()->row_array();
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
    /* *
     * 用户中心 解散公会
     * @access  public
     * @param   string $where['name'] 要解散的公会管理者名字
     * @param   string $where['uid'] 要解散的公会管理者ID
     * @param   string $reason 解散原因
     * @return  bool
     */
    public function guild_dissolve($where = array(),$reason = ''){
       
                    if(!empty($where['pf_admin_name'])){
                        $this->pfdb->where(array('pf_admin_name'=>$where['pf_admin_name']));
                    }
                    if(!empty($where['pf_admin_uid'])){
                        $this->pfdb->where(array('pf_admin_uid'=>$where['pf_admin_uid']));
                    }
                    if(!empty($where['pf_guild_uid'])){
                        $this->pfdb->where($where['pf_id']);
                    }
                    if ($this->pfdb->where($where)->update($this->pfdb->dbprefix('guild'), array('pf_status' =>0,'pf_reason'=>$reason))){
                        //echo $this->pfdb->last_query().'<br>';
                        return true;
                    }
                    return false;
    }
     /* *
     * 公会中心 删除公会会员
     * @access  public
     * @param   int $where['guild_id']   公会ID
     * @param   int $where['uid']   删除的会员ID
     * @return  bool
     */
    public function guild_user_del($where = array()){
                    if(!empty($where['uid'])){
                        $where = array(
                            'pf_guild_id' => $where['guild_id'],
                            'pf_uid'   => $where['uid']
                        );
                    }else{
                        $where = array(
                            'pf_guild_id' => $where['guild_id'],
                        );
                    }
                    if ($this->pfdb->where($where)->update($this->pfdb->dbprefix('guild_user'), array('pf_status' =>0))){
                        //echo $this->pfdb->last_query().'<br>';
                        return true;
                    }
                    return false;
    }
     /* *
     * 用户中心 统计公会人数
     * @access  public
     * @param   int $guild_id 公会ID
     * @return  integer
     */
    public function count_guild_user($guild_id){
                  $this->pfdb->from($this->pfdb->dbprefix('guild_user'));
                  $this->pfdb->where(array('pf_guild_id'=>$guild_id,'pf_type'=>1,'pf_status'=>1));
                  $res = $this->pfdb->count_all_results();
                  //echo $this->pfbbs->last_query().'<br>'; 
                  return $res; 
    }
    /* *
     * 用户中心 统计公会点击次数
     * @access  public
     * @param   int $guild_id 公会ID
     * @return  integer
     */
    public function count_guild_click($where = array()){
                  $this->pfdb->from($this->pfdb->dbprefix('guild_click'));
                  if(!empty($where['start_time']) && !empty($where['end_time'])){
                      $this->pfdb->where(array('pf_guild_id'=>$where['pf_guild_id'],'pf_dateline >'=>$where['start_time'],'pf_dateline <'=>$where['end_time']));
                  }else{
                      $this->pfdb->where(array('pf_guild_id'=>$where['pf_guild_id']));
                  }
                  $res = $this->pfdb->count_all_results();
                  //echo $this->pfbbs->last_query().'<br>';guild_click 
                  return $res; 
    }
    /* *
     * 用户中心 查询公会论坛版块信息
     * @access  public
     * @param   int $where['pf_uid']  用户ID
     * @param   string $where['pf_name']  用户名称
     * @return  integer
     */
    public function guild_bbs_info($where = array()){
                  $this->pfdb->select('*');
                  $this->pfdb->from($this->pfdb->dbprefix('guild_bbs'));
                  if(!empty($where['pf_guild_id'])){
                      $this->pfdb->where(array('pf_guild_id'=>$where['pf_guild_id']));
                  }
                  $res = $this->pfdb->get()->row_array();
                  //echo $this->pfdb->last_query().'<br>'; 
                  return $res; 
    }
    /* *
     * 用户中心 统计公会论坛版块帖子条数
     * @access  public
     * @param   int $where['fid'] 论坛版块ID
     * @return  integer
     */
    public function count_bbs_forum($where = array()){
                  $this->pfbbs->from($this->pfbbs->dbprefix('forum_post'));
                  if(!empty($where['fid'])){
                      $this->pfbbs->where(array('fid'=>$where['fid'],'first'=>1));
                  }
                  $res = $this->pfbbs->count_all_results();
                  //echo $this->pfbbs->last_query().'<br>';guild_click 
                  return $res; 
    }
    /* *
     * 用户中心 查询论坛版块帖子信息
     * @access  public
     * @param   int $where['pf_uid']  用户ID
     * @param   string $where['pf_name']  用户名称
     * @return  integer
     */
    public function bbs_forum_info($where = array(),$params = array()){
                  $limit     = (!empty($params['limit'])) 	? intval($params['limit']) 	: 0;
		  $offset    =  (!empty($params['offset'])) 	? intval($params['offset'])     : 0;
                  $this->pfbbs->select('*');
                  $this->pfbbs->from($this->pfbbs->dbprefix('forum_post'));
                  if(!empty($where['fid'])){
                      $this->pfbbs->where(array('fid'=>$where['fid'],'first'=>1));
                  }
                  if($limit || $offset){
                    $this->pfbbs->limit($limit, $offset);
                  }
                  $res = $this->pfbbs->get()->result_array();
                  //echo $this->pfbbs->last_query().'<br>'; 
                  return $res; 
    }
}
