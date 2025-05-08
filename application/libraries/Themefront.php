<?php
	class Themefront {
		protected $_ci;

		function __construct() {
			$this->_ci = &get_instance(); //Untuk Memanggil function load, dll dari CI. ex: $this->load, $this->model, dll
		}

		function views($template = NULL, $data = NULL) {
			if ($template != NULL) {
				// head
				$data['_meta'] 		      = $this->_ci->load->view('_layoutfront/_meta', $data, TRUE);
				$data['_css'] 				  = $this->_ci->load->view('_layoutfront/_css', $data, TRUE);
				$data['_jstop'] 			  = $this->_ci->load->view('_layoutfront/_jstop', $data, TRUE);
				
				// Header
				//$data['_nav'] 				= $this->_ci->load->view('_layoutfront/_nav', $data, TRUE);
				$data['_headermenu'] 		= $this->_ci->load->view('_layoutfront/_headermenu', $data, TRUE);
				$data['_headertop'] 		= $this->_ci->load->view('_layoutfront/_headertop', $data, TRUE);
				//$data['_header'] 			= $this->_ci->load->view('_layoutfront/_header', $data, TRUE);
				
				//Sidebar
				$data['_sidebar'] 			= $this->_ci->load->view('_layoutfront/_sidebar', $data, TRUE);
				
				//Content
				$data['_headerContent']	= $this->_ci->load->view('_layoutfront/_headerContent', $data, TRUE);
				$data['_mainContent'] 	= $this->_ci->load->view($template, $data, TRUE);
				$data['_content'] 			= $this->_ci->load->view('_layoutfront/_content', $data, TRUE);
				
				//Footer
				$data['_footer'] 			  = $this->_ci->load->view('_layoutfront/_footer', $data, TRUE);
				
				//JS
				$data['_script'] 				= $this->_ci->load->view('_layoutfront/_script', $data, TRUE);
				$data['_js'] 						= $this->_ci->load->view('_layoutfront/_js', $data, TRUE);

				echo $data['_template'] = $this->_ci->load->view('_layoutfront/_template', $data, TRUE);
			}
		}
	}
?>