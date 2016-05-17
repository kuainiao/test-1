<?php

require_once './models/UserModel.php';
require_once 'response.php';


class Register
{
	public function UserRegister($oldUsername, $oldUserpass,$username,$userpass,$gameId,$systemType,$partner, $action)
    {
        if($action == 'oneKey')
        {
            $this->oneKeyUserRegister($username,$userpass,$gameId,$systemType,$partner);
        }
        elseif($action == 'bind')
        {
            $this->userBinding($oldUsername, $oldUserpass, $username,$userpass,$gameId,$systemType);
        }
        else
        {
            $this->normalUserRegister($username,$userpass,$gameId,$systemType,$partner);
        }
	}

    private function oneKeyUserRegister($username,$userpass,$gameId,$systemType,$partner)
    {
        //查询数据库中是否有此用户
        $newDb = new UserModel();
        $usernameInDb = $newDb->selectUserInfo($gameId,$username,$action='username');
        if($usernameInDb == $username)
        {
            $username = 'wzsg'.$this->createSession(6);
            //插入用户数据信息
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
                            echo  constant('LOGIN_SUCCESS').'_'.$uid.'_'.$session.'_'.$username.'_'.$userpass;
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
            }
        }
        else
        {
            //插入用户数据信息
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
                            echo  constant('LOGIN_SUCCESS').'_'.$uid.'_'.$session.'_'.$username.'_'.$userpass;
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
            }
        }
    }

    private function normalUserRegister($username,$userpass,$gameId,$systemType,$partner)
    {
        //查询数据库中是否有此用户
        $newDb = new UserModel();
        $usernameInDb = $newDb->selectUserInfo($gameId,$username,$action='username');
        if($usernameInDb == $username)
        {
            echo constant('USERNAME_ALREADY_EXIST');
        }
        else
        {
            //插入用户数据信息
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
            }
        }
    }


    private function userBinding($oldUsername, $oldUserpass, $username,$userpass,$gameId,$systemType)
    {
        //查询数据库中是否有此用户
        $newDb = new UserModel();
        $usernameInDb = $newDb->selectUserInfo($gameId,$oldUsername,$action='username');
        if($usernameInDb == $oldUsername)
        {
            $userpassInDb = $newDb->selectUserInfo($gameId,$oldUsername,$action='userpass');
            if($userpassInDb == $oldUserpass)
            {
                $usernameInDb = $newDb->selectUserInfo($gameId,$username,$action='username');
                if($usernameInDb == $username)
                {
                    echo constant('USERNAME_ALREADY_BINDING');
                }
                else
                {
                    $update = $newDb->updateUsernameUserpass($gameId, $oldUsername, $oldUserpass, $username, $userpass,$systemType);
                    if($update)
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
                        echo constant('UPDATE_USER_INFO_ERROR');
                    }
                }
            }
            else
            {
                //密码错误
                echo constant('PASSWORD_ERROR');
            }
        }
        else
        {
            //用户名绑定成功
            echo constant('USERNAME_ALREADY_BINDING');
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
