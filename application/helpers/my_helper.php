<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('delete_old_sessions')) {
    
    function delete_old_sessions() {
        // Mendapatkan instance CI untuk akses database
        $CI =& get_instance();
        $driver = $CI->config->item('sess_driver');
		if(strtolower($driver)=="files"){
			// Tentukan path direktori tmp
			$dir = FCPATH.'tmp'; // Folder tmp default di server
			//$dir = '/path/to/your/tmp/folder'; // Jika direktori tmp khusus
			
			// Dapatkan waktu saat ini
			$now = time();

			// Ambil semua file di direktori tmp
			$files = scandir($dir);

			// Loop melalui setiap file
			foreach ($files as $file) {
				$file_path = $dir . '/' . $file;

				// Periksa apakah ini adalah file (bukan direktori)
				if (is_file($file_path)) {

					// Dapatkan waktu modifikasi terakhir file
					$file_mod_time = filemtime($file_path);

					// Hitung perbedaan waktu
					$diff_in_seconds = $now - $file_mod_time;

					// Jika file lebih dari 1 hari yang lalu (86400 detik = 24 jam)
					if ($diff_in_seconds > 172800) {
						// Hapus file
						unlink($file_path);
						// log_message('info', 'Deleted tmp file: ' . $file_path);
					}
				}
			}
		}elseif(strtolower($driver)=="database"){
			// Waktu sekarang
			$now = time();
			
			// 86400 detik = 1 hari
			$yesterday = $now - 86400; 
			
			// Hapus semua data session yang lebih lama dari 1 hari
			$CI->db->where('timestamp <', $yesterday);
			$CI->db->delete('ci_sessions');
			
			if ($CI->db->affected_rows() > 0) {
				// log_message('info', 'Deleted old sessions from ci_sessions table.');
			} else {
				// log_message('info', 'No old sessions to delete.');
			}
		}
    }
}

function key_google(){
	return "AIzaSyCoR8XQr6YwMLTyPq8ABc5FppBTXkc60BE";
}
function key_firebase(){
	$data = [
		"apiKey"			=> "AIzaSyCoR8XQr6YwMLTyPq8ABc5FppBTXkc60BE",
		"authDomain"		=> "first-discovery-401904.firebaseapp.com",
		"projectId"			=> "first-discovery-401904",
		"storageBucket"		=> "first-discovery-401904.appspot.com",
		"messagingSenderId"	=> "213831062450",
		"appId"				=> "1:213831062450:web:289159c02cf68fd4378aab",
		"measurementId"		=> "G-DWM1NGGK9F"
	];
	return json_encode($data);
}
function key_firebasex(){
	return "AIzaSyCoR8XQr6YwMLTyPq8ABc5FppBTXkc60BE";
}
function key_notif(){
	return "AAAAMclTE7I:APA91bED4GaLERoZdCMRTIMwzEaRQ-dh1yglJWrCFoJD3j_w3h_hHXYMhVE1h4AGIYEYFsyGks5GpWX8sX-aKvLvSUSkXjW-DnWRIK6N5_RKxWEHEZX2yrucB_xHMcdQCndfAchdARNi";
}
function logo_apps(){
	return base_url("assets/images/logo/logo_only.png");
}
function logo_apps_white(){
	return base_url("assets/images/logo/logo_only.png");
}
function logo_apps_doc(){
	return base_url("assets/images/logo/logo_only.png");
}
function logo_apps_label(){
	return base_url('assets/images/logo/logo_only.png');
}
function logo_apps_login(){
	return base_url('assets/images/logo/logo_only.png');
}
function apps_name(){
	return "KOMANDO HRD";
}
function apps_login(){
	return "KOMANDO HRD";
}
function brand_name(){
	return "KOMANDO";
}
function apps_name_title(){
	return "KOMANDO HRD";
}
/** Credential Test MProgramming END */

function Void($var)
{
	if (empty($var) === true)
	{
		if (($var === 0) || ($var === '0'))
		{
			return false;
		}else{
			return true;
		}
	}else{
		return false;
	}
}
?>