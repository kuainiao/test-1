<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yang
 * Date: 13-8-24
 * Time: 下午5:25
 * To change this template use File | Settings | File Templates.
 */
require_once '../../models/NineOneModel.php';
require_once '../../models/NineOneToGameServerModel.php';
require_once '../../models/UserLoginModel.php';

class ManageNineOne
{
    public function manageNineOneUser($area, $Uid,$server,$channel_type=1)
    {
        //定义第三方平台类型
        $third_type = 0;
		
		$server_table = 'platform_nineone_server';
        //查询网站平台Uid
        $newdb = new NineOneModel();
        $dbUid = $newdb->selectNineOneUser($area, $Uid);

        //查询游戏数据库Uid
        $newgamedb = new NineOneToGameServerModel($area);
        $gameDbUid = $newgamedb->selectNineOneUserOnGameServer($Uid);

        //判断用户是否登录过
        if ($Uid == $dbUid)
        {
            //判断用户是否在游戏数据库里
            file_put_contents('/home/baiyutao/log/abc.txt',$Uid.'vv'.$dbUid.'vv'.$gameDbUid.'<br/>');
            if ($Uid == $gameDbUid)
            {
                //更新游戏数据库第三方表code

                $code = $this->createRandString(12);
                $updateCode = $newgamedb->updateNineOneUserCodeOnGameServer($code, $Uid);

                //插入登录用户登录时间
                $account_id = $newdb->selectNineOneUserAccountId($area, $Uid);
                $newUserLogin = new UserLoginModel();
                $loginTime = time();
                $insertUserLoginTime = $newUserLogin->insertUserLoginTime($third_type, $area, $account_id, $Uid, $loginTime,$server,$channel_type);

                if ($updateCode)
                {
                	//查看用户是否在此游戏服存在
	            	if($server!=''){
	            		$cnt = $newdb->selectServer($server_table,$Uid,$server);
						if(!$cnt){
							//查看该用户所在大区对应的id
							$area_id = $newdb->selectAreaId('platform_nineone_user', $area, $Uid);
							if($area_id){
								$ser = $newdb->insertServer($server_table,$Uid,$area_id,$server,$area,$channel_type);
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
        	$db_username = $newdb->selectNineOneUserCheck($Uid);
            //创建游戏用户名、密码
            if($db_username!=''){
            	$wz_username = $db_username;
            }else{
            	$string = substr(md5(time()),8,2);
            	$wz_username = '91'.$this->createRandString(6).$string;
            }
            $wz_password = $this->createRandString(6);
            $md5_wz_password = md5($wz_password);

            //插入游戏服务器username、password
            $insertGameServerUser = $newgamedb->insertNineOneUserOnGameServer($wz_username, $md5_wz_password);

            if($insertGameServerUser)
            {
                $account_id = $newgamedb->selectNineOneUserIdOnGameServer($wz_username);
				if($account_id)
                //插入网站平台Uid、account_id、username
                $insertUser = $newdb->insertNineOneUser($area, $Uid, $account_id, $wz_username,$channel_type);

                if($insertUser)
                {

                    //插入游戏数据库第三方表code
                    $code = $this->createRandString(12);
                    $insertCode = $newgamedb->insertNineOneUserCodeOnGameServer($account_id, $code, $third_type, $Uid);

                    //插入登录用户登录时间
                    $newUserLogin = new UserLoginModel();
                    $loginTime = time();
                    $insertUserLoginTime = $newUserLogin->insertUserLoginTime($third_type, $area, $account_id, $Uid, $loginTime,$server,$channel_type);

                    if(!$insertCode)
                    {
                    	if($server!=''){
		            		$cnt = $newdb->selectServer($server_table,$Uid,$server);
							if(!$cnt){
								//查看该用户所在大区对应的id
								$area_id = $insertUser;
								if($area_id){
									$ser = $newdb->insertServer($server_table,$Uid,$area_id,$server,$area,$channel_type,time());
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
            file_put_contents('/home/baiyutao/log/abc.txt','bb');
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
