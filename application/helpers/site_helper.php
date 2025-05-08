<?php 

// start csrf token
if(!function_exists('get_csrf_token')){
    function get_csrf_token(){
        $ci =& get_instance();
        if(!$ci->session->csrf_token){
            $ci->session->set_userdata('csrf_token', hash('sha1',time()));
        }
        return $ci->session->csrf_token;
    }
}

if(!function_exists('get_csrf_name')){
    function get_csrf_name(){
        return "csrfsession";
    }
}

if(!function_exists('cek_csrf')){
    function cek_csrf(){
        $ci =& get_instance();
        if($ci->input->post('csrfsession') != $ci->session->csrf_token or !$ci->input->post('csrfsession') or !$ci->session->csrf_token){
            $ci->session->unset_userdata('csrf_token');
            $ci->output->set_status_header(403);
            show_error('This session is invalid');
            die;
            // return false;
        }
    }
}

if(!function_exists('cek_csrf_return')){
    function cek_csrf_return(){
        $ci =& get_instance();
        if($ci->input->post('csrfsession') != $ci->session->csrf_token or !$ci->input->post('csrfsession') or !$ci->session->csrf_token){
            $ci->session->unset_userdata('csrf_token');
            return false;
        }else{
            return true;
        }
    }
}

if(!function_exists('cek_get_csrf')){
    function cek_get_csrf(){
        $ci =& get_instance();
        if($ci->input->get('csrfsession') != $ci->session->csrf_token or !$ci->input->get('csrfsession') or !$ci->session->csrf_token){
            $ci->session->unset_userdata('csrf_token');
            $ci->output->set_status_header(403);
            show_error('This session is invalid');
            die;
            // return false;
        }
    }
}

function csrf_input(){
    return "<input type='hidden' name='".get_csrf_name()."' value='".get_csrf_token()."' />";
}

// end csrf token

