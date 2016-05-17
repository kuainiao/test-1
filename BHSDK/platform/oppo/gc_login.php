<?php
/**
 * @author Simon 2013 sanwkj@163.com
 * @copyright yunzhongfei
 */

 // appKey  请求注意替换！！！
define('NEARME_CONSUMER_KEY', 'vR398Nn7c0g804sGS04wc0ko');
// appSecret 请求注意替换！！！
define('NEARME_SHA1_KEY_BASE', 'BC32D5d7d9536f30b01A2FC49B56c560');
// 接口地址
define('NM_UC_SERVER', 'http://i.open.game.oppomobile.com/gameopen/user/fileIdInfo');

class gc_login_base{
		
	protected $token;
	protected $field;
	
	function __construct($field=null,$token=null ){
		$this->token=$token;
		$this->field=$field;
	}

	private function signature($oauthSignature, $requestString){
		return urlencode(base64_encode( hash_hmac( 'sha1', $requestString,$oauthSignature,true) ));
	}

	
	/**
	     * 请求的参数串组合
	     */
	private function _assemblyParameters($dataParams){
	   $requestString 				= "";
		foreach($dataParams as $key=>$value){
		  $requestString = $requestString . $key . "=" . $value . "&";
		}
		return $requestString;
	}

	private function oppoCurl()
	{
		$time=microtime(true);

		$params=array(
			'oauthConsumerKey'=>NEARME_CONSUMER_KEY,
			'oauthToken'=>$this->token,
			'oauthSignatureMethod'=>'HMAC-SHA1',
			'oauthTimestamp'=> intval( $time *1000),
			'oauthNonce'=>intval($time) + rand(0,9),
			'oauthVersion'=>'1.0',

		);


		$requestString 						= $this->_assemblyParameters($params);

		$oauthSignature = NEARME_SHA1_KEY_BASE ."&";
		$sign 			= $this->signature($oauthSignature,$requestString);
		$request_serverUrl = NM_UC_SERVER ."?fileId=".$this->field."&token=".$this->token;
		$result 		= $this->OauthPostExecuteNew($sign,$requestString,$request_serverUrl);
        return $result;


	}

	public function OauthPostExecuteNew($sign,$requestString,$request_serverUrl){
		$opt = array(
			"http"=>array(
				"method"=>"GET",
				'header'=>array("param:".$requestString, "oauthsignature:".$sign),
			)
		);
		//var_dump($opt);
		//echo $request_serverUrl;

		$res=file_get_contents($request_serverUrl,null,stream_context_create($opt));
		return $res;
	}
	


	public function getUserInfo( $secret=null,$token=null ){

		$res = $this->oppoCurl();
		file_put_contents('/tmp/3333333',$res);
		//var_dump($res);
		if($res&&(strpos($res, 'errorCode')===false&&strpos($res, 'errorCode')===false)){
			file_put_contents('/tmp/444444',json_decode(urldecode($res),true));
			return json_decode(urldecode($res),true);
		}else{
			file_put_contents('/tmp/5555555',urldecode($res));
			return urldecode($res);
			return false;
		}
	}
}
?>