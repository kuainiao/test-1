<?php
class wz_model extends CI_Model {
    public $wzdb;
    public function __construct() {
        parent::__construct();
        $this->wzdb = $this->load->database('wzdb',TRUE);
    }
    /**
     * 获取数据信息
     */
    public function get_data($table='',$where=array(),$page=null,$per_page=NULL,$db = 'wzdb'){
        if($db=='wzdb'){
            if(!empty($page) && !empty($per_page)){
                if(!empty($where)){
                    $res = $this->wzdb->get_where($this->wzdb->dbprefix($table),$where,$per_page,$page)->result();
                }else{
                    $res = $this->wzdb->get($this->wzdb->dbprefix($table),$per_page,$page)->result();
                }
                //echo $this->wzdb->last_query().'<br>';
            }
            else{
                if(!empty($where)){
                    $res = $this->wzdb->get_where($this->wzdb->dbprefix($table),$where)->result();
                }else{
                    $res = $this->wzdb->get($this->wzdb->dbprefix($table))->result();
                }
                //echo $this->wzdb->last_query().'<br>';
            }
            return $res;
        }
    }

    /**
     * 获取排序数据信息
     */
    public function get_data_order_by($table=null,$where=array(),$order_by=null,$desc=null,$page=null,$per_page=null,$db='wzdb'){
        if($db=='wzdb'){
            if(!empty($per_page)){
                if(!empty($where)){
                    $res = $this->wzdb->select('*')->from($table)->where($where)->order_by($order_by,$desc)->limit($per_page,$page)->get()->result();
                }else{
                    $res = $this->wzdb->select('*')->from($table)->order_by($order_by,$desc)->limit($per_page,$page)->get()->result();
                }
                //echo $this->wzdb->last_query().'<br>';
            }
            else{
                if(!empty($where)){
                    $res = $this->wzdb->select('*')->from($table)->where($where)->order_by($order_by,$desc)->get()->result();
                }else{
                    $res = $this->wzdb->select('*')->from($table)->order_by($order_by,$desc)->get()->result();
                }
                //echo $this->wzdb->last_query().'<br>';
            }

            return $res;
        }
    }


    public function get_sum($table,$sum_filed,$where,$db='wzdb')
    {
        if($db=='wzdb'){
            $res = $this->wzdb->select_sum($sum_filed)->where($where)->get($this->wzdb->dbprefix($table))->result();
            //echo $this->wzdb->last_query().'<br>';
            return $res;
        }
    }

    /**
     * 统计分类数据总条数
     */
    public function get_count_data_category($table,$where,$db = 'wzdb'){
        if($db=='wzdb'){
            if(!empty($where)){
                $res = $this->wzdb->from($this->wzdb->dbprefix($table))->where($where)->count_all_results();
                //echo $this->wzdb->last_query().'<br>';
                return $res;
            }else{
                $res = $this->wzdb->count_all($this->wzdb->dbprefix($table));
                //echo $this->wzdb->last_query().'<br>';
                return $res;
            }
        }
    }


    //stdClass Object转array
    public function object_array($array){
        if(is_object($array)){
            $array = (array)$array;
        }
        if(is_array($array)){
            foreach($array as $key=>$value){
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }

    /**
     * 二维数组转成一维数组
     */
    public function arrayChange($a){
        static $arr2;
        foreach($a as $v) {
            if(is_array($v)) {
                $this->arrayChange($v);
            }else{
                $arr2[]=$v;
            }
        }
        return $arr2;
    }


    //数组排序
    public function array_sort($arr,$keys,$type='asc')
    {
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
     * 统计数据总条数
     */

    public function get_count_data($table,$where = array(),$db = 'wzdb'){
        if($db == 'wzdb'){
            if(!empty($where)){
                $res = $this->wzdb->from($this->wzdb->dbprefix($table))->where($where)->count_all_results();
                //echo $this->wzdb->last_query().'<br>';
                return $res;
            }else{
                $res = $this->wzdb->count_all($this->wzdb->dbprefix($table));
                //echo $this->wzdb->last_query().'<br>';
                return   $res;
            }

        }
    }

    public function query_data($sql,$db='wzdb',$res = false){
        if($db == 'wzdb'){
            $query  = $this->wzdb->query($sql);
        }else{
            $query = $this->pfsdkdb->query($sql);
        }
        if($res){
            return $query;
        }else{
            return $query->result();
        }
    }
	
	public function query_data_arr($sql,$db='wzdb',$res = false){
        if($db == 'wzdb'){
            $query  = $this->wzdb->query($sql);
        }else{
            $query = $this->pfsdkdb->query($sql);
        }
        if($res){
            return $query;
        }else{
            return $query->result_array();
        }
    }

    public function query_sql($sql,$db='wzdb'){
        if($db == 'wzdb'){
            $query  = $this->wzdb->query($sql);
            return true;
        }
        return false;
    }


        //编辑数据
    public function update_data($table , $data , $where ,$db = 'wzdb' ){
        if($db == 'wzdb'){
            if ($this->wzdb->where($where)->update($this->wzdb->dbprefix($table), $data)){
                //echo $this->wzdb->last_query().'<br>';
                return true;
            }
        }
        return FALSE;
    }

    public function del_data($table,$where,$db = 'wzdb'){
        if($db == 'wzdb'){
            if($this->wzdb->delete($table, $where)){
                return true;
            }
        }
        return false;
    }
    //添加数据
    public function insert_data($table , $data , $db = 'wzdb' ){
        if($db == 'wzdb'){
            if ($this->wzdb->insert($this->wzdb->dbprefix($table),$data)){
                return true;
            }
        }
        return FALSE;
    }

    public function get_rand_char($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;
        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
    }
}
