<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

function generate_csrf_token() {
    $CI =& get_instance();

    // Mengecek apakah token CSRF sudah ada dalam session
    if (!$CI->session->has_userdata('csrf_token') || token_is_expired()) {
        // Membuat token baru
        $token = bin2hex(openssl_random_pseudo_bytes(16)); // Token random 32 karakter
        $CI->session->set_userdata('csrf_token', $token);

        // Set waktu kedaluwarsa token (2 jam atau 7200 detik)
        $CI->session->set_userdata('csrf_token_time', time());
    }

    // Mengembalikan token yang ada atau baru
    return $CI->session->userdata('csrf_token');
}

function token_is_expired() {
    $CI =& get_instance();

    // Periksa apakah token sudah kedaluwarsa (lebih dari 2 jam)
    $csrf_time = $CI->session->userdata('csrf_token_time');
    $csrf_lifetime = 7200; // 2 jam (dalam detik)

    return (time() - $csrf_time) > $csrf_lifetime;
}

function get_csrf_token() {
    return generate_csrf_token(); // Dapatkan token CSRF
}

function validate_csrf_token() {
    $CI =& get_instance();

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $token = $CI->input->post('csrfsession');
    }elseif($_SERVER['REQUEST_METHOD']==='GET'){
        $token = $CI->input->get('csrfsession');
    }else{
        $token = "";
    }

    $session_token = $CI->session->userdata('csrf_token');

    // Validasi apakah token dari request cocok dengan token dalam session
    // var_dump('<pre>');var_dump($token);var_dump($session_token);die;
    return $token === $session_token;
}

function csrf_input() {
    $token = get_csrf_token();
    return '<input type="hidden" name="csrfsession" value="' . $token . '">';
}