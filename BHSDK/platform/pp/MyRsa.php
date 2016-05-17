<?php

/**
 * rsa
 * 需要 openssl 支持
 * @author andsky
 *
 */
class MyRsa extends Rsa
{

    private static $_instance;

    const private_key = '';
    const public_key = '
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvwgZkvmVZZGb2C8ebbnl
RmM00fPmNuhG7AqSfnAIPEQW3KYvOFTFXhQeRUy5LjkCJro+Uy7ZfVsu3I1Fmduh
oRguZNr1N9a+Qg6xs13VW7XGjySnenZLvxbn3VzAmVMHX1rQJJB0boPEefqi+0Iq
Nh11YbrRtx1eYRSzuBSPAcDQO2RtGr67gq0wy0uZ7CrLcZ7fAlp1JsuOvY87gbMW
jtZgoAuZprBSPtVzw1I/6HN/TJIrd4AvDyjIedplGgOX0dWzCDOBsBNuL7fSkF5d
19YX1DKTG+wLTiOl9I8tbgfyCSb8OuYjLHmakBXPewtSyvrHYTdg+lWKjo6MoX66
TwIDAQAB
-----END PUBLIC KEY-----';
    
    
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


   /* function sign($sourcestr = NULL)
    {

        return base64_encode(parent::sign($sourcestr, self::private_key));

    }

    function verify($sourcestr = NULL, $signature = NULL)
    {
        return parent::verify($sourcestr, $signature, self::public_key);

    }*/


}
?>
