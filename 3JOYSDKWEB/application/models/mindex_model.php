<?php
class Mindex_model extends CI_Model {
    protected $pfdb;
	protected $pfbbs;
	protected $sdkdb;
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
	
	/**
     * 获取数据信息
     *
     * @access  public
     * @param   int
     * @return  string
     */
    public function get_userinfo($table=null,$where=array()){
        return $this->pfbbs->get_where($this->pfbbs->dbprefix($table),$where)->row();
    }
	
	/**
     * 获取数据信息
     *
     * @access  public
     * @param   int
     * @return  string
     */
    public function get_gameinfo($table=null,$where=array()){
        return $this->pfdb->get_where($this->pfdb->dbprefix($table),$where)->row();
		//echo $this->pfdb->last_query().'<br>';
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
   
    /**
     * 统计数据总条数
     */
    public function get_count_data($table,$where=null){
         $res = $this->pfdb->get_where($this->pfdb->dbprefix($table),$where)->num_rows();
         //echo $this->db->last_query().'<br>';
         return $res;
    }
	
	//数组排序
	public function array_sort($arr,$keys,$type='asc'){ 
		$keysvalue = $new_array = array();
		foreach ($arr as $k=>$v){
			$keysvalue[$k] = $v->$keys;
		}
		if($type == 'asc'){
			asort($keysvalue);
		}else{
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k=>$v){
			$new_array[$k] = $arr[$k];
		}
		return $new_array; 
	}
	
	/**
     * 修改信息
     *
     * @access  public
     * @param   object
     * @param   array
     * @return  bool
     */
	public function edit_info($table,$where,$data)
	{
		if ($this->sdkdb->where($where)->update($this->sdkdb->dbprefix($table), $data))
		{
			return TRUE;
		}
		return FALSE;
	}
	
}
