<?php

require_once '../../libs/ez_sql/ez_sql_mysql.php';
require_once '../../halo/HaloDb.php';

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
				'host'=>'dbnsky',
				'user'=>'bianhuang',
				'pass'=>'bianhuang',
				'dbname'=>'bianhuangdb'
		));
		$db->query('SET NAMES utf8');
		return $db;
	}
}
