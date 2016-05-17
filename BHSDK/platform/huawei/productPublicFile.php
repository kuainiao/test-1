<?php

header("Content-Type: text/html; charset=utf-8");

$filename = dirname(__FILE__)."/payPublicKey.pem";

@chmod($filename, 0777);
@unlink($filename);

$devPubKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjpaw3OPEr9BkTfdn+c0Aqlz68a9dQHCZ+52aW6enWbIwzwTMMaD5l6lfGRF3znH0TZkwJhiUtvb40bTuTrFU6OVhjHwIuDfqdmSKMuPYuAyzdbFPhBTVIt75bdEUhCmSUm+kd5gFLKXzZb2xB11mUZ1Md/UWONiaXjRCZTWPDkDC/iI/sRxkHdr+xriUKwGmb0Up06XPShVdt+tv3chc342gSayIBS8yd8otoInyg/JJWaO1pM89n3CC0ncCIozLSHEo0AhMWIyDQqBI76IgCz9X+zJXk9sCzV/CK+Zo1cdi5mlBEiNKZzWJnQWYXgP5w1IdvdXM2xkMbnqfz02xIQIDAQAB";
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
echo file_get_contents($filename);


?>

