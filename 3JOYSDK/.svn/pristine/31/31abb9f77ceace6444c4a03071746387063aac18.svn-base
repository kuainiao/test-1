<?php

require_once './models/UserModel.php';
require_once './models/PFUserModel.php';
require_once 'response.php';


class Register
{
	public function UserRegister($oldUsername, $oldUserpass,$username,$userpass,$gameId,$systemType,$partner,$uuid, $action)
    {
        if($action == 'oneKey')
        {
            $this->oneKeyUserRegister($username,$userpass,$gameId,$systemType,$partner);
        }
/*        elseif($action == 'bind')
        {
            $this->userBinding($oldUsername, $oldUserpass, $username,$userpass,$gameId,$systemType);
        }
        else
        {
            $this->normalUserRegister($username,$userpass,$gameId,$systemType,$partner);
        }*/
	}

    private function oneKeyUserRegister($username,$userpass,$gameId,$systemType,$partner,$uuid)
    {
        //查询数据库中是否有此用户
        $newDb = new UserModel();
        $usernameInDb = $newDb->selectUserInfo($username,$action='username');
        if($usernameInDb == $username)
        {
            echo constant('USERNAME_ALREADY_EXIST');

        }
        else
        {
            //插入平台用户返回uid,写入用户来源
            $email = $username.'@'.'3joy.cn';
            $regUrl = 'http://user.3joy.cn/index.php/uhome/api_register';
            $param = 'username='.$username.'&password='.$userpass.'&email='.$email;
            $request = $this->request($regUrl, $param);
            //preg_match("/uid_(.*)?UCenter info/",$request['msg'],$uidArray);
            $uidString = $request['msg'];
            $uidArray = explode('_',$uidString);
            $uid = $uidArray[1];
            if($uid)
            {
                $newPFDb = new PFUserModel();
                $insertSource = $newPFDb->insertSource($uid, $gameId, $systemType, $partner,$uuid);
                $password = $newPFDb->selectPFUserInfo($username, 'password');
                $salt = $newPFDb->selectPFUserInfo($username, 'salt');

                //插入用户数据信息
                $insertUserInfoInDb = $newDb->insertUserInfo($uid, $username, $password, $salt);
                if($insertUserInfoInDb)
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
                            echo  constant('LOGIN_SUCCESS').'_'.$uid.'_'.$session.'_'.$username;
                            if($gameId==100001){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx01&gameId=100001&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100002){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx02&gameId=100002&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100003){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx03&gameId=100003&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100004){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx04&gameId=100004&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100005){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx05&gameId=100005&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100006){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx06&gameId=100006&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100007){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx07&gameId=100007&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100008){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx08&gameId=100008&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100009){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx09&gameId=100009&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100010){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx10&gameId=100010&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100011){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx11&gameId=100011&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100012){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx12&gameId=100012&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100013){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx13&gameId=100013&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100014){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx14&gameId=100014&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100015){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx15&gameId=100015&systemType=1&username=$username&userpass=$password");
                            }
                            else if ($gameId==100016){
                                file_get_contents("http://pfsdk.9sky.me/register.php?partner=tx16&gameId=100016&systemType=1&username=$username&userpass=$password");
                            }
                            else die();
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
                    echo constant('INSERT_USER_INFO_ERROR');
                }
            }
        }
    }

    /*private function normalUserRegister($username,$userpass,$gameId,$systemType,$partner)
    {
        //查询数据库中是否有此用户
        $newDb = new UserModel();
        $usernameInDb = $newDb->selectUserInfo($username,$action='username');
        if($usernameInDb == $username)
        {
            echo constant('USERNAME_ALREADY_EXIST');
        }
        else
        {
            //往平台写入用户，写入用户来源

            $newPFDb = new PFUserModel();
            $insertSource = $newPFDb->insertSource($uid, $gameId, $systemType, $partner);
            //插入用户数据信息
            $insertUserInfoInDb = $newDb->insertUserInfo($uid, $username, $userpass);
            if($insertUserInfoInDb)
            {
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
        $usernameInDb = $newDb->selectUserInfo($oldUsername,$action='username');
        if($usernameInDb == $oldUsername)
        {
            $userpassInDb = $newDb->selectUserInfo($oldUsername,$action='userpass');
            if($userpassInDb == $oldUserpass)
            {
                $usernameInDb = $newDb->selectUserInfo($username,$action='username');
                if($usernameInDb == $username)
                {
                    echo constant('USERNAME_ALREADY_BINDING');
                }
                else
                {
                    //更改平台用户名密码、更改sdk用户名密码
                    $update = $newDb->updateUsernameUserpass($oldUsername, $oldUserpass, $username, $userpass);
                    if($update)
                    {
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
    }*/


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

    private function request($Url, $Params, $Method='post'){

        $Curl = curl_init();//初始化curl

        if ('get' == $Method){//以GET方式发送请求
            curl_setopt($Curl, CURLOPT_URL, "$Url?$Params");
        }else{//以POST方式发送请求
            curl_setopt($Curl, CURLOPT_URL, $Url);
            curl_setopt($Curl, CURLOPT_POST, 1);//post提交方式
            curl_setopt($Curl, CURLOPT_POSTFIELDS, $Params);//设置传送的参数
        }

        curl_setopt($Curl, CURLOPT_HEADER, false);//设置header
        curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上
        curl_setopt($Curl, CURLOPT_CONNECTTIMEOUT, 3);//设置等待时间

        $Res = curl_exec($Curl);//运行curl
        $Err = curl_error($Curl);

        if (false === $Res || !empty($Err)){
            $Errno = curl_errno($Curl);
            $Info = curl_getinfo($Curl);
            curl_close($Curl);

            return array(
                'result' => false,
                'errno' => $Errno,
                'msg' => $Err,
                'info' => $Info,
            );
        }
        curl_close($Curl);//关闭curl
        return array(
            'result' => true,
            'msg' => $Res,
        );
    }
}
