<?php
/**
 * Created by JetBrains PhpStorm.
 * User: djr
 * Date: 13-12-09
 * Time: 上午10:00
 * To change this template use File | Settings | File Templates.
 */
require_once '../../models/PlatModel.php';
require_once '../../models/PlatToGameServerModel.php';
require_once '../../models/UserLoginModel.php';

class ManageIs
{
    public function manageIsUser($area,$uid,$server,$channel_type=2)
    { 	
        //定义第三方平台类型
        $third_type = 14;
		
		$server_table = 'platform_itools_server';
        //查询网站平台Uin
        $newdb = new PlatModel();
		$table = 'platform_itools_user';
        $dbUin = $newdb->selectThirdUser($table,$area,$uid);

        //查询游戏数据库Uin
        $newgamedb = new PlatToGameServerModel($area);
        $gameDbUin = $newgamedb->selectThirdUserOnGameServer($uid,$third_type);
        
        //判断用户是否登录过
        if ($uid == $dbUin)
        {
            //判断用户是否在游戏数据库里
            if ($uid == $gameDbUin)
            {
                //更新游戏数据库第三方表code
                $code = $this->createRandString(12);
                $updateCode = $newgamedb->updateThirdUserCodeOnGameServer($code,$uid);

                //插入登录用户登录时间
                $account_id = $newdb->selectThirdUserAccountId($table, $area, $uid);
                $newUserLogin = new UserLoginModel();
                $loginTime = time();
                $insertUserLoginTime = $newUserLogin->insertUserLoginTime($third_type,$area,$account_id,$uid,$loginTime,$server,$channel_type);

                if ($updateCode)
                {
                	//查看用户是否在此游戏服存在
	            	if($server!=''){
	            		$cnt = $newdb->selectServer($server_table,$uid,$server);
						if(!$cnt){
							//查看该用户所在大区对应的id
							$area_id = $newdb->selectAreaId($table, $area, $uid);
							if($area_id){
								$ser = $newdb->insertServer($server_table,$uid,$area_id,$server,$area,$channel_type);
							}
						}
	            	} 
                    echo $code;
                }
                else
                {
                    echo "update code error";
                }
            }
        }
        else
        {
        	//根据平台id 判断以前是否该平台id注册过用户名
        	$db_username = $newdb->selectThirdUserCheck($table,$uid);
            //创建游戏用户名、密码
            if($db_username!=''){
            	$wz_username = $db_username;
            }else{
            	$string = substr(md5(time()),8,2);
            	$wz_username = 'itools'.$this->createRandString(6).$string;
			}
            $wz_password = $this->createRandString(6);
            $md5_wz_password = md5($wz_password);

            //插入游戏服务器username、password
            $insertGameServerUser = $newgamedb->insertThirdUserOnGameServer($wz_username,$md5_wz_password);

            if($insertGameServerUser)
            {
                $account_id = $newgamedb->selectThirdUserIdOnGameServer($wz_username);
				if($account_id)
                //插入网站平台uin、account_id、username
                $insertUser = $newdb->insertThirdUser($table,$area,$uid,$account_id,$wz_username,$channel_type);

                if($insertUser)
                {
                    //插入游戏数据库第三方表code
                    $code = $this->createRandString(12);
                    $insertCode = $newgamedb->insertThirdUserCodeOnGameServer($account_id,$code,$third_type,$uid);

                    //插入登录用户登录时间
                    $newUserLogin = new UserLoginModel();
                    $loginTime = time();
                    $insertUserLoginTime = $newUserLogin->insertUserLoginTime($third_type,$area,$account_id,$uid,$loginTime,$server,$channel_type);

                    if($insertCode)
                    {
                    	if($server!=''){
		            		$cnt = $newdb->selectServer($server_table,$uid,$server);
							if(!$cnt){
								//查看该用户所在大区对应的id
								$area_id = $insertUser;
								if($area_id){
									$ser = $newdb->insertServer($server_table,$uid,$area_id,$server,$area,$channel_type,time());
								}
							}
            			}
                        echo $code;
                    }
                    else
                    {
                        echo "插入code失败";
                    }
                }
                else
                {
                    echo "插入用户失败";
                }
            }
            else
            {
                echo "插入游戏服务器用户失败";
            }
        }
    }

    private function createRandString($length)
    {
    	$rand = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';  //定义字符池
        for($i=0;$i<$length;$i++)
        {
            $rand .= $pattern{mt_rand(0,35)};  //从a-Z选择生成随机数
        }
        return $rand; // 终止函数的执行和从函数中返回一个值
    }
}
