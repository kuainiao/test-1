<?php
class game_model_100 extends CI_Model {
    public $ltgamedb100;
    public function __construct() {
        parent::__construct();
        $this->ltgamedb100 = $this->load->database('ltgamedb100',TRUE);
    }

    public function query_data($sql,$db='ltgamedb',$res = false){
        if($db == 'ltgamedb'){
            $query  = $this->ltgamedb100->query($sql);
        }else{
            $query = $this->pfsdkdb->query($sql);
        }
        if($res){
            return $query;
        }else{
            return $query->result();
        }
    }
	
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
}
