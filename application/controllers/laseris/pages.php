<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends Frontend {

	public function __construct() {
		parent::__construct();
		$this->load->model('content_model');
		$this->load->model('counters_model');
		$this->load->model('menu_model');
        $this->load->helper('url');
        $this->config->load('templates');
	}

	public function index($page = '') {
		
        $page = $this->uri->uri_string();
		$data['page'] = $this->content_model->getToAlias($page,true);
        $data['template_list'] = $this->config->item('templates');
        
		
		if($data['page']) {
			//$this->load->view('startbootstrap/common/header', $data);
			//$this->load->view('startbootstrap/common/nav', $data);
			
			if (!empty($data['template_list'][$data['page']['template']]['modules']))
            {
                foreach ($data['template_list'][$data['page']['template']]['modules'] as $name => $value)
                {
                   $this->load->model($value['model']);
                   $data['modules'][$name] = $this->$value['model']->$value['method']['name']($value['method']['params']);
                }
            }

            if (!empty($data['template_list'][$data['page']['template']]['fields']))
            {
                $content = unserialize($data['page']['content']);
                foreach ($content as $i => $item)
                {
                    $this->load->library('SmartCodes');
                    $item = $this->smartcodes->parseString($item);
                    $data['page'][$i] = $item;
                }
            }

            if($counters = $this->counters_model->getCounters($this->input->ip_address(), $_SERVER['HTTP_HOST'])) $data['counters'] = $counters; else $data['counters'] = '';
            
            $this->load->view('laseris/pages/'.$data['page']['template'], $data);

			//$this->load->view('startbootstrap/common/footer', $data);
		}
		else {
			show_404();
		}
	}
}

/* End of file page.php */
/* Location: ./application/controllers/page.php */