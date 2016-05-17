<?php
/**
 * Created by PhpStorm.
 * User: YANG
 * Date: 14-6-16
 * Time: 下午3:02
 */

require_once dirname(__FILE__).'/'.'../models/SessionModel.php';

class Session
{
    //返回码：0=失败，1=成功(SessionId有效)，2= gameId无效，3=参数无效，4= Sign无效，5=session无效
    public function checkSession($gameId, $uid, $session, $sign)
    {
        $newModel = new SessionModel();

        if($gameKey = $newModel->selectGameKey($gameId))
        {
            $mySession = $newModel->selectUserSession($gameId, $uid);
            if($mySession == $session)
            {
                $mySign = md5($gameId.$uid.$session.$gameKey);
                if($mySign == $sign)
                {
                    $response = json_encode(
                        array("ErrorCode" => "1", "ErrorDesc" => "有效")
                    );
                    echo $response;
                }
                else
                {
                    $response = json_encode(
                        array("ErrorCode" => "4", "ErrorDesc" => "sign无效")
                    );
                    echo $response;
                }
            }
            else
            {
                $response = json_encode(
                    array("ErrorCode" => "5", "ErrorDesc" => "session无效")
                );
                echo $response;
            }
        }
        else
        {
            $response = json_encode(
                array("ErrorCode" => "2", "ErrorDesc" => "gameId无效")
            );
            echo $response;
        }
    }
}