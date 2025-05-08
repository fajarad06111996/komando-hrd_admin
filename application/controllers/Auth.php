<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';

use Nullix\CryptoJsAes\CryptoJsAes;
class Auth extends CI_Controller
{
	protected $username;
	protected $level;
	protected $enkey;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_access', 'mAccess');
		$this->load->model('M_auth');
		$this->load->library('security_function');
		$this->load->library('secure');
		$this->username = $this->session->userdata('JTuser_id');
		$this->level  	= $this->secure->dec($this->session->userdata('JTlevel'));
		$this->enkey  	= $this->config->item('encryption_key');
	}

	// untuk view form login
	public function index()
	{
		$session 	= $this->session->JTsession_id;
		if($session) {
			redirect('home');
		}else{
			$params     	        = '{"csrf": "'.$this->session->csrf_token.'", "base_url": "'.base_url().'"}';
			// encrypt
			$encrypted          = CryptoJsAes::encrypt($params, $this->enkey);
			$data['params']     = $encrypted;
			$data['session']	= $session;
			$this->load->view('auth/login', $data);
		}
	}

	// untuk proses login 
	public function loginApps()
	{
		$this->form_validation->set_rules('tUname', 'Username', 'required|min_length[4]|max_length[55]');
		$this->form_validation->set_rules('tPass', 'Password', 'required|min_length[4]');
	    
		// untuk validasi inputan username dan password
	 	if ($this->form_validation->run() == false) {
			$msg['status'] 	= false;
			$msg['msg']		= validation_errors();
            echo json_encode($msg);die;
		}

		$cekCsrf = validate_csrf_token();
		// echo json_encode($cekCsrf);die;

		// cek csrf tokennya
		if($cekCsrf == false){
			$msg['status'] 	= false;
			$msg['msg']		= 'You can not get the right to access from another site.';
            echo json_encode($msg);die;
			// die("<center><h1>You can not get the right to access from another site</h1></center>");
		}

		$username		= trim(filter_var($this->input->post('tUname',TRUE), FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH));
		$pass_user		= trim($this->input->post('tPass', TRUE));
		$checking 		= $this->M_auth->check_login($username,$pass_user); // dari model check_login
		// var_dump('<pre>');var_dump($checking);die;
		// jika tipe data variabel $checking berupa object atau array
		if(gettype($checking) == 'object' || gettype($checking) == 'array') {
			$lev	    = $this->M_auth->getLevelUser($username);
			$off	    = $this->mAccess->getTable('user_account',[ 'user_id' => $username])->row_array(); // output berupa array assosiatif
			$company   	= (int)$off['company_idx'];
			$level	    = (int)$lev['user_level_id'];
			$result 	= $this->M_auth->getAccess($level,$company);
			$res_sub	= $this->M_auth->getAccessSub($level,$company);
			$nilai		= null;
			$point		= null;
			$nilaiSub	= null;
			$pointSub	= null;
			$param		= $this->M_auth->selectAccessDetail($level,$company);
			$paramSub	= $this->M_auth->selectChildAccessDetail($level,$company);

			foreach($param->result() as $row):
				if(!empty($row->access_submenu_id)):
					$nilai	.= $row->access_submenu_id.",";
					$point	.= $row->access_permissions_id.",";
				endif;
			endforeach;
	
			if(!empty($nilai) && !empty($point)):
				$nilai 	= substr($nilai,0,strlen($nilai)-1);
				$point 	= substr($point,0,strlen($point)-1);
				$parameter=array();
				$detail_menu=$this->M_auth->SelectMenuChildName($nilai);
				foreach($detail_menu->result() as $row):
					$parameter[]=str_replace(" ","",strtolower($row->menu_title));
				endforeach;
				$point 	= explode(",",$point);
			endif;

			foreach($paramSub->result() as $c):
				if(!empty($c->access_submenu_id)):
					$nilaiSub	.= $c->access_submenu_id.",";
					$pointSub	.= $c->access_permissions_id.",";
				endif;
			endforeach;

			if(!empty($nilaiSub) && !empty($pointSub)):
				$nilaiSub 			= substr($nilaiSub,0,strlen($nilaiSub)-1);
				$pointSub 			= substr($pointSub,0,strlen($pointSub)-1);
				$parameterSub		= array();
				$detail_submenu	= $this->M_auth->SelectMenuChildName($nilaiSub);
				foreach($detail_submenu->result() as $c):
					$parameterSub[]=str_replace(" ","",strtolower($c->menu_title));
				endforeach;
				$pointSub 	= explode(",",$pointSub);
			endif;
			
			$uniqueId = uniqid(rand(), TRUE);

			foreach ($checking as $apps) {
				$session_data = array(
					'JTsession_id'			=> $this->secure->enc($uniqueId),
					'JTidx'   				=> $this->secure->enc($apps->idx),
					'JTuser_id'   			=> $apps->user_id,
					'JTlevel'   			=> $this->secure->enc($level),
					'JToffice_id'   		=> $this->secure->enc($apps->company_idx),
					'JToffice_name'   		=> $apps->company_name,
					'JToffice_code'   		=> $apps->company_code,
					'JTfullname' 			=> $apps->fullname,
					'JTusername' 			=> $apps->user_name,
					'JTphoto' 				=> $apps->photo,
					'JTemail'		 		=> $apps->email_id,
					'JTapp_access'			=> ($result!=0)?$result:null,
					'JTsub_access'			=> ($res_sub!=0)?$res_sub:null,
					'JThak_access'			=> $parameter,
					'JTpermissions'			=> $point,
					'JTsubpermissions' 		=> $pointSub,
					'JTstatusLoginApps' 	=> 'Login'
				);
				// $cekonline 		= $this->M_auth->onlineUname($username);
				$cekRightbar 	= $this->M_auth->cekRightbar($apps->idx);
				if($cekRightbar == false){
					$this->M_auth->createRightbar($apps->idx);
				}

				$this->session->set_userdata($session_data);
				if($level == 4){
					$msg['status'] 	= true;
					$msg['link'] 	= base_url('hub');
					$msg['msg']		= 'Login success,<br>Click oke to go menu';
					echo json_encode($msg);die;
					// redirect('hub');die;
				}else{
					$msg['status'] 	= true;
					$msg['link'] 	= base_url('home');
					$msg['msg']		= 'Login success,<br>Click oke to go menu';
					echo json_encode($msg);die;
					// redirect('home');die;
				}
			}
		}else{
			// jika passwordnya salah
			if($checking==11){
				$session_data = array(
					'JTsession_id',
					'JTidx',
					'JToffice_id',
					'JToffice_name',
					'JToffice_code',
					'JTuser_id',
					'JTlevel',
					'JTusername',
					'JTfullname',
					'JTphoto',
					'JTemail',
					'JTapp_access',
					'JTsub_access',
					'JThak_access',
					'JTpermissions',
					'JTsubpermissions',
					'JTstatusLoginApps'
				);
				$this->session->unset_userdata($session_data);
				$msg['status'] 	= false;
				$msg['msg']		= 'Alert! Wrong Password. Try again.';
				echo json_encode($msg);die;
			}
			//  akun tidak ditemukan
			else{
				$session_data = array(
					'JTsession_id',
					'JTidx',
					'JToffice_id',
					'JToffice_name',
					'JToffice_code',
					'JTuser_id',
					'JTlevel',
					'JTusername',
					'JTfullname',
					'JTphoto',
					'JTemail',
					'JTapp_access',
					'JTsub_access',
					'JThak_access',
					'JTpermissions',
					'JTsubpermissions',
					'JTstatusLoginApps'
				);
				$this->session->unset_userdata($session_data);
				$msg['status'] 	= false;
				$msg['msg']		= 'Alert! Account is not existed';
				echo json_encode($msg);die;
			}
		}
	}

	public function login()
	{
		$this->form_validation->set_rules('tUname', 'Username', 'required|min_length[4]|max_length[55]');
		$this->form_validation->set_rules('tPass', 'Password', 'required');

		if ($this->form_validation->run() == TRUE) {
			$username 	= trim($_POST['tUname']);
			$password 	= trim($_POST['tPass']);
			$data 	  	= $this->M_auth->login($this->encryptstring->encrypt_decrypt('encrypt',$username), $this->encryptstring->hash_password($password));
			//echo $data->pass_user;
			//exit();
			if ($data == false) {
				$this->session->set_flashdata('notif', '<span class="badge d-block badge-danger form-text text-left mb-1"><strong>Alert!</strong> The Username / Password Is incorrect</span>');
				redirect('auth');
			} else {
				if ($this->check_password($this->encryptstring->hash_password($password), $data->pass_user)) {
					$session = [
						'userdata' 					=> $data,
						'statusLoginMsAplikasi' 	=> "Loged in"
					];
					$this->session->set_userdata($session);
					redirect('home/dashboard');
				} else {
					$this->session->set_flashdata('notif', '<span class="badge d-block badge-danger form-text text-left mb-1"><strong>Alert!</strong> The Username / Password Is incorrect</span>');
					redirect('auth');
				}
				
			}
		} else {
			$this->session->set_flashdata('error_msg', validation_errors());
			redirect('auth');
		}
	}

	public function logout()
	{
		// $session_data = array(
		// 	'csrf_token',
		// 	'JTsession_id',
		// 	'JTidx',
		// 	'JToffice_id',
		// 	'JToffice_name',
		// 	'JToffice_code',
		// 	'JTuser_id',
		// 	'JTlevel',
		// 	'JTusername',
		// 	'JTfullname',
		// 	'JTphoto',
		// 	'JTemail',
		// 	'JTapp_access',
		// 	'JTsub_access',
		// 	'JThak_access',
		// 	'JTpermissions',
		// 	'JTsubpermissions',
		// 	'JTstatusLoginApps',
		// 	'JThub_id',
		// 	'JThub_name',
		// 	'JThub_address',
		// 	'JThub_city',
		// 	'JThub_telephone',
		// 	'JTHUBuser_id',
        //     'JTHUBuser_name',
        //     'JTHUBemail_id',
        //     'JTHUBaddress',
        //     'JTHUBmobile_phone',
        //     'JTHUBphoto',
		// 	'JTkeywordOrder',
		// 	'JTtypeOrder',
		// 	'JTkeywordPickup',
		// 	'JTtypePickup'
		// );
		// $this->session->unset_userdata($session_data);
		$this->session->sess_destroy();
		redirect('Auth');
	}

	public function Registrasi()
	{
		$this->load->view('auth/registration');
	}
	public function RegistrasiSimpan()
	{
		$msg 			= array('error' => false);
		$msg 			= array('status' => false);
		$tUname      	= $this->input->post('tUname', TRUE);
		$tFullname     	= $this->input->post('tFullname', TRUE);
		$tPass          = $this->input->post("tPass");
		$tPass2         = $this->input->post("tPass2");
		$tEmail         = $this->input->post('tEmail', TRUE);
		$tUser_id       = $this->input->post('tUser_id', TRUE);
		$this->form_validation->set_rules('tUname', 'Username', 'trim|required');
		$this->form_validation->set_rules('tFullname', 'Full Name', 'trim|required');
		$this->form_validation->set_rules('tEmail', 'Email', 'required|trim|valid_email', [
            'valid_email'   => 'Email Tidak Valid !'
        ]);
		$this->form_validation->set_rules('tUser_id', 'No HP', 'required|trim');
		$this->form_validation->set_rules('tPass', 'Password', 'required|trim|min_length[4]|matches[tPass2]', [
            'matches' => 'Password Tidak Sama!',
            'min_length' => 'Password Terlalu Pendek!'
		]);
		$this->form_validation->set_rules('tPass2', 'Confirm Password', 'required|trim|min_length[4]|matches[tPass]');

		if ($this->form_validation->run() == false) {
			$msg['error']   = true;
			$msg['message'] = validation_errors();
		} else {
			$field = array(
				'user_name'       	=> $tFullname,
				'email_id'      	=> $tEmail,
				'user_id'      	  	=> $tUname,
				'user_level_id'     => 1,
				'status'     		=> 1,
				'status_login'     	=> 0,
				'country'			=> 'Indonesia',
				'created_by'		=> 'register',
				'mobile_phone'		=> $tUser_id,
				'created_on'		=> date('Y-m-d H:i:s'),
				'password'   		=> password_hash($tPass, PASSWORD_DEFAULT)
			);
			$cek  = $this->M_auth->insertData($field, 'user_account');
			if($cek){
				$this->session->set_flashdata('notif', "notifsukses('Registration Successfully. Please Login ')");
				redirect('auth');
			}else{
				$this->session->set_flashdata('notif', "notiferror_a('Alert! Try comeback soon..')");
				redirect('auth/logout');
			}
		}
		// $out['status'] = 'Gagal';
		// $data 			= $this->input->post();
		// $result 			= $this->M_pelanggan->insert($data);

		// if ($result > 0)
		// 	$out['status'] = 'Berhasil';

		// echo json_encode($out);
	}

	public function forgotPassword()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {
            // $this->load->view('auth/login');
			$msg['status']	= true;
			$msg['text']	= validation_errors();
        } else {
            $email = $this->input->post('email');
            $user = $this->db->get_where('user_account', ['email_id' => $email, 'status' => 1])->row_array();
            if ($user) {
				$cek_token = $this->db->get_where('user_token', ['email_id' => $email])->num_rows();
				if($cek_token>0){
					$token = base64_encode(random_bytes(32));
					$user_token = [
						'email_id' 		=> $email,
						'token' 		=> $token,
						'created_on' 	=> date("Y-m-d H:i:s")
					];
	
					// $result = 1;
					$result = $this->M_auth->updateToken($email,$user_token);
					if($result == 1){
						$this->_sendEmail($token, 'forgot');
						$msg['success']	= true;
						$msg['text']	= 'Silahkan Cek Email Anda Untuk Reset Password!';
					}else{
						$msg['status']	= true;
						$msg['text']	= 'Check your network and try again later !';
					}
				}else{

					$token = base64_encode(random_bytes(32));
					$user_token = [
						'email_id' 		=> $email,
						'token' 		=> $token,
						'created_on' 	=> date("Y-m-d H:i:s")
					];
	
					// $result = 1;
					$result = $this->M_auth->insertToken($user_token);
					if($result == 1){
						$this->_sendEmail($token, 'forgot');
						$msg['success']	= true;
						$msg['text']	= 'Silahkan Cek Email Anda Untuk Reset Password!';
					}else{
						$msg['status']	= true;
						$msg['text']	= 'Check your network and try again later !';
					}
				}
            } else {
				$msg['status']	= true;
				$msg['text']	= 'Email Belum Terdaftar Atau Aktivasi!';
            }
        }

		echo json_encode($msg);
    }

	public function resetPassword()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');
        // $token = '9sf+rFXT6ijZ2Gi0ZHZPH/OEhAAQ7U9ZI/psMmCNPDc=';
        // var_dump($email);

        $user = $this->db->get_where('user_account', ['email_id' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            if ($user_token) {
                $this->session->set_userdata('reset_email', $email);
				$data['token']	= $this->secure->enc($token);
                $this->load->view('auth/resetpassword', $data);
            } else {
                $this->session->set_flashdata('notif', "notiferror_a('Reset Password Gagal! Kode Token Salah.')");
                redirect('auth');
            }
        } else {
			$this->session->set_flashdata('notif', "notiferror_a('Reset Password Gagal! Email Tidak Terdaftar.')");
			// $this->session->set_flashdata('notif', "notiferror_a('$user')");
            redirect('auth');
        }
    }

	public function changePassword()
    {
        if (!$this->session->userdata('reset_email')) {
            redirect('auth/logout');
        }

        $this->form_validation->set_rules('tPass1', 'Password', 'trim|required|min_length[3]|matches[tPass2]',['matches' => '<p class="text-danger">Password tidak sama!</p>']);
        $this->form_validation->set_rules('tPass2', 'Repeat Password', 'trim|required|min_length[3]|matches[tPass1]',['matches' => '']);
        if ($this->form_validation->run() == false) {
			$data['token'] 	= $this->input->post('tokenforgot');
			$validate		= validation_errors();
			$this->session->set_flashdata('message', "$validate");
            $this->load->view('auth/resetpassword', $data);
        } else {
            $password = password_hash($this->input->post('tPass1'), PASSWORD_DEFAULT);
            $token = $this->secure->dec(($this->input->post('tokenforgot')));
            $email = $this->session->userdata('reset_email');

			$data = [
				'password' => $password
			];
			$result = $this->M_auth->updatePassword($email,$token,$data);
			if($result == 1){
				$this->session->unset_userdata('reset_email');
	
				$this->session->set_flashdata('notif', "notifsukses('Reset Password Berhasil Di Ubah! Silahkan Login.')");
				redirect('auth');
			}else{
				$this->session->set_flashdata('notif', "notiferror_a('Cek koneksi internet dan coba lagi.')");
				redirect('auth');
			}
            // $this->db->set('password', );
            // $this->db->where('email', $email);
            // $this->db->update('user');

        }
    }

	public function _sendEmail($token, $type)
    {
        $config = [
            // 'protocol'  => 'smtp',
            // 'smtp_host' => 'ssl://smtp.gmail.com',
            // 'smtp_user' => 'jatiberkah36@gmail.com',
            // 'smtp_pass' => 'JTEjakarta2021',
            // 'smtp_port' => 465,
            // 'mailtype'  => 'html',
            // 'charset'   => 'utf-8'

            // 'mailtype'  => 'html',
            // 'charset'   => 'utf-8',
            // 'protocol'  => 'smtp',
            // 'smtp_host' => 'smtp.gmail.com',
            // 'smtp_user' => 'jati.ekpresjkt@gmail.com',  // Email gmail
            // 'smtp_pass'   => 'JTEjakarta2019',  // Password gmail
            // 'smtp_crypto' => 'ssl',
            // 'smtp_port'   => 465,
            // 'crlf'    => "\r\n",
            // 'newline' => "\r\n"

            'protocol'      => 'smtp',
            'smtp_host'     => 'ssl://mail.mokhammad.com',
            'smtp_user'     => 'sender@mokhammad.com',
            'smtp_pass'     => 'VP8oULim^SSp',
            'smtp_port'     => 465,//587,
            'mailtype'      => 'html',
            'charset'       => 'utf-8',
            'newline'       => "\r\n"
        ];

        // $this->load->library('email', $config);
        $this->email->initialize($config);

        $this->email->set_newline("\r\n");

        $this->email->from('sender@mokhammad.com', 'PT JTE');

        if ($type == 'verify') {
            $this->email->subject('Akun Verifikasi');
            $this->email->message('Klik Link Ini Untuk Verifikasi : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Klik Disini</a>');
            $this->email->to($this->input->post('email'));
        } else if ($type == 'forgot') {
            $this->email->subject('Reset Password');
            $this->email->message('Klik Link Ini Untuk Reset Password : <a href="' . base_url() . 'auth/resetpassword?email=' . $this->input->post('email') . '&token=' . urlencode($token) . '">Klik Disini</a>');
            $this->email->to($this->input->post('email'));
        }

        if ($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

	public function verify()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user_account', ['email_id' => $email])->row_array();

        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
            if ($user_token) {
                if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $email);
                    $this->db->update('user');

                    $this->db->delete('user_token', ['email' => $email]);
                    $this->session->set_flashdata('notif', "notifsuccess('Akun Aktivasi $email Sukses! Silahkan Login.')");
                    redirect('auth');
                } else {

                    $this->db->delete('user', ['email' => $email]);
                    $this->db->delete('user_token', ['email' => $email]);

                    $this->session->set_flashdata('message2', '<div class="alert alert-danger text-center mt-3" role="alert">Akun Aktivasi Gagal! Token Expired.</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message2', '<div class="alert alert-danger text-center mt-3" role="alert">Akun Aktivasi Gagal! Token Salah Atau Expired.</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message2', '<div class="alert alert-danger text-center mt-3" role="alert">Akun Aktivasi Gagal! Email Salah.</div>');
            redirect('auth');
        }
    }

	public function check_password($password, $stored_hash) {
		$this->load->library('Passwordhash', array('iteration_count_log2' => 8, 'portable_hashes' => FALSE));
		
		// check password
		return $this->passwordhash->CheckPassword($password, $stored_hash);
	}

	public function changeCompany()
    {
		$this->form_validation->set_rules('get_company', 'Company', 'trim|required',
        ['required' => 'Company wajib di pilih']);
		if($this->form_validation->run() == false){
            $msg['error']   = true;
            $msg['message'] = validation_errors();
            echo json_encode($msg);die;
        }
		$company 	= $this->secure->dec($this->input->post('get_company'));
		// var_dump('<pre>');var_dump($company);die;
        if(empty($company))
        {
			$msg['status'] 	= true;
			$msg['text'] 	= 'No Company set.';
			echo json_encode($msg);die;
		}
		$cek = validate_csrf_token();
		if($cek == false){
			$msg['status'] 	= true;
			$msg['text'] 	= 'Session invalid.';
			echo json_encode($msg);die;
		}
		$checking 		= $this->check_user($this->username);
		if ($checking == true) {
			$result 	= $this->M_auth->getAccess($this->level,$company);
			$res_sub	= $this->M_auth->getAccessSub($this->level,$company);
			$nilai		= null;
			$point		= null;
			$nilaiSub	= null;
			$pointSub	= null;
			$param		= $this->M_auth->selectAccessDetail($this->level,$company);
			$paramSub	= $this->M_auth->selectChildAccessDetail($this->level,$company);

			// var_dump('<pre>');
			// var_dump($result);

			foreach($param->result() as $row):
				if(!empty($row->access_submenu_id)):
					$nilai	.= $row->access_submenu_id.",";
					$point	.= $row->access_permissions_id.",";
				endif;
			endforeach;
	
			if(!empty($nilai) && !empty($point)):
				$nilai 	= substr($nilai,0,strlen($nilai)-1);
				$point 	= substr($point,0,strlen($point)-1);
				$parameter=array();
				$detail_menu=$this->M_auth->SelectMenuChildName($nilai);
				foreach($detail_menu->result() as $row):
					$parameter[]=str_replace(" ","",strtolower($row->menu_title));
				endforeach;
				$point 	= explode(",",$point);
			endif;

			foreach($paramSub->result() as $c):
				if(!empty($c->access_submenu_id)):
					$nilaiSub	.= $c->access_submenu_id.",";
					$pointSub	.= $c->access_permissions_id.",";
				endif;
			endforeach;

			if(!empty($nilaiSub) && !empty($pointSub)):
				$nilaiSub 			= substr($nilaiSub,0,strlen($nilaiSub)-1);
				$pointSub 			= substr($pointSub,0,strlen($pointSub)-1);
				$parameterSub		= array();
				$detail_submenu	= $this->M_auth->SelectMenuChildName($nilaiSub);
				foreach($detail_submenu->result() as $c):
					$parameterSub[]=str_replace(" ","",strtolower($c->menu_title));
				endforeach;
				$pointSub 	= explode(",",$pointSub);
			endif;
			
			$uniqueId = uniqid(rand(), TRUE);

			$session_data = array(
				'JToffice_id'   		=> $this->secure->enc($company),
				'JTapp_access'			=> ($result!=0)?$result:null,
				'JTsub_access'			=> ($res_sub!=0)?$res_sub:null,
				'JThak_access'			=> !empty($parameter)?$parameter:null,
				'JTpermissions'			=> !empty($point)?$point:null,
				'JTsubpermissions' 		=> !empty($pointSub)?$pointSub:null,
			);
			$this->session->set_userdata($session_data);
			$msg['success'] = true;
			$msg['type']	= 'change';
			$msg['text'] 	= 'Company was changed.';
			echo json_encode($msg);die;
		}else{
			$msg['status']	= true;
			$msg['text'] 	= 'User account not valid.';
			echo json_encode($msg);die;
		}
    }

	public function changeCounter()
    {
		$counter 	= $this->secure->dec($this->input->post('get_counter'));
        if(empty($counter))
        {
			$msg['status'] 	= true;
			$msg['text'] 	= 'No Counter set.';
			echo json_encode($msg);die;
		}
		$cek = validate_csrf_token();
		if($cek == false){
			$msg['status'] 	= true;
			$msg['text'] 	= 'Session invalid.';
			echo json_encode($msg);die;
		}
		$checking 		= $this->check_user($this->username);
		if ($checking == true) {

			$session_data = array(
				'JTcounter_id'   		=> $this->secure->enc($counter)
			);
			$this->session->set_userdata($session_data);
			$msg['success'] = true;
			$msg['type']	= 'change';
			$msg['text'] 	= 'Counter Was Changed.';
			echo json_encode($msg);die;
		}else{
			$msg['status']	= true;
			$msg['text'] 	= 'User account not valid.';
			echo json_encode($msg);die;
		}
    }

	private function check_user($username)
	{
		$query = $this->db->get_where('user_account',['user_id' => $username]);
		if ($query->num_rows() == 1) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function jos($date, $empId)
    {
        $attendance_no = str_pad(time(),13,"0",STR_PAD_LEFT);
        $getEmp = $this->M_auth->get_employee($empId);
        if(empty($getEmp)){
			$msg['status'] 	= false;
			$msg['msg'] 	= 'gada karyawan woy';
			echo json_encode($msg);die;
        }
        $data = [
            'attendance_no' => $attendance_no,
            'attendance_date' => $date,
            'employee_id' => $empId,
            'shift_idx' => (int)$getEmp['office_shift'],
            'target_in' => $date.' 09:00:00',
            'check_in' => $date.' 09:00:00',
            'target_out' => $date.' 17:00:00',
            'check_out' => $date.' 17:00:00',
            'point_center_check_in' => '-6.2385505,106.888545',
            'point_center_check_out' => '-6.2385505,106.888545',
            'url_photo_check_in' => 'https://firebasestorage.googleapis.com/v0/b/first-discovery-401904.appspot.com/o/ekomando_hrd%2Fphoto_attendance%2FCHECK_IN_1718941664963.svg?alt=media&token=886479fb-00f9-4d83-8683-415b7b84b787',
            'filename_photo_check_in' => 'CHECK_IN_1718941664963.svg',
            'url_photo_check_in' => 'https://firebasestorage.googleapis.com/v0/b/first-discovery-401904.appspot.com/o/ekomando_hrd%2Fphoto_attendance%2FCHECK_OUT_1718356687327.svg?alt=media&token=04ba650d-0034-4523-bb75-8704decabbdc',
            'filename_photo_check_out' => 'CHECK_OUT_1718356687327.svg',
            'company_idx' => 1,
            'status' => 1,
            'created_by' => 1,
            'created_on' => date('Y-m-d H:i:s'),
        ];

        $cek = $this->M_auth->insertAtt($data);
        if($cek==1){
			$msg['status'] 	= true;
			$msg['msg'] 	= 'okelah '.$attendance_no;
			echo json_encode($msg);die;
        }else{
            $msg['status'] 	= false;
			$msg['msg'] 	= 'gagal bro';
			echo json_encode($msg);die;
        }
    }

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */
