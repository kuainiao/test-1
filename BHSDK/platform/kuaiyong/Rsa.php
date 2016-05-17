<?php

/**
 * rsa
 * 需要 openssl 支持
 * @author andsky
 *
 */
class Rsa
{

    private static $_instance;
	
	const private_key = 'rmFW9oeson48iegfKBotXT5bdBaofNGH';
    //const public_key  = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDld1BOYy9maeljngpkaZ4jYcunvT7oELcu7xYS2CE0NYxfZ+Wqhtzsx1dC+yHmE/BNEDGyiu5KmT+UANSMaEB1gWoysDjacGLhVJDm0E/JAtAZFjf+8NBYeTOvOT7AS/x+/k3L4JzlyeW/huDVmNc0Ks7qrBC6408wxuctvYfeuwIDAQAB';
    const public_key = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCWKhyIsCIexukgdGqnuIdzyOFPSfBE5lTYgaxwIBCYPAgoSk9yKrjqUYpCZ7XaM+TTTzfmjQ43Mm+kNAkiWGYeGqQGaKnA7AvOQj7PxjqX4AlQp7C3UhTmsisBFjAUom3Mv0BjyLurcfgXTsH9gaSKpg3SfUjYJLPGSxS5dLd4RwIDAQAB';


    function __construct ()
    {
    }


    function __destruct ()
    {
    }

    public static function instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * 生成
     * @param unknown_type $bits
     * @author andsky 669811@qq.com
     */
    public function create($bits = 1024)
    {
        $rsa = openssl_pkey_new(array('private_key_bits' => $bits,'private_key_type' => OPENSSL_KEYTYPE_RSA));
        while( ( $e = openssl_error_string() ) !== false ){
            echo "openssl_pkey_new: " . $e . "\n";
        }
        openssl_pkey_export($rsa, $privatekey);
        while( ( $e = openssl_error_string() ) !== false ){
            echo "openssl_pkey_new: " . $e . "\n";
        }
        $publickey = openssl_pkey_get_details($rsa);
        $publickey = $publickey['key'];
        return array(
                'privatekey' => $privatekey,
                'publickey' => $publickey
                );
    }


    /**
     * 公匙加密
     * @param unknown_type $sourcestr
     * @param unknown_type $publickey
     * @author andsky 669811@qq.com
     */
    function publickey_encodeing($sourcestr, $publickey)
    {

        $pubkeyid = openssl_get_publickey($publickey);

        if (openssl_public_encrypt($sourcestr, $crypttext, $pubkeyid, OPENSSL_PKCS1_PADDING))
        {
            return $crypttext;
        }
        return FALSE;
    }


    /**
     * 公匙解密
     * @param unknown_type $crypttext
     * @param unknown_type $publickey
     * @author andsky 669811@qq.com
     */
    function publickey_decodeing($crypttext, $publickey)
    {
        $pubkeyid = openssl_get_publickey($publickey);
        if (openssl_public_decrypt($crypttext, $sourcestr, $pubkeyid, OPENSSL_PKCS1_PADDING))
        {
            return $sourcestr;
        }
        return FALSE;
    }

    /**
     * 私匙解密
     * @param unknown_type $crypttext
     * @param unknown_type $privatekey
     * @author andsky 669811@qq.com
     */
    function privatekey_decodeing($crypttext, $privatekey)
    {

        $prikeyid = openssl_get_privatekey($privatekey);
        if (openssl_private_decrypt($crypttext, $sourcestr, $prikeyid, OPENSSL_PKCS1_PADDING))
        {
            return $sourcestr;
        }
        return FALSE;
    }


    /**
     * 私匙加密
     * @param unknown_type $sourcestr
     * @param unknown_type $privatekey
     * @author andsky 669811@qq.com
     */
    function privatekey_encodeing($sourcestr, $privatekey)
    {

        $prikeyid = openssl_get_privatekey($privatekey);
        if (openssl_private_encrypt($sourcestr, $crypttext, $prikeyid, OPENSSL_PKCS1_PADDING))
        {
            return $crypttext;
        }
        return FALSE;
    }

    function sign($sourcestr, $privatekey)
    {
        $pkeyid = openssl_get_privatekey($privatekey);
        openssl_sign($sourcestr, $signature, $pkeyid);
        openssl_free_key($pkeyid);
        return $signature;

    }

    function verify($sourcestr, $signature, $publickey)
    {
        $pkeyid = openssl_get_publickey($publickey);
        $verify = openssl_verify($sourcestr, $signature, $pkeyid);
        openssl_free_key($pkeyid);
        return $verify;

    }



    public function convert_publicKey($public_key){
    
    	$public_key_string = "";
    
    	$count=0;
    	for($i=0;$i<strlen($public_key);$i++){
    		if($count<64){
    			$public_key_string.=$public_key[$i];
    			$count++;
    		}else{
    			$public_key_string.=$public_key[$i]."\r\n";
    			$count=0;
    		}
    	}
    
    	$public_key_header = "-----BEGIN PUBLIC KEY-----\r\n";
    	$public_key_footer = "\r\n-----END PUBLIC KEY-----";
    	$public_key_string = $public_key_header.$public_key_string.$public_key_footer;
    	
    	return $public_key_string;
    }

}
?>