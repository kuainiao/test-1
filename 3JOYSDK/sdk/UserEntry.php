<?php

require_once './models/UserModel.php';
require_once './models/PFUserModel.php';
require_once 'response.php';


class UserEntry
{
	public function checkUserLogin($partner,$username,$userpass,$gameId,$systemType)
    {
        $this->manageSdkUser($partner, $username,$userpass,$gameId,$systemType);
	}

    private function manageSdkUser($partner,$username,$userpass,$gameId,$systemType)
    {
        //查询数据库中是否有此用户
        $newDb = new UserModel();
        $usernameInDb = $newDb->selectUserInfo($username,$action='username');
        if($usernameInDb == $username)
        {
            //查询此用户密码
            $userpassInDb = $newDb->selectUserInfo($username,$action='userpass');
            if($userpassInDb)
            {
                $salt = $newDb->selectUserInfo($username,$action='salt');
                $password = md5($userpass.$salt);
                if($userpassInDb == $password)
                {
                    //查询此游戏用户的平台ID
                    $uid = $newDb->selectUserInfo($username,$action='uid');
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
                                if($partner=='cjgh01' || $partner=='cjgh02' || $partner=='cjgh03' || $partner=='cjgh04' || $partner=='cjgh05' || $partner=='cjgh06' || $partner=='cjgh07' || $partner=='cjgh08' || $partner=='cjgh09' || $partner=='cjgh10' || $partner=='cjgh11' || $partner=='cjgh12' ||
                                    $partner=='cjgh13' || $partner=='cjgh14' || $partner=='cjgh15'){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=$partner&username=$username&userpass=$password&gameId=115&systemType=1");
                                }

                                if($gameId==100001){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx01&username=$username&userpass=$password&gameId=100001&systemType=1");
                                }
                                else if ($gameId==100002){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx02&username=$username&userpass=$password&gameId=100002&systemType=1");
                                }
                                else if ($gameId==100003){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx03&username=$username&userpass=$password&gameId=100003&systemType=1");
                                }
                                else if ($gameId==100004){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx04&username=$username&userpass=$password&gameId=100004&systemType=1");
                                }
                                else if ($gameId==100005){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx05&username=$username&userpass=$password&gameId=100005&systemType=1");
                                }
                                else if ($gameId==100006){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx06&username=$username&userpass=$password&gameId=100006&systemType=1");
                                }
                                else if ($gameId==100007){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx07&username=$username&userpass=$password&gameId=100007&systemType=1");
                                }
                                else if ($gameId==100008){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx08&username=$username&userpass=$password&gameId=100008&systemType=1");
                                }
                                else if ($gameId==100009){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx09&username=$username&userpass=$password&gameId=100009&systemType=1");
                                }
                                else if ($gameId==100010){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx10&username=$username&userpass=$password&gameId=100010&systemType=1");
                                }
                                else if ($gameId==100011){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx11&username=$username&userpass=$password&gameId=100011&systemType=1");
                                }
                                else if ($gameId==100012){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx12&username=$username&userpass=$password&gameId=100012&systemType=1");
                                }
                                else if ($gameId==100013){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx13&username=$username&userpass=$password&gameId=100013&systemType=1");
                                }
                                else if ($gameId==100014){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx14&username=$username&userpass=$password&gameId=100014&systemType=1");
                                }
                                else if ($gameId==100015){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx15&username=$username&userpass=$password&gameId=100015&systemType=1");
                                }
                                else if ($gameId==100016){
                                    file_get_contents("http://pfsdk.9sky.me/login.php?partner=tx16&username=$username&userpass=$password&gameId=100016&systemType=1");
                                }
                                else{
                                    die();
                                }
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
