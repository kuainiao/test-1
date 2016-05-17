<?php
header("Content-type: text/html; charset=utf-8");
require_once './models/UserModel.php';
require_once 'response.php';


class Change
{

    public function changePassword($username,$oldPassword,$newPassword1,$gameId=10000)
    {
        //查询数据库中是否有此用户
        $newDb = new UserModel();
        $usernameInDb = $newDb->selectUserInfo($gameId,$username,$action='username');
        if($usernameInDb == $username)
        {
            $passwordInDb = $newDb->selectUserInfo($gameId,$username,$action='userpass');
            $getPassword = md5($oldPassword);
            if($passwordInDb == $getPassword)
            {
                $update = $newDb->updateUserpass($gameId, $username, md5($newPassword1));
                if($update)
                {
                    return '用户修改密码成功';
                }
                else
                {
                    return '用户修改密码失败请重试';
                }
            }
            else
            {
                return '用户原始密码错误请重试';
            }
        }
        else
        {
            return '没有这个用户';
        }
    }
}
