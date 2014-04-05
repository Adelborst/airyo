<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('pages_model');
		$this->load->model('menu_model');
	}

	public function index($page = '') {
		$data['page'] = $this->pages_model->get($page);
        $data['menu'] = $this->menu_model->getList(1,true);
		if($data['page']) {
			$this->load->view('header');
			$this->load->view('menu', $data);
			$this->load->view('pages', $data);
			$this->load->view('footer');
		} else {
			show_404();
		}
	}
}

/* End of file page.php */
/* Location: ./application/controllers/page.php */