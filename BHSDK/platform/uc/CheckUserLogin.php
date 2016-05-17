<?php
header("Content-type: text/html; charset=utf-8");

require_once 'ManageUcUser.php';
$config = include "config.inc.php";
//echo file_put_contents("/tmp/test88.txt", "222222222222222");//把内容写入到一个文件
$area = $_GET['area'];
$sid = $_GET['sid'];
$server = isset($_GET['server'])?$_GET['server']:'';
//echo file_put_contents("/tmp/test88.txt", "222222222222222");//把内容写入到一个文件
//合并区服时使用  此加载文件只可在此区域加载
require_once '../../ext/CheckArea.php';

if(is_null($area))
{
    $area = 100;
}

$verifySid = new sidInfo($config);
$verifySid->sidInfoMethod($area, $sid,$server);

class sidInfo{
	public $gameId;
	public $serverUrl;
    private $config = array();
	

	public function __construct($config){
		$this->config 		= $config;
		if(is_array($this->config)&& $this->config!=null){
			if(array_key_exists("serverUrl", $this->config)) 
				$this->serverUrl 	= $this->config['serverUrl'];
			if(array_key_exists("gameId", $this->config)) 
				$this->gameId		= intval($this->config['gameId']);
			if(array_key_exists("apiKey", $this->config)) 
				$this->apiKey 		= $this->config['apiKey'];	
		}else{
			throw new exception('配置为空');
		}
	}

	/**
	 * sid用户会话验证。
	 * @param sid 从游戏客户端的请求中获取的sid值
	 */
	public function sidInfoMethod($area, $sid,$server)
    {
		//echo "[开始调用sidInfo接口]";
		///////////////////////////////////////////////////
		$gameParam = array();
		//$gameParam['cpId']			= $this->cpId;//cpid是在游戏接入时由UC平台分配，同时分配相对应cpId的apiKey
		$gameParam['gameId']		= $this->gameId;//gameid是在游戏接入时由UC平台分配
		//$gameParam['channelId']		= $this->channelId;//channelid是在游戏接入时由UC平台分配
		
		//serverid是在游戏接入时由UC平台分配，
		//若存在多个serverid情况，则根据用户选择进入的某一个游戏区而确定。
		//若在调用该接口时未能获取到具体哪一个serverid，则此时默认serverid=0
		//$gameParam['serverId']		= $this->serverId;
		//////////////////////////////////////////////////
		$dataParam = array();
		$dataParam['sid']			= $sid;//在uc sdk登录成功时，游戏客户端通过uc sdk的api获取到sid，再游戏客户端由传到游戏服务器
		$signSource					= "sid=".$sid.$this->apiKey;//组装签名原文
        file_put_contents("/tmp/xiaobai/test9933.txt", $signSource.'\n');//把内容写入到一个文件
        //要删除
     //   $signSource					= 'sid=abcdefg123456202cb962234w4ers2aaa';//组装签名原文
		$sign						= md5($signSource);//MD5加密签名


		$requestParam = array();
		$requestParam["id"] 		= time();//当前系统时间
		//$requestParam["service"] 	= "ucid.user.sidInfo";
		$requestParam['game']		= $gameParam;
		$requestParam['data']		= $dataParam;
		//$requestParam['encrypt']	= "md5";
		$requestParam['sign']		= $sign;

		$requestString 				= json_encode($requestParam);//把参数序列化成一个json字符串
		$result = $this->request($this->serverUrl,$requestString);//http post方式调用服务器接口,请求的body内容是参数json格式字符串
		$responseData = json_decode($result['msg'],true);//结果也是一个json格式字符串
        //要删除
        $strin=json_encode($responseData);

        file_put_contents("/tmp/xiaobai/test9933.txt", $strin);//把内容写入到一个文件
        if($responseData!=null)
        {
            if($responseData['state']['code'] == 1)
            {   
                $ucaccountId = $responseData['data']['accountId'];
				$ucid = abs(crc32($ucaccountId));
				//echo file_put_contents("/tmp/test9933.txt", $ucid);//把内容写入到一个文件
                $new = new ManageUc();
                $new->manageUcUser($area, $ucid, $server, $ucaccountId);
            }
            else
            {
                echo '[code]='.$responseData['state']['code'];
            }

		}
        else
        {
			throw new Exception("[接口返回异常]");
		}
	}

    public function request($Url, $Params, $Method='post'){

        $Curl = curl_init();//初始化curl
        file_put_contents("/tmp/xiaobai/test9933.txt", $Url.'?'.$Params);//把内容写入到一个文件
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