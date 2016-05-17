<?php
require_once 'ManagePpUser.php';

header("Content-type: text/html; charset=utf-8");
$area = isset($_GET['area'])?$_GET['area']:null;
$uid = isset($_GET['uid'])?$_GET['uid']:null;//平台uid
$server = isset($_GET['server'])?$_GET['server']:'';

//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}
if($uid&&$area){
	$new = new ManageP();
	$new->managePUser($area,$uid,$server,2);
}else{
	echo "参数错误!";
}

?>