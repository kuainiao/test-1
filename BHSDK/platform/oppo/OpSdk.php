<?php

date_default_timezone_set('Asia/ShangHai');
require_once '../../models/PlatModel.php';
require_once 'ManageOpUser.php';
require_once "gc_login.php";


header("Content-type: text/html; charset=utf-8");


class OpSdk{

	public function check_user_login($area,$token,$field,$server,$channel_type=1)
    {
		file_put_contents('/tmp/111111',$token);
        $gclogin= new gc_login_base($field,$token);
		$user = $gclogin->getUserInfo();
		file_put_contents('/tmp/222222',$user);
		if($user){
			if (($user['resultCode'] != '200')) {
				die("远程返回有误");
			}

				$new = new ManageOp();
		        $new->manageOpUser($area,$user['ssoid'],$server,$channel_type);

		}
	}
}
?>
