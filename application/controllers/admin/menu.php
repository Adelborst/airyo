<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('ion_auth');
	}

	public function index() {

		if(!$this->ion_auth->logged_in()) {
			redirect('admin', 'refresh');
		}

		$data['menu'] = array();
		$data['usermenu'] = array();
		
		$this->load->view('admin/header', $data);
		$this->load->view('admin/menu/list');
		$this->load->view('admin/footer', $data);
	}
	
	public function edit($id = '') {
		
		$data['menu'] = array();
		$data['usermenu'] = array();
		
		$this->load->view('admin/header', $data);
		$this->load->view('admin/menu/edit');
		$this->load->view('admin/footer', $data);
	}
	
}

/* End of file page.php */
/* Location: ./application/controllers/page.php */