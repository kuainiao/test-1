<?php
class log_model_100 extends CI_Model {
    public $ltlogdb100;
    public function __construct() {
        parent::__construct();
        $this->ltlogdb100 = $this->load->database('ltlogdb100',TRUE);
    }

    public function query_data($sql,$db='ltlogdb',$res = false){
        if($db == 'ltlogdb'){
            $query  = $this->ltlogdb100->query($sql);
        }else{
            $query = $this->pfsdkdb->query($sql);
        }
        if($res){
            return $query;
        }else{
            return $query->result();
        }
    }
}
