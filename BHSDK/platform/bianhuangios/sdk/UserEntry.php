<?php

require_once './models/UserModel.php';
require_once 'response.php';


class UserEntry
{
	public function checkUserLogin($username,$userpass,$gameId,$systemType,$partner)
    {
        $this->manageSdkUser($username,$userpass,$gameId,$systemType,$partner);
	}

    private function manageSdkUser($username,$userpass,$gameId,$systemType,$partner)
    {
        //查询数据库中是否有此用户
        $newDb = new UserModel();
        $usernameInDb = $newDb->selectUserInfo($gameId,$username,$action='username');
        if($usernameInDb == $username)
        {
            //查询此用户密码
            $userpassInDb = $newDb->selectUserInfo($gameId,$username,$action='userpass');
            if($userpassInDb)
            {
                if($userpassInDb == $userpass)
                {
                    //查询此游戏用户ID
                    $uid = $newDb->selectUserInfo($gameId,$username,$action='userId');
                    if($uid)
                    {
                        //插入用户在平台登录时间
                        $loginTime = time();
                        $insertUserLoginTime = $newDb->insertUserLoginTime($gameId, $uid, $loginTime);
                        if($insertUserLoginTime)
                        {
                            //创建返回客户端的session
                            $session = $this->createSession(18);
                            $insertUserLoginSession = $newDb->insertUserLoginSession($uid, $gameId, $systemType, $session);
                            if($insertUserLoginSession)
                            {
                                echo constant('LOGIN_SUCCESS').'_'.$uid.'_'.$session;
                            }
                            else
                            {
                                echo constant('INSERT_USER_LOGIN_SESSION_ERROR');
                            }
                        }
                        else
                        {
                            echo constant('INSERT_USER_LOGIN_TIME_ERROR');
                        }
                    }
                    else
                    {
                        echo constant('SELECT_UID_ERROR');
                    }
                }
                else
                {
                    echo constant('PASSWORD_ERROR');
                }
            }
            else
            {
                echo constant('SELECT_PASSWORD_ERROR');
            }
        }
        else
        {
            echo constant('USERNAME_NOT_EXIST');
            die();
            /*//插入用户数据信息
            $insertUserInfoInDb = $newDb->insertUserInfo($gameId,$username,$userpass,$systemType,$partner);
            if($insertUserInfoInDb)
            {
                $uid = $newDb->selectUserInfo($gameId,$username,$action='userId');
                if($uid)
                {
                    //插入用户在平台登录时间
                    $loginTime = time();
                    $insertUserLoginTime = $newDb->insertUserLoginTime($gameId, $uid, $loginTime);
                    if($insertUserLoginTime)
                    {
                        //创建返回客户端的session
                        $session = $this->createSession(18);
                        $insertUserLoginSession = $newDb->insertUserLoginSession($uid, $gameId, $systemType, $session);
                        if($insertUserLoginSession)
                        {
                            echo  constant('LOGIN_SUCCESS').'_'.$uid.'_'.$session;
                        }
                        else
                        {
                            echo constant('INSERT_USER_LOGIN_SESSION_ERROR');
                        }
                    }
                    else
                    {
                        echo constant('INSERT_USER_LOGIN_TIME_ERROR');
                    }
                }
                else
                {
                    echo constant('SELECT_UID_ERROR');
                }
            }
            else
            {
                echo constant('INSERT_USER_INFO_ERROR');
            }*/
        }
    }

    private function createSession($length)
    {
        $rand = '';
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';  //定义字符池
        for($i=0;$i<$length;$i++)
        {
            $rand .= $pattern{mt_rand(0,35)};  //从a-Z选择生成随机数
        }
        return $rand; // 终止函数的执行和从函数中返回一个值
    }
}
