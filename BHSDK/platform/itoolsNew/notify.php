<?php
class notify
{
	private $pubKey = '
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC2kcrRvxURhFijDoPpqZ/IgPlA
gppkKrek6wSrua1zBiGTwHI2f+YCa5vC1JEiIi9uw4srS0OSCB6kY3bP2DGJagBo
Egj/rYAGjtYJxJrEiTxVs5/GfPuQBYmU0XAtPXFzciZy446VPJLHMPnmTALmIOR5
Dddd1Zklod9IQBMjjwIDAQAB
-----END PUBLIC KEY-----';
	private $pubRes = '';

	public function __construct() 
	{
		//$pubKeyPath = dirname(dirname(__FILE__)) . '/rsa_public_key.pem';

		//$this->pubKey = file_get_contents($pubKeyPath);
		$this->pubRes = openssl_get_publickey($this->pubKey);
	}

	/**
	* 解密数据
	*/
	public function decrypt($data)
	{
		$data = base64_decode($data);
		$maxlength = 128;
		$output = '';
		while ($data) {
			$input = substr($data, 0, $maxlength);
			$data = substr($data, $maxlength);
			openssl_public_decrypt($input, $out, $this->pubRes);

			$output .= $out;
		}

		return $output;
	}

	/**
	* 签名验证
	*/
	public function verify($data, $sign)
	{
		$result = openssl_verify($data, base64_decode($sign), $this->pubRes);

		return $result;
	}

	public function __destruct()
	{
		openssl_free_key($this->pubRes);
	}
}
?>
