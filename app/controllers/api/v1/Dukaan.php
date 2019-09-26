<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Dukaan extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->methods['index_get']['limit'] = 500;
        $this->load->api_model('dukaan_api');
        $this->load->model('site');
    }

	public function pushtest_get() {
		$this->load->library('pushNotification');
		$r = $this->pushnotification->newSale($this->get('saleid'));
		
		$this->response($r, REST_Controller::HTTP_OK);	
	}

	public function push_get() {
			// API access key from Google API's Console
			$API_ACCESS_KEY = 'AAAAi9jBNOo:APA91bE60QZalZaxGlUjzMEXG6YCMQAeLhreyEbufXl4XW_GR7-oCfkR6dJgOnIJ1vs6ENiYVrdgE7VKFh67enlcN8mLWolcxmsm4J83nmtSuf-GcHSYu7InU74rmsviSCUvl5mkn-kO';
			$registrationIds = array( $_GET['id'] );
			// prep the bundle
			$msg = array
			(
				'message' 	=> 'here is a message. message',
				'title'		=> 'This is a title. title',
				'subtitle'	=> 'This is a subtitle. subtitle',
				'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
				'vibrate'	=> 1,
				'sound'		=> 1,
				'largeIcon'	=> 'large_icon',
				'smallIcon'	=> 'small_icon'
			);
			$fields = array
			(
				'registration_ids' 	=> $registrationIds,
				'data'			=> $msg
			);
			 
			$headers = array
			(
				'Authorization: key=' . $API_ACCESS_KEY,
				'Content-Type: application/json'
			);
			 
			$ch = curl_init();
			curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); 
			curl_setopt( $ch,CURLOPT_POST, true );
			curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
			$result = curl_exec($ch );
			curl_close( $ch );
			echo $result;
	}


	public function subscribe_post() {
		 if ( !isset($_POST['username'])  || !isset($_POST['devicekey']) ) {
                	$this->set_response([
                    		'message' => 'Missing parameter',
                    		'status'  => false,
                	], REST_Controller::HTTP_BAD_REQUEST);
		} else {

			$d = array('username' => $this->input->post('username'),
       		    		'device_key' => $this->input->post('devicekey'));
        		$r = $this->dukaan_api->insert($d);

		
			$data = [
				'data'  => $d,
				'total' => 1,
			];		
			$this->response($data, REST_Controller::HTTP_OK);		
		}
	}

    public function subscribers_get() {
	
        $userid = $this->get('username');

		$filters = [
            'username'     => $userid,
            'start'    => $this->get('start') && is_numeric($this->get('start')) ? $this->get('start') : 1,
            'limit'    => $this->get('limit') && is_numeric($this->get('limit')) ? $this->get('limit') : 10,			
        ];
		
		$r = $this->dukaan_api->getSubscriptions($filters);
		
		$data = [
			'data'  => $r,
			'limit' => $filters['limit'],
			'start' => $filters['start'],
			'total' => $this->dukaan_api->countSubscriptions($filters),
		];
		
		$this->response($data, REST_Controller::HTTP_OK);		
    }
}
