<?php
class pay_model extends CI_Model {
    public $ltpaydb;
    public function __construct() {
        parent::__construct();
        $this->ltpaydb = $this->load->database('ltpaydb',TRUE);
    }

    public function query_data($sql,$db='ltpaydb',$res = false){
        if($db == 'ltpaydb'){
            $query  = $this->ltpaydb->query($sql);
        }else{
            $query = $this->ltpaydb->query($sql);
        }
        if($res){
            return $query;
        }else{
            return $query->result();
        }
    }
}
