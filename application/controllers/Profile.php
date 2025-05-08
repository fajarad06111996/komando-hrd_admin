<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends AUTH_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_home');
		$this->load->library('Secure');
		$this->load->library('security_function');
        $this->link		= site_url().strtolower(get_class($this));
        $this->filename	= strtolower(get_class($this));
        $this->office 	= $this->secure->dec($this->session->userdata('JToffice_id'));
        $this->hub    	= $this->secure->dec($this->session->userdata('JThub_id'));
        $this->idx    	= $this->secure->dec($this->session->userdata('JTidx'));
		$this->level  	= $this->secure->dec($this->session->userdata('JTlevel'));
		$this->userdata = $this->session->userdata('JTuserdata');
	}
	public function index()
	{
		$this->session->set_userdata($ses);
		$data['userdata'] 	= $this->userdata;
		$data['page'] 		= "HOME";
		$data['judul'] 		= "MY PROFILE";
		$data['link'] 		= $this->link;
		$data['profile']	= $this->M_home->myprofile($this->idx);
		$this->template->views('pages/v_myprofile', $data);
	}

	public function cekRightbar($user)
	{
		$userx 	= $this->secure->dec($user);
		if($this->input->post('name')){
			$name = $this->input->post('name', true);
			$value	= $this->input->post('value', true);
			$data = array(
				$name => $value
			);
			$result = $this->M_home->updateRightbar($userx, $data);
		}else{
			$result = $this->M_home->cekRightbar($userx);
		}
		echo json_encode($result);
	}

	public function cekRightbarx($user)
	{
		$userx 	= $this->secure->dec($user);
		if($this->input->post('name')){
			$name = $this->input->post('name', true);
			$value	= $this->input->post('value', true);
			$data = array(
				$name => $value
			);
			$result = $this->M_home->updateRightbar($userx, $data);
		}else{
			$result = $this->M_home->cekRightbar($userx);
		}
		echo $result;
	}

	public function changePassword()
	{
		cek_csrf();
		$pass1 = $this->input->post('tPass1', TRUE);
		$pass2 = $this->input->post('tPass2', TRUE);
		if($pass1 != $pass2)
		{
			$msg['status'] 	= true;
			$msg['text'] 	= "password does'nt match.";
			echo json_encode($msg);
			die;
		}
		$data = [
			'password' 		=> password_hash($pass1, PASSWORD_DEFAULT),
			'modified_on' 	=> date('Y-m-d H:i:s'),
			'modified_by'	=> $this->idx
		];
		// var_dump('<pre>');
		// var_dump($data);
		// die();
		$cek = $this->M_home->changePassword($data, $this->idx);
		if($cek == 0)
		{
			$msg['status'] 	= true;
			$msg['text'] 	= "Network failure,<br> Please try again later.";
			echo json_encode($msg);
			die;
		}else{
			$msg['success'] = true;
			$msg['text'] 	= "Change password successfully.";
			echo json_encode($msg);
			die;
		}
	}
}

/* End of file Profile.php */
/* Location: ./application/controllers/Profile.php */
