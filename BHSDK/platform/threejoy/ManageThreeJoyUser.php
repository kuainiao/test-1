<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yang
 * Date: 13-8-24
 * Time: 下午5:25
 * To change this template use File | Settings | File Templates.
 */
require_once '../../models/TJModel.php';
require_once '../../models/TJToGameServerModel.php';
require_once '../../models/UserLoginModel.php';

class ManageThreeJoy
{
    public function manageThreeJoyUser($area,$uid,$server,$systemType=1)
    {
        //定义第三方平台类型
        $third_type = 27;
		
		$server_table = 'platform_threejoy_server';
        //查询网站平台uid
        $newDb = new TJModel();
        $dbUid = $newDb->selectThreeJoyUser($area,$uid);
		
        //查询游戏数据库uid
        $newGameDb = new TJToGameServerModel($area);
        $gameDbUid = $newGameDb->selectThreeJoyUserOnGameServer($uid,$third_type);
		
        //判断用户是否登录过
        if ($uid == $dbUid)
        {
            //判断用户是否在游戏数据库里
            if ($uid == $gameDbUid)
            {
                //更新游戏数据库第三方表code
                $code = $this->createRandString(12);
                $updateCode = $newGameDb->updateThreeJoyUserCodeOnGameServer($code,$uid);

                //插入登录用户登录时间
                $account_id = $newDb->selectThreeJoyUserAccountId($area,$uid);
                $newUserLogin = new UserLoginModel();
                $loginTime = time();
                $insertUserLoginTime = $newUserLogin->insertUserLoginTime($third_type, $area, $account_id, $uid, $loginTime,$server,$systemType);

                if ($updateCode)
                {
                	//查看用户是否在此游戏服存在
	            	if($server!=''){
	            		$cnt = $newDb->selectServer($server_table,$uid,$server);
						if(!$cnt){
							//查看该用户所在大区对应的id
							$area_id = $newDb->selectAreaId('platform_threejoy_user', $area, $uid);
							if($area_id){
								$ser = $newDb->insertServer($server_table,$uid,$area_id,$server,$area,$systemType);
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
        	//根据平台id判断以前是否该平台id注册过用户名
        	$db_username = $newDb->selectThreeJoyUserCheck($uid);
            //创建游戏用户名、密码
            if($db_username!=''){
            	$wz_username = $db_username;
            }else{
            	$string = substr(md5(time()),8,2);
            	$wz_username = 'txjz'.$this->createRandString(6).$string;
            }
            $wz_password = $this->createRandString(6);
            $md5_wz_password = md5($wz_password);
			
            //插入游戏服务器username、password
            $insertGameServerUser = $newGameDb->insertThreeJoyUserOnGameServer($wz_username, $md5_wz_password);

            if($insertGameServerUser)
            {
                $account_id = $newGameDb->selectThreeJoyUserIdOnGameServer($wz_username);
				if($account_id)
                //插入网站平台uid、account_id、username
                $insertUser = $newDb->insertThreeJoyUser($area, $uid, $account_id, $wz_username,$systemType);

                if($insertUser)
                {

                    //插入游戏数据库第三方表code
                    $code = $this->createRandString(12);
                    $insertCode = $newGameDb->insertThreeJoyUserCodeOnGameServer($account_id, $code, $third_type, $uid);

                    //插入登录用户登录时间
                    $newUserLogin = new UserLoginModel();
                    $loginTime = time();
                    $insertUserLoginTime = $newUserLogin->insertUserLoginTime($third_type, $area, $account_id, $uid, $loginTime,$server,$systemType);

                    if(!$insertCode)
                    {
                    	if($server!=''){
		            		$cnt = $newDb->selectServer($server_table,$uid,$server);
							if(!$cnt){
								//查看该用户所在大区对应的id
								$area_id = $insertUser;
								if($area_id){
									$ser = $newDb->insertServer($server_table,$uid,$area_id,$server,$area,$systemType,time());
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
