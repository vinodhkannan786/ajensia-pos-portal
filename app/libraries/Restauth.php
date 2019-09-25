<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Restauth extends \CI_Controller
{	

    public function __construct()
    {
        parent::__construct();

        $this->load->library('ion_auth');
    }
	
    public function login($username, $password) {
	//echo $username, $password;
	if ($this->ion_auth->login($username, $password)) {
	      return true;
	}
		
	return false; 
   }
}
