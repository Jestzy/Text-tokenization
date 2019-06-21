<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('segment_model');    
    }
	public function index()
	{
        $data = array();
        if($this->input->post('submit') != NULL ){
            // POST data
            $postData   = $this->input->post();
            $content    = $postData['txt_sentence'];
            $result     = $this->segment_model->get_segment($content);
            $data['response'] = $result;
        }
//      vdebug($data);
		$this->load->view('tokenize_view',$data);
	}
}
