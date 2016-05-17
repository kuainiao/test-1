<?php
class Bh_model extends CI_Model {
    public $bhmyown;
    public function __construct() {
        parent::__construct();
        $this->bhmyown = $this->load->database('bhmyown',TRUE);
    }
    /**
     * 获取数据信息444
     */
    public function get_data($table='',$where=array(),$page=null,$per_page=NULL,$db = 'bhmyown'){
        if($db=='bhmyown'){
            if(!empty($page) && !empty($per_page)){
                if(!empty($where)){
                    $res = $this->bhmyown->get_where($this->bhmyown->dbprefix($table),$where,$per_page,$page)->result();
                }else{
                    $res = $this->bhmyown->get($this->bhmyown->dbprefix($table),$per_page,$page)->result();
                }
                //echo $this->bhmyown->last_query().'<br>';
            }
            else{
                if(!empty($where)){
                    $res = $this->bhmyown->get_where($this->bhmyown->dbprefix($table),$where)->result();
                }else{
                    $res = $this->bhmyown->get($this->bhmyown->dbprefix($table))->result();
                }
                //echo $this->bhmyown->last_query().'<br>';
            }
            return $res;
        }
    }

    /**
     * 获取排序数据信息
     */
    public function get_data_order_by($table=null,$where=array(),$order_by=null,$desc=null,$page=null,$per_page=null,$db='bhmyown'){
        if($db=='bhmyown'){
            if(!empty($per_page)){
                if(!empty($where)){
                    $res = $this->bhmyown->select('*')->from($table)->where($where)->order_by($order_by,$desc)->limit($per_page,$page)->get()->result();
                }else{
                    $res = $this->bhmyown->select('*')->from($table)->order_by($order_by,$desc)->limit($per_page,$page)->get()->result();
                }
                //echo $this->bhmyown->last_query().'<br>';
            }
            else{
                if(!empty($where)){
                    $res = $this->bhmyown->select('*')->from($table)->where($where)->order_by($order_by,$desc)->get()->result();
                }else{
                    $res = $this->bhmyown->select('*')->from($table)->order_by($order_by,$desc)->get()->result();
                }
                //echo $this->bhmyown->last_query().'<br>';
            }

            return $res;
        }
    }


    public function get_sum($table,$sum_filed,$where,$db='bhmyown')
    {
        if($db=='bhmyown'){
            $res = $this->bhmyown->select_sum($sum_filed)->where($where)->get($this->bhmyown->dbprefix($table))->result();
            //echo $this->bhmyown->last_query().'<br>';
            return $res;
        }
    }

    /**
     * 统计分类数据总条数
     */
    public function get_count_data_category($table,$where,$db = 'bhmyown'){
        if($db=='bhmyown'){
            if(!empty($where)){
                $res = $this->bhmyown->from($this->bhmyown->dbprefix($table))->where($where)->count_all_results();
                //echo $this->bhmyown->last_query().'<br>';
                return $res;
            }else{
                $res = $this->bhmyown->count_all($this->bhmyown->dbprefix($table));
                //echo $this->bhmyown->last_query().'<br>';
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

    public function get_count_data($table,$where = array(),$db = 'bhmyown'){
        if($db == 'bhmyown'){
            if(!empty($where)){
                $res = $this->bhmyown->from($this->bhmyown->dbprefix($table))->where($where)->count_all_results();
                //echo $this->bhmyown->last_query().'<br>';
                return $res;
            }else{
                $res = $this->bhmyown->count_all($this->bhmyown->dbprefix($table));
                //echo $this->bhmyown->last_query().'<br>';
                return   $res;
            }

        }
    }

    public function query_data($sql,$db='bhmyown',$res = false){
        if($db == 'bhmyown'){
            $query  = $this->bhmyown->query($sql);
        }else{
            $query = $this->bhmyown->query($sql);
        }
        if($res){
            return $query;
        }else{
            return $query->result();
        }
    }
	public function query_data_arr($sql,$db='bhmyown',$res = false){
        if($db == 'bhmyown'){
            $query  = $this->bhmyown->query($sql);
        }else{
            $query = $this->bhmyown->query($sql);
        }
        if($res){
            return $query;
        }else{
            return $query->result_array();
        }
    }

    public function query_sql($sql,$db='bhmyown'){
        if($db == 'bhmyown'){
            $query  = $this->bhmyown->query($sql);
            return true;
        }
        return false;
    }


        //编辑数据
    public function update_data($table , $data , $where ,$db = 'bhmyown' ){
        if($db == 'bhmyown'){
            if ($this->bhmyown->where($where)->update($this->bhmyown->dbprefix($table), $data)){
                //echo $this->bhmyown->last_query().'<br>';
                return true;
            }
        }
        return FALSE;
    }

    public function del_data($table,$where,$db = 'bhmyown'){
        if($db == 'bhmyown'){
            if($this->bhmyown->delete($table, $where)){
                return true;
            }
        }
        return false;
    }
    //添加数据
    public function insert_data($table , $data , $db = 'bhmyown' ){
        if($db == 'bhmyown'){
            if ($this->bhmyown->insert($this->bhmyown->dbprefix($table),$data)){
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
