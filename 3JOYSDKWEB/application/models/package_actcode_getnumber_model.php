<?php
class Package_actcode_getnumber_model extends CI_Model {
    protected $pfdb;
    function __construct() {
        parent::__construct();
        $this->pfdb = $this->load->database('default', TRUE);
    }
	
	/**
     * 获取数据信息
     *
     * @access  public
     * @param   int
     * @return  string
     */
    public function get_info($table=null,$where=array()){
        return $this->pfdb->get_where($this->pfdb->dbprefix($table),$where)->row();
    }
	
	 /**
     * 统计数据总条数
     */
    public function get_count_data($table,$where=null){
         $res = $this->pfdb->get_where($this->pfdb->dbprefix($table),$where)->num_rows();
         //echo $this->db->last_query().'<br>';
         return $res;
    }
	
}
