<?php

header("Content-Type: text/html; charset=utf-8");

$filename = dirname(__FILE__)."/payPublicKey.pem";

@chmod($filename, 0777);
@unlink($filename);

$devPubKey = "MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBAJ4flep3uoi+UwalabsoJXZeZaqyXfcx3ueWPSv0BzihJed0a7ypVJ0JktG7wP2yXyfUmD8CIHARcmHMT5TBupMCAwEAAQ==";
$begin_public_key = "-----BEGIN PUBLIC KEY-----\r\n";
$end_public_key = "-----END PUBLIC KEY-----\r\n";


$fp = fopen($filename,'ab');
fwrite($fp,$begin_public_key,strlen($begin_public_key));

$raw = strlen($devPubKey)/64;
$index = 0;
while($index <= $raw )
{
    $line = substr($devPubKey,$index*64,64)."\r\n";
    if(strlen(trim($line)) > 0)
        fwrite($fp,$line,strlen($line));
    $index++;
}
fwrite($fp,$end_public_key,strlen($end_public_key));
fclose($fp);
?>

