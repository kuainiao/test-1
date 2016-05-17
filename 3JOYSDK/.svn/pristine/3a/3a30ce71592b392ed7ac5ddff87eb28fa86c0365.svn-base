<?php

require_once  dirname(__FILE__).'/'.'../libs/ez_sql/ez_sql_mysql.php';
require_once  dirname(__FILE__).'/'.'../halo/HaloDb.php';

class Model
{
	protected $db;
	
	public function __construct()
	{
		$this->db = Model::getDB();
	}

	static public function getDB()
	{
		$db = new HaloDb(array(
				'host'=>'dbsdkdb',
				'user'=>'sdk',
				'pass'=>'sdk.db',
				'dbname'=>'sdkdb'
		));
		$db->query('SET NAMES utf8');
		return $db;
	}
}
