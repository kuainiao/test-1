<?php
class command_model extends CI_Model {
    public $pfguilddb;
    public $pfsdkdb;
    public function __construct() {
        parent::__construct();
        $this->pfsdkdb = $this->load->database('pfsdkdb',TRUE);
        $this->pfguilddb = $this->load->database('default',TRUE);
    }
    /**
     * 获取数据信息
     */
    public function get_data($table='',$where=array(),$page=null,$per_page=NULL,$db = 'pfguilddb'){
        if($db=='pfguilddb'){
            if(!empty($page) && !empty($per_page)){
                if(!empty($where)){
                    $res = $this->pfguilddb->get_where($this->pfguilddb->dbprefix($table),$where,$per_page,$page)->result();
                }else{
                    $res = $this->pfguilddb->get($this->pfguilddb->dbprefix($table),$per_page,$page)->result();
                }
                //echo $this->pfguilddb->last_query().'<br>';
            }
            else{
                if(!empty($where)){
                    $res = $this->pfguilddb->get_where($this->pfguilddb->dbprefix($table),$where)->result();
                }else{
                    $res = $this->pfguilddb->get($this->pfguilddb->dbprefix($table))->result();
                }
                //echo $this->pfguilddb->last_query().'<br>';
            }
            return $res;
        }
    }

    /**
     * 获取排序数据信息
     */
    public function get_data_order_by($table=null,$where=array(),$order_by=null,$desc=null,$page=null,$per_page=null,$db='pfguilddb'){
        if($db=='pfguilddb'){
            if(!empty($per_page)){
                if(!empty($where)){
                    $res = $this->pfguilddb->select('*')->from($table)->where($where)->order_by($order_by,$desc)->limit($per_page,$page)->get()->result();
                }else{
                    $res = $this->pfguilddb->select('*')->from($table)->order_by($order_by,$desc)->limit($per_page,$page)->get()->result();
                }
                //echo $this->pfguilddb->last_query().'<br>';
            }
            else{
                if(!empty($where)){
                    $res = $this->pfguilddb->select('*')->from($table)->where($where)->order_by($order_by,$desc)->get()->result();
                }else{
                    $res = $this->pfguilddb->select('*')->from($table)->order_by($order_by,$desc)->get()->result();
                }
                //echo $this->pfguilddb->last_query().'<br>';
            }

            return $res;
        }
    }


    public function get_sum($table,$sum_filed,$where,$db='pfguilddb')
    {
        if($db=='pfguilddb'){
            return $this->pfguilddb->select_sum($sum_filed)->where($where)->get($this->pfguilddb->dbprefix($table))->result();
        }
    }

    /**
     * 统计分类数据总条数
     */
    public function get_count_data_category($table,$where,$db = 'pfguilddb'){
        if($db=='pfguilddb'){
            if(!empty($where)){
                $res = $this->pfguilddb->from($this->pfguilddb->dbprefix($table))->where($where)->count_all_results();
                //echo $this->pfguilddb->last_query().'<br>';
                return $res;
            }else{
                $res = $this->pfguilddb->count_all($this->pfguilddb->dbprefix($table));
                //echo $this->pfguilddb->last_query().'<br>';
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

    public function get_count_data($table,$where = array(),$db = 'pfguilddb'){
        if($db == 'pfguilddb'){
            if(!empty($where)){
                $res = $this->pfguilddb->from($this->pfguilddb->dbprefix($table))->where($where)->count_all_results();
                //echo $this->pfguilddb->last_query().'<br>';
                return $res;
            }else{
                $res = $this->pfguilddb->count_all($this->pfguilddb->dbprefix($table));
                //echo $this->pfguilddb->last_query().'<br>';
                return   $res;
            }

        }
    }

    public function query_data($sql,$db='pfguilddb',$res = false){
        if($db == 'pfguilddb'){
            $query  = $this->pfguilddb->query($sql);
            //echo $this->pfguilddb->last_query().'<br>';
            //return $query->result();
        }else{
            $query = $this->pfsdkdb->query($sql);
            echo $this->pfsdkdb->last_query().'<br>';
        }
        if($res){
            return $query;
        }else{
            return $query->result();
        }
    }
    //编辑数据
    public function update_data($table , $data , $where ,$db = 'pfguilddb' ){
        if($db == 'pfguilddb'){
            if ($this->pfguilddb->where($where)->update($this->pfguilddb->dbprefix($table), $data)){
                //echo $this->pfguilddb->last_query().'<br>';
                return true;
            }
        }
        return FALSE;
    }

    public function del_data($table,$where,$db = 'pfguilddb'){
        if($db == 'pfguilddb'){
            if($this->pfguilddb->delete($table, $where)){
                return true;
            }
        }
        return false;
    }
    //添加数据
    public function insert_data($table , $data , $db = 'pfguilddb' ){
        if($db == 'pfguilddb'){
            if ($this->pfguilddb->insert($this->pfguilddb->dbprefix($table),$data)){
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
