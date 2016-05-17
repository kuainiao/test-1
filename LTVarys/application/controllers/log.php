<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function all_log(){
        header ( "Content-type: text/html; charset=utf-8" );
        $logs_0610 = $this->nginx_log('0610');
        $logs_0611 = $this->nginx_log('0611');
        $logs_0612 = $this->nginx_log('0612');
        $logs_0613 = $this->nginx_log('0613');
        $logs_0614 = $this->nginx_log('0614');
        $logs_0615 = $this->nginx_log('0615');
        $logs_0616 = $this->nginx_log('0616');

        $logs_0610 = array_unique($logs_0610);
        $logs_0611 = array_unique($logs_0611);
        $logs_0612 = array_unique($logs_0612);
        $logs_0613 = array_unique($logs_0613);
        $logs_0614 = array_unique($logs_0614);
        $logs_0615 = array_unique($logs_0615);
        $logs_0616 = array_unique($logs_0616);


        echo '6.10新增用户='.count($logs_0610).','.'次日留存='.$this->retention($logs_0610, $logs_0611).'%'.'<br>';
        echo '6.11新增用户='.$this->new_user($logs_0611, $logs_0610).','.'次日留存='.$this->retention($logs_0611, $logs_0612).'%'.'<br>';
        echo '6.12新增用户='.$this->new_user($logs_0612, $logs_0611).','.'次日留存='.$this->retention($logs_0612, $logs_0613).'%'.'<br>';
        echo '6.13新增用户='.$this->new_user($logs_0613, $logs_0612).','.'次日留存='.$this->retention($logs_0613, $logs_0614).'%'.'<br>';
        echo '6.14新增用户='.$this->new_user($logs_0614, $logs_0613).','.'次日留存='.$this->retention($logs_0614, $logs_0615).'%'.'<br>';
        echo '6.15新增用户='.$this->new_user($logs_0615, $logs_0614).','.'次日留存='.$this->retention($logs_0615, $logs_0616).'%'.'<br>';
        echo '<br>';
        echo '6.10三日留存='.$this->retention($logs_0610, $logs_0612).'%'.'<br>';
        echo '6.11三日留存='.$this->retention($logs_0611, $logs_0613).'%'.'<br>';
        echo '6.12三日留存='.$this->retention($logs_0612, $logs_0614).'%'.'<br>';
        echo '6.13三日留存='.$this->retention($logs_0613, $logs_0615).'%'.'<br>';
        echo '6.14三日留存='.$this->retention($logs_0614, $logs_0616).'%'.'<br>';
        echo '<br>';
        echo '6.10七日留存='.$this->retention($logs_0610, $logs_0616).'%'.'<br>';
    }


    public function retention($logs_0610, $logs_0611){
        $not_login_uid = array_diff($logs_0610, $logs_0611);
        $login_uid = count($logs_0610)-count($not_login_uid);
        return $next_retention_0610 = round($login_uid/count($logs_0610)*100 ,2);
    }

    public function new_user($logs_0611, $logs_0610){
        $new_user = array_diff($logs_0611, $logs_0610);
        return count($new_user);
    }

    public function nginx_log($filename=''){
        $file = APPPATH . "logs/" . $filename;

        $handle = @fopen($file, "r");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, 4096);
                $arr[] = $buffer;
            }
            fclose($handle);
        }
        foreach($arr as $v){
            $new_arr[] = explode(' ', $v);
        }

        foreach($new_arr as $v){
            if(!empty($v[6]) && $v[6]!='/favicon.ico' && $v[6]!='/'){
               $logs[] = $v[6];
            }
        }

        foreach($logs as $v){
            $channel_uid_arr[] = explode('&', $v);
        }

        foreach($channel_uid_arr as $v){
            $channel_uid[] = explode('=', $v[3])[1];
        }

        return $channel_uid;
    }
}
