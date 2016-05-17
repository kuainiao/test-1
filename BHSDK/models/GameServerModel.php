<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yang
 * Date: 13-9-4
 * Time: 下午3:13
 * To change this template use File | Settings | File Templates.
 */

require_once '../../libs/ez_sql/ez_sql_mysql.php';
require_once '../../halo/HaloDb.php';

class GameServerModel
{
    protected $db;

    public function __construct($area)
    {
        $this->db = GameServerModel::getDB($area);
    }

    static public function getDB($area)
    {
        $db = new HaloDb(array(
            'host'=>'area'.$area,
            'user'=>'account',
            'pass'=>'account',
            'dbname'=>'accountdb'
        ));
        $db->query('SET NAMES utf8');
        return $db;
    }
}
