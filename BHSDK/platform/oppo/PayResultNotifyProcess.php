<?php
header("Content-type: text/html; charset=utf-8");
define("APP_KEY",'dfsdfs');
require_once '../../ext/Ext.php';
require_once '../../models/PlatModel.php';
require_once '../../socket/ConnectServer.php';
function get_param($param_name)//兼容post/get
{
    $param_value = "";
    if(isset($_POST[$param_name])){
        $param_value = trim($_POST[$param_name]);
    }else if(isset($_GET[$param_name])){
        $param_value = trim($_GET[$param_name]);
    }
    return $param_value;
}

$request['notifyId'] = get_param('notifyId');
$request['partnerOrder'] = get_param('partnerOrder');
$request['productName'] = get_param('productName');
$request['productDesc'] = get_param('productDesc');
$request['price'] = get_param('price');
$request['count'] = get_param('count');
$request['attach'] = get_param('attach');
$request['sign'] = get_param('sign');
$request['order_id'] = get_param('partnerOrder');

file_put_contents('/tmp/lererl',json_encode($request));
//file_put_contents('/tmp/lksdfjdsljkfsldkfsdl',json_encode($_POST));
/*
$sr = '{"notifyId":"GC2016033116252757100083909561","partnerOrder":"1459412727082","productName":"\u5143\u5b9d","productDesc":"\u4e00\u5806\u5143\u5b9d","price":"100","count":"1","attach":"20003","sign":"XLa0GIyrottt\/5A74XDqGPqanPrzoP3VH3jGxQtTQ30ZE4OiTiHNvEt\/g3Nti+mysxhKqyncbG15c\/E2PkgFmNNK8w\/b8R8xQmPZ6fnVbpegj1\/fsMy0NP8xr\/nT1QBrm214K9c4+\/Ybnh4+O\/L4SCJ15QuUpJSEWHgGLN0EzLQ="}';
$request = json_decode($sr, true);
*/
$Res = pay_result_notify_process($request);


 function pay_result_notify_process($request)
    {
        $thirdType = 13;
        $pay_table =  'platform_oppo_pay';
		if(!rsa_verify($request)){
			die('result=FAIL&resultMsg=验证错误');
            file_put_contents('/tmp/fail','failse');
		} 

			$uid = $_POST['userId'];
			$server = $request['attach'];
			//file_put_contents('/tmp/sdsdsd0000',$server);
        $newdb = new PlatModel();
        $selectOrderId = $newdb->selectOpPay($uid,$request['notifyId'],'platform_oppo_pay');
		//file_put_contents('/tmp/sdsdsd4444',$selectOrderId);
        //验证是否充值过
        if ($request['notifyId'] == $selectOrderId)
        {	//file_put_contents('/tmp/sdsdsd111',$request['notifyId']);
            die('result=OK&resultMsg=成功');
        }
        else
        {
            $sdkPaytime = time();
            $area = Ext::getInstance()->getArea($server);
			//file_put_contents('/tmp/sdsdsd2222',$area);	
            $gameMoney = $request['price']/10;
            $insert = $newdb->insertOpPay($request,$uid,$server,$sdkPaytime,$area,$gameMoney,$channel_type=1);
            if($insert)
            {
				//file_put_contents('/tmp/sdsdsd333',$insert);
                $accountId = $newdb->selectThirdUserAccountId('platform_oppo_user',$area,$uid);
                $username = $newdb->selectThirdUserWzUsername('platform_oppo_user',$area,$uid);
                $socketClass = new ConnectGameServer();
                $socketClass->payToGameServer($server,$thirdType,$username,$accountId,$gameMoney,$request['notifyId']);
				$newdb->insertUserpaylog($uid, $thirdType, $request['notifyId'], $sdkPaytime, $area, $server, $request['price']/100, $accountId);
                echo 'result=OK&resultMsg=成功';
            }
            else
            {
            	die('result=FAIL&resultMsg=插入游戏平台订单失败');
            }
        }
    }

function rsa_verify($request) {
    //var_dump($request);

	$str_contents = "notifyId={$request['notifyId']}&partnerOrder={$request['partnerOrder']}&productName={$request['productName']}&productDesc={$request['productDesc']}&price={$request['price']}&count={$request['count']}&attach={$request['attach']}";
    var_dump($str_contents);

    file_put_contents('/tmp/ewewr',$str_contents);
    $publickey='MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCmreYIkPwVovKR8rLHWlFVw7YDfm9uQOJKL89Smt6ypXGVdrAKKl0wNYc3/jecAoPi2ylChfa2iRu5gunJyNmpWZzlCNRIau55fxGW0XEu553IiprOZcaw5OuYGlf60ga8QT6qToP0/dpiL/ZbmNUO9kUhosIjEu22uFgR+5cYyQIDAQAB';
	$pem = chunk_split($publickey,64,"\n");
	$pem = "-----BEGIN PUBLIC KEY-----\n".$pem."-----END PUBLIC KEY-----\n";
	$public_key_id = openssl_pkey_get_public($pem);
    //var_dump($public_key_id );

    //file_put_contents('/tmp/ewewr',json_encode($public_key_id));
	$signature =base64_decode($request['sign']);

	return openssl_verify($str_contents, $signature, $public_key_id );
}
?>