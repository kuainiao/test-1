<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yang
 * Date: 13-9-24
 * Time: 上午12:11
 * To change this template use File | Settings | File Templates.
 */

require_once 'Model.php';

class PayFailureModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertPayFailure($serverName, $thirdType, $merUsername, $accountId, $gameMoney, $orderId, $failTime, $payTime, $mark)
    {
        $db = $this->db;
        $insert = $db->insertTable('platform_pay_failure', array('NS_id'=>'', 'NS_area'=>$serverName, 'NS_third_type'=>$thirdType, 'NS_wz_username'=>$merUsername, 'NS_account_id'=>$accountId, 'NS_game_money'=>$gameMoney, 'NS_order_id'=>$orderId, 'NS_fail_time'=>$failTime, 'NS_pay_time'=>$payTime, 'NS_mark'=>$mark));
        return $insert;
    }
}