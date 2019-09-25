<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Test extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->methods['index_get']['limit'] = 500;
        $this->load->api_model('sales_api');
    }

    public function index_get()
    {      
	$data = [ 'data' => 1 ];
        $this->response($data, REST_Controller::HTTP_OK);            
    }
}
