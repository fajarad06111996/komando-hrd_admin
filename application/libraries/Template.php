<?php
require 'vendor/autoload.php';
use Nullix\CryptoJsAes\CryptoJsAes;

class Template
{
	protected $_ci;
	var $template_data = array();
	function __construct()
	{
		$this->_ci = &get_instance(); //Untuk Memanggil function load, dll dari CI. ex: $this->load, $this->model, dll
		$this->_ci->load->model('M_menu', 'mMenu');
		$this->_ci->load->model('M_access', 'mAccess');
		$this->_ci->load->library('session');
		$this->_ci->load->library('secure');
		$this->idx    	= $this->_ci->secure->enc($this->_ci->session->userdata('JTidx'));
		$this->office   = $this->_ci->secure->enc($this->_ci->session->userdata('JToffice_id'));
		$this->level    = $this->_ci->secure->enc($this->_ci->session->userdata('JTlevel'));
		$this->enkey  	= $this->_ci->config->item('encryption_key');
	}
	function set($nama, $value)
	{
		$this->template_data[$nama] = $value;
	}
	function views($template = NULL, $data = NULL)
	{
		// $level	= $this->_ci->secure->enc($this->_ci->session->userdata('JTlevel'));
		if ($template != NULL) {
			if (count($this->_ci->uri->segment_array()) == 1) {
				$uri1 				= strtoupper(trim($this->_ci->uri->segment(1)));
				$data['uri1']		= strtoupper(trim($this->_ci->uri->segment(1)));
				$data['uri2']		= "";
				$data['uri3']		= "";
				$data['get2']		= $this->_ci->mMenu->getMenu($uri1)->row_array();
				$data['cTitle']		= "";
				$data['pChild']		= "";
				$data['cont']		= count($this->_ci->uri->segment_array());
				$origin_office 		= $this->_ci->mAccess->getTable('app_oaccess', ['access_level_id' => $this->level])->row_array();
				if($origin_office == null){
					$user_office = "";
				}else{
					$user_office = explode(",",$origin_office['access_office_idx']);
				}
			} elseif (count($this->_ci->uri->segment_array()) == 2) {
				$data['uri1']		= $this->_ci->uri->segment(1);
				$data['uri2']		= $this->_ci->uri->segment(2);
				$uri3				= "";
				$data['get']		= $this->_ci->mMenu->getIdSubModul($this->_ci->uri->segment(2))->row_array();
				$param				= $this->_ci->mMenu->getIdSubModul($this->_ci->uri->segment(2))->row_array();
				$mTitle				= !empty($param['menu_title']) ? $param['menu_title'] : 'nodataTitle';
				$data['cTitle']		= strtolower(str_replace(" ", "", $mTitle));
				$data['pChild'] 	= !empty($param['pChild']) ? $param['pChild'] : '';
				$data['cont']		= count($this->_ci->uri->segment_array());
				$origin_office = $this->_ci->mAccess->getTable('app_oaccess', ['access_level_id' => $this->level])->row_array();
				if($origin_office == null){
					$user_office = "";
				}else{
					$user_office = explode(",",$origin_office['access_office_idx']);
				}
			} else {
				$data['uri1']		= $this->_ci->uri->segment(1);
				$data['uri2']		= $this->_ci->uri->segment(2);
				$data['uri3']		= $this->_ci->uri->segment(3);
				$data['get']		= $this->_ci->mMenu->getIdSubModul($this->_ci->uri->segment(2))->row_array();
				$param				= $this->_ci->mMenu->getIdSubModul($this->_ci->uri->segment(2))->row_array();
				$mTitle				= !empty($param['menu_title']) ? $param['menu_title'] : 'nodataTitle';
				$data['cTitle']		= strtolower(str_replace(" ", "", $mTitle));
				$data['pChild'] 	= !empty($param['pChild']) ? $param['pChild'] : '';
				$data['cont']		= count($this->_ci->uri->segment_array());
				$origin_office = $this->_ci->mAccess->getTable('app_oaccess', ['access_level_id' => $this->level])->row_array();
				if($origin_office == null){
					$user_office = "";
				}else{
					$user_office = explode(",",$origin_office['access_office_idx']);
				}
			}
			$sideBarParams 				= '{"link": "'.base_url().'", "office_id": "'.$this->_ci->session->userdata('JToffice_id').'", "counter_id": "'.$this->_ci->session->userdata('JTcounter_id').'", "office_access": "'.($this->_ci->security_function->officeAccess()==true?1:0).'", "counter_access": "'.($this->_ci->security_function->counterAccess()==true?1:0).'"}';
			$encrypted          		= CryptoJsAes::encrypt($sideBarParams, $this->enkey);

        	$data['sideBarParams']    	= $encrypted;
			$data['companyData']		= $this->_ci->mAccess->getCompany();
			$data['companyList']		= $this->_ci->mAccess->getCompanyList();
			$data['rBar']				= $this->_ci->mMenu->cekRightbar($this->idx);
			$data['_meta'] 				= $this->_ci->load->view('_layout/_meta', $data, TRUE);
			$data['_css'] 				= $this->_ci->load->view('_layout/_css', $data, TRUE);
			$data['_jstop'] 			= $this->_ci->load->view('_layout/_jstop', $data, TRUE);
			$data['_navbar'] 			= $this->_ci->load->view('_layout/_navbar', $data, TRUE);
			$data['_header'] 			= $this->_ci->load->view('_layout/_header', $data, TRUE);
			$data['modparent']			= $this->_ci->mMenu->getModParent()->result();
			$data['_rightbar'] 			= $this->_ci->load->view('_layout/_rightbar', $data, TRUE);
			$data['_sidebar'] 			= $this->_ci->load->view('_layout/_sidebar', $data, TRUE);
			$data['_headerContent']		= $this->_ci->load->view('_layout/_headerContent', $data, TRUE);
			$data['_mainContent'] 		= $this->_ci->load->view($template, $data, TRUE);
			$data['_modal'] 			= $this->_ci->load->view('_layout/_modal', $data, TRUE);
			$data['_content'] 			= $this->_ci->load->view('_layout/_content', $data, TRUE);
			$data['_footer'] 			= $this->_ci->load->view('_layout/_footer', $data, TRUE);
			$data['_js'] 				= $this->_ci->load->view('_layout/_js', $data, TRUE);
			return $data['_template'] 	= $this->_ci->load->view('_layout/_template', $data, FALSE);
		}
	}
	function loadview($template = NULL, $data = NULL, $view_data = array())
	{
		if ($template != NULL) {
			$data['_mainContent'] 		= $this->_ci->load->view($template, $data, TRUE);
			return $data['_template'] 	= $this->_ci->load->view('_layout/_theme', $data, FALSE);
		}
	}
	function load($template = '', $view = '', $view_data = array(), $return = FALSE, $data = NULL)
	{
		$this->set('_mainContent', $this->_ci->load->view($view, $view_data, TRUE));
		return $this->_ci->load->view($template, $this->template_data, $data, $return);
	}
}
