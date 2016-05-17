<?php
//检查用户
    header("Content-type: text/html; charset=utf-8");

    $serv_id = $_GET['serv_id'];
    $usr_name = $_GET['usr_name'];
    $app_id = '6061';

    if(empty($serv_id) || empty($usr_name)){
        echo "value is empty!";
        exit;
    }
    
    switch($serv_id){
        case "201";
            $game_host = "103.26.0.23:1111";
            $gamedb = "gamedb1";
            $account_host = "103.26.0.23:3306";
            $accountdb = "accountdb";
            break;
        case "211";
            $game_host = "103.26.0.23:5555";
            $gamedb = "gamedb5";
            $account_host = "103.26.0.23:3306";
            $accountdb = "accountdb";
            break;
        case "209";
            $game_host = "103.26.0.23:3333";
            $gamedb = "gamedb3";
            $account_host = "103.26.0.23:3306";
            $accountdb = "accountdb";
            break;
        case "202";
            $game_host = "103.26.0.23:2222";
            $gamedb = "gamedb2";
            $account_host = "103.26.0.23:3306";
            $accountdb = "accountdb";
            break;
        case "302";
            $game_host = "103.26.0.25:4444";
            $gamedb = "gamedb4";
            $account_host = "103.26.0.25:3306";
            $accountdb = "accountdb";
            break;
        case "301";
            $game_host = "103.26.0.25:3333";
            $gamedb = "gamedb3";
            $account_host = "103.26.0.25:3306";
            $accountdb = "accountdb";
            break;
        case "304";
            $game_host = "103.26.0.25:1111";
            $gamedb = "gamedb1";
            $account_host = "103.26.0.25:3306";
            $accountdb = "accountdb";
            break;
        case "310";
            $game_host = "103.26.0.25:2222";
            $gamedb = "gamedb2";
            $account_host = "103.26.0.25:3306";
            $accountdb = "accountdb";
            break;
        case "100001";
            $game_host = "103.26.0.19:2222";
            $gamedb = "gamedb2";
            $account_host = "103.26.0.19:3306";
            $accountdb = "accountdb";
            break;
        case "100007";
            $game_host = "103.26.0.71:6666";
            $gamedb = "gamedb6";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
        case "100002";
            $game_host = "103.26.0.71:44444";
            $gamedb = "gamedb4";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
        case "100003";
            $game_host = "103.26.0.71:5555";
            $gamedb = "gamedb5";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
        case "100009";
            $game_host = "103.26.0.71:1111";
            $gamedb = "gamedb1";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
        case "100023";
            $game_host = "103.26.0.71:8888";
            $gamedb = "gamedb8";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
        case "100017";
            $game_host = "103.26.0.71:3333";
            $gamedb = "gamedb3";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
        case "100019";
            $game_host = "103.26.0.71:7777";
            $gamedb = "gamedb7";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
        case "100020";
            $game_host = "103.26.0.71:9999";
            $gamedb = "gamedb9";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
        case "100014";
            $game_host = "103.26.0.71:10000";
            $gamedb = "gamedb10";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
        case "100015";
            $game_host = "103.26.0.19:1111";
            $gamedb = "gamedb1";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
        case "100021";
            $game_host = "103.26.0.39:1111";
            $gamedb = "gamedb1";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
        case "100024";
            $game_host = "103.26.0.39:44444";
            $gamedb = "gamedb4";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
		case "100026";
            $game_host = "103.26.0.39:2222";
            $gamedb = "gamedb2";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
		case "100022";
            $game_host = "103.26.0.39:3333";
            $gamedb = "gamedb3";
            $account_host = "103.26.0.71:3306";
            $accountdb = "accountdb";
            break;
    }
    
    //echo $host." ".$db." ".$port;
    
    $username_game = "monitor";
    $password_game = "monitor!@#$";
    $username_account = "account";
    $password_account = "account";
    
    $conn=mysql_connect($game_host,$username_game,$password_game);
    mysql_query('set names utf8',$conn);

        if (!$conn)
        {
            die('Could not connect: ' . mysql_error());
        }
        
        mysql_select_db("$gamedb", $conn);

        $result_roleinfo = mysql_query("select account_id,role_name,level from role_data where role_name = "."'".$usr_name."'");
        while($row = mysql_fetch_array($result_roleinfo)){
            $account_id=$row['account_id'];
            $role_name=$row['role_name'];
            $level=$row['level'];
        }

        $result_roleinfo = mysql_query("select account_name from account_common where account_id = "."'".$account_id."'");
        while($row = mysql_fetch_array($result_roleinfo)){
            $account_name=$row['account_name'];
        }

        mysql_close($conn);
        
        if(empty($role_name)){
            $Res = array("err_code"=>1,
                         "desc"=>"没有该角色"
                        );
            $Result = json_encode($Res);
            echo $Result;
            exit;
        }
        
        else{
            $conn_account=mysql_connect($account_host,$username_account,$password_account);
            mysql_query('set names utf8',$conn_account);

                if (!$conn_account)
                {
                    die('Could not connect: ' . mysql_error());
                }
                mysql_select_db("$accountdb", $conn_account);
                $result_uid = mysql_query("select third_id from account_third where account_id= "."'".$account_id."'");
                while($row = mysql_fetch_array($result_uid)){
                    $uid=$row['third_id'];
                }
        
                mysql_close($conn_account);
        
                //echo $role_name." ".$level." ".$role_id." ".$uid." ".$app_id." ".$account_id;
                    $Res = array("err_code"=>0,
                                "usr_id"=>$uid,
                                "usr_name"=>$role_name,
                                "usr_rank"=>$level,
                                "player_id"=>$account_id,
                                "app_id"=>$app_id
                                );
                    $Result = json_encode($Res);
                    if(substr($account_name, 0,6) == 'kunlun')
                    {
                    	echo $Result;
                    }
                    elseif(substr($account_name, 0,2) == 'yd')
                    {
                    	echo $Result;
                    }
                    else{
                        $array = array("err_code"=>1,"desc"=>"非云顶用户");
                        echo json_encode($array);
                    }
            }
?>