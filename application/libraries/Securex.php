<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class securex 
{
	protected $skey  = "ovmuIAVTtHgDbGfGjcqkTPxgOskqlltm"; // you can change it
	protected $sykey;
	protected $iv;
	protected $method;

	function __construct()
	{
		$this->ci 		= & get_instance();
		$this->sykey   	= $this->ci->config->item('encryption_key');
		$this->apikey	= $this->ci->config->item('encryption_key');
		$this->iv		= $this->ci->config->item('iv_key');
		$this->method	= $this->ci->config->item('encrypt_method');
	}

	public function safe_b64encode($string)
	{
		$data = base64_encode($string);
		$data = str_replace(array('+','/','='),array('-','_',''),$data);
		return $data;
	}

	public function safe_b64decode($string) 
	{
		$data = str_replace(array('-','_'),array('+','/'),$string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}

	function endec($action, $string)
	{
		$output         = false;
		$secret_key     = $this->sykey;
		$secret_iv      = $this->iv;
		$encrypt_method = $this->method;
		// hash
		$key = hash('sha256', $secret_key);
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);

		//hash $secret_key dengan algoritma sha256 
		$key = hash("sha256", $secret_key);
		if ( $action == 'encrypt' ) {
			$result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($result);
		} else if( $action == 'decrypt' ) {
			$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
		}
		return $output;
	}
	// function encrypt_decrypt($action, $string)
	// {
	// 	$output         = false;
	// 	$encrypt_method = "AES-256-CBC";
	// 	$secret_key     = $this->sykey;
	// 	$secret_iv      = $secret_key;
	// 	// hash
	// 	$key = hash('sha256', $secret_key);
	// 	// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
	// 	$iv = substr(hash('sha256', $secret_iv), 0, 16);

	// 	if ( $action == 'encrypt' ) {
	// 		$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	// 		$output = base64_encode(base64_encode(base64_encode(base64_encode($output))));
	// 	} else if( $action == 'decrypt' ) {
	// 		$output = openssl_decrypt(base64_decode(base64_decode(base64_decode(base64_decode($string)))), $encrypt_method, $key, 0, $iv);
	// 	}
	// 	return $output;
	// }

	public function hash_password($string)
	{
		//$key    = $this->config->item('encryption_key');
		$salt1  = hash('sha512', $this->sykey . $string);
		$salt2  = hash('sha512', $string . $this->sykey);
		return hash('sha512', $salt1 . $string . $salt2);
		//return hash('sha512', $string . config_item('encryption_key'));
	}
}

