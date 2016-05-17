<?php

require_once  dirname(__FILE__).'/'.'../libs/ez_sql/ez_sql_mysql.php';
require_once  dirname(__FILE__).'/'.'../halo/HaloDb.php';

class PFModel
{
	protected $db;
	
	public function __construct()
	{
		$this->db = PFModel::getDB();
	}

	static public function getDB()
	{
		$db = new HaloDb(array(
				'host'=>'dbpfbbs',
				'user'=>'pfuser',
				'pass'=>'pfuser.!@#%$^',
				'dbname'=>'pfbbs'
		));
		$db->query('SET NAMES utf8');
		return $db;
	}
}
