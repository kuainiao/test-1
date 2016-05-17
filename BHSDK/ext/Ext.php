<?php
class Ext{
	static $_instance = null;
	//配置服务列表
	public $server_info = array(
		101,102,103,104,105,106,
		201,202,203,204,205,206,207,208,209,210,211,212,
		301,302,303,304,305,306,307,308,309,310,311,312,
		401,402,403,404,405,406,407,408,409,410,411,412,
		10001,20001,200001
	);
    
    public static function getInstance(){
        if(null == self::$_instance){
            $className = get_called_class();
            self::$_instance = new $className;
        }
        return self::$_instance;
    }
	
	//通过区服获取区
	public function getArea($info){
		if ($info >= 100 && $info < 200)
        {
            $area = 100;
        }
        elseif ($info >= 200 && $info < 300)
        {
            $area = 200;
        }
        elseif ($info >= 300 && $info < 400)
        {
            $area = 300;
        }
        elseif ($info >= 400 && $info < 500)
        {
            $area = 400;
        }
        elseif ($info >= 500 && $info < 600)
        {
            $area = 500;
        }
        elseif ($info >= 600 && $info < 700)
        {
            $area = 600;
        }
        elseif ($info >= 700 && $info < 800)
        {
            $area = 700;
        }
        elseif ($info >= 800 && $info < 900)
        {
            $area = 800;
        }
        elseif ($info >= 900 && $info < 1000)
        {
            $area = 900;
        }
        elseif ($info >= 1000 && $info < 2000)
        {
            $area = 1000;
        }
        elseif ($info >= 2000 && $info < 3000)
        {
            $area = 2000;
        }
        elseif($info >= 10000 && $info < 10100){
        	$area = 10000;
        }
        elseif($info >= 20000 && $info < 20100){
        	$area = 20000;
        }
        elseif($info >= 100000 && $info < 110000){
            $area = 100000;
        }
        elseif($info >= 200000 && $info < 220000){
            $area = 200000;
        }
        elseif($info >= 300000 && $info < 330000){
            $area = 300000;
        }
        else
        {
            $area = 1000;
        }
		return $area;
	}
	
	//判断区服
	public function CheckAreaServer($info){
		if(!in_array($info,$this->server_info))
        {
            return 101;die;
        }
		return $info;
	}
}
