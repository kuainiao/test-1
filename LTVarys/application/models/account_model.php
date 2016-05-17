<?php
class account_model extends CI_Model {
    public $ltaccountdb;
    public function __construct() {
        parent::__construct();
        $this->ltaccountdb = $this->load->database('ltaccountdb',TRUE);
    }

    public function query_data($sql,$db='ltaccountdb',$res = false){
        if($db == 'ltaccountdb'){
            $query  = $this->ltaccountdb->query($sql);
			
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
