<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Midware
{
	function middleWare()
	{
		// $_ci =& get_instance();
		// get_csrf_token();
		// $CI =& get_instance();

		// Muat helper CSRF
		// $CI->load->helper('csrf');

		// Hanya periksa token CSRF untuk request POST
		// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		// 	// Ambil token dari input POST
		// 	$token = $CI->input->post('csrfsession');

		// 	// Validasi token
		// 	if (!validate_csrf_token($token)) {
		// 		// Token tidak valid, tampilkan error 403
		// 		show_error('Token CSRF tidak valid atau sudah kadaluarsa.', 403);
		// 	}

		// }
		// Perbarui token jika sudah kadaluarsa
		delete_old_sessions();
		if (token_is_expired()) {
			generate_csrf_token();
		}
	}
}	