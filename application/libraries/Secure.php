<?php
if (!defined("BASEPATH")) exit("No direct script access allowed");

class secure
{
    // protected $ci;
    // protected $enkey;
    protected $sykey;
    protected $iv;
    protected $method;

    function __construct()
    {
        $this->ci        = &get_instance();
        
        // if ($this->ci) {
        //     return $this->ci;
        //     // exit;
        // }

        $this->enkey     = $this->ci->config->item('encryption_key');
        $this->iv        = $this->ci->config->item('iv_key');
        $this->method    = $this->ci->config->item('encrypt_method');
    }

    function enc($string)
    {
        $output = false;

        // $security = parse_ini_file('security.ini'); // parsing file security.ini output:array asosiatif
        //Hasil parsing masukkan kedalam variable
        $secret_key     = $this->enkey;
        $secret_iv      = $this->iv;
        $encrypt_method = $this->method;

        //hash $secret_key dengan algoritma sha256 
        $key = hash("sha256", $secret_key);

        //iv(initialize vector), encrypt iv dengan encrypt method AES-256-CBC (16 bytes)
        $iv     = substr(hash("sha256", $secret_iv), 0, 16);
        $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($result);
        return $output;
    }

    // untuk decrip key
    function dec($string)
    {
        $output = false;

        // $security = parse_ini_file('security.ini'); // parsing file security.ini output:array asosiatif
        //Hasil parsing masukkan kedalam variable
        $secret_key     = $this->enkey;
        $secret_iv      = $this->iv;
        $encrypt_method = $this->method;

        //hash $secret_key dengan algoritma sha256 
        $key = hash("sha256", $secret_key);

        //iv(initialize vector), encrypt $secret_iv dengan encrypt method AES-256-CBC (16 bytes)
        $iv     = substr(hash("sha256", $secret_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }
}
