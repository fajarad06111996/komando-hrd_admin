<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AUTH_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_access', 'mAccess');
		$this->load->model('M_menu', 'mMenu');
		// $this->load->model('M_office', 'mOffice');
		$this->load->model('ModelGenId');
		$this->load->library('secure');
		// $this->load->library('Secure');

		$this->link		= site_url().strtolower(get_class($this)); // Menyusun URL berdasarkan class saat ini dan menyimpannya dalam variabel $link
		$this->userdata = $this->session->userdata();
		$this->session->set_flashdata('segment', explode('/', $this->uri->uri_string()));
		if ($this->session->userdata('JTstatusLoginApps') == '') {
			redirect('Auth/logout');
		}
	}

	public function getModulSidebar()
	{
		$data['modparent']	= $this->mMenu->getModParent()->result();
		$data['result']     = $this->mMenu->getSubChild()->result();
	}
}

/* End of file MY_Auth.php */
/* Location: ./application/core/MY_Auth.php */