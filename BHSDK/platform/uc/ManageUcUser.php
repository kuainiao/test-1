<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yang
 * Date: 13-8-24
 * Time: 下午5:25
 * To change this template use File | Settings | File Templates.
 */
require_once '../../models/UCModel.php';
require_once '../../models/UCToGameServerModel.php';
require_once '../../models/UserLoginModel.php';

class ManageUc
{
    public function manageUcUser($area, $Uin, $server, $ucaccountId)
    {
        //定义第三方平台类型
        $third_type = 1;
		
		$server_table = 'platform_uc_server';
        //查询网站平台Uin
        $newdb = new UCModel();
        $dbUin = $newdb->selectUcUser($area, $Uin);

        //查询游戏数据库Uin
        $newgamedb = new UCToGameServerModel($area);
        $gameDbUin = $newgamedb->selectUcUserOnGameServer($Uin);

        //判断用户是否登录过
        if ($Uin == $dbUin)
        {
            //判断用户是否在游戏数据库里
            if ($Uin == $gameDbUin)
            {
                //更新游戏数据库第三方表code
                $code = $this->createRandString(12);
                $updateCode = $newgamedb->updateUcUserCodeOnGameServer($code, $Uin);

                //插入登录用户登录时间
                $account_id = $newdb->selectUcUserAccountId($area, $Uin);
                $newUserLogin = new UserLoginModel();
                $loginTime = time();
                $insertUserLoginTime = $newUserLogin->insertUserLoginTime($third_type, $area, $account_id, $Uin, $loginTime,$server,$channel_type=1);

                if ($updateCode)
                {
                	//查看用户是否在此游戏服存在
	            	if($server!=''){
	            		$cnt = $newdb->selectServer($server_table,$Uin,$server);
						if(!$cnt){
							//查看该用户所在大区对应的id
							$area_id = $newdb->selectAreaId('platform_uc_user', $area, $Uin);
							if($area_id){
								$ser = $newdb->insertServer($server_table,$Uin,$area_id,$server,$area,$channel_type=1);
							}
						}
	            	}
                    echo $Uin.'_'.$code;
                    file_put_contents("/tmp/tesxiaobai.txt",$code);//把内容写入到一个文件
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
        	$db_username = $newdb->selectUcUserCheck($Uin);
            //创建游戏用户名、密码
            if($db_username!=''){
            	$wz_username = $db_username;
            }else{
            	$string = substr(md5(time()),8,2);
            	$wz_username = 'UC'.$this->createRandString(6).$string;
            }   
            $wz_password = $this->createRandString(6);
            $md5_wz_password = md5($wz_password);

            //插入游戏服务器username、password
            $insertGameServerUser = $newgamedb->insertUcUserOnGameServer($wz_username, $md5_wz_password);

            if($insertGameServerUser)
            {
                $account_id = $newgamedb->selectUcUserIdOnGameServer($wz_username);
				if($account_id)
                //插入网站平台uin、account_id、username
                $insertUser = $newdb->insertUcUser($area, $Uin, $account_id, $wz_username, $ucaccountId);

                if($insertUser)
                {
                    //插入游戏数据库第三方表code
                    $code = $this->createRandString(12);
                    $insertCode = $newgamedb->insertUcUserCodeOnGameServer($account_id, $code, $third_type, $Uin);


                    //插入登录用户登录时间
                    $newUserLogin = new UserLoginModel();
                    $loginTime = time();
                    $insertUserLoginTime = $newUserLogin->insertUserLoginTime($third_type, $area, $account_id, $Uin, $loginTime,$server,$channel_type=1);
                    file_put_contents("/tmp/xiaobai/test9933.txt",'aaaxxxxxx'.$insertCode);//把内容写入到一个文件
                    if(!$insertCode)
                    {
                    	if($server!=''){
		            		$cnt = $newdb->selectServer($server_table,$Uin,$server);
							if(!$cnt){
								//查看该用户所在大区对应的id
								$area_id = $insertUser;
								if($area_id){
									$ser = $newdb->insertServer($server_table,$Uin,$area_id,$server,$area,$channel_type=1,time());
								}
							}
	            		}
                        echo $Uin.'_'.$code;
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
