<?php

defined('BASEPATH') or exit('No direct script access allowed');

class PushNotification
{
    public function __construct()
    {	       		
		// load all the registration ids
		$this->CI =& get_instance();
    }
	
	public function push($msg, $registrationIds) {
		// API access key from Google API's Console
		$API_ACCESS_KEY = "AAAAi9jBNOo:APA91bE60QZalZaxGlUjzMEXG6YCMQAeLhreyEbufXl4XW_GR7-oCfkR6dJgOnIJ1vs6ENiYVrdgE7VKFh67enlcN8mLWolcxmsm4J83nmtSuf-GcHSYu7InU74rmsviSCUvl5mkn-kO";
		$serverurl = "https://fcm.googleapis.com/fcm/send";

		$fields = array (
			'registration_ids' 	=> $registrationIds,
			'data'			=> $msg
		);
		 
		$headers = array (
			'Authorization: key=' . $API_ACCESS_KEY,
			'Content-Type: application/json'
		);
		 
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, $serverurl );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
		return $result;		
	}

    public function newSale($sale_id, $text = null)
    {
        if (!$text) {
            $text = lang('new_sale');
        }
        $sale       = $this->CI->site->getSaleByID($sale_id);
        $customer   = $this->CI->site->getCompanyByID($sale->customer_id);
		$warehouse = $this->CI->site->getWarehouseByID($sale->warehouse_id);
        $subscribers    = $this->CI->site->getPushNotificationSubscribers($sale->warehouse_id);
				
		$msg = array
		(
			'message' 	=> $sale->biller, //lang('payment_received'),
			'title'		=> $text,
			'subtitle'	=> $warehouse->name,
			'tickerText'	=> $this->CI->sma->formatMoney($sale->grand_total),
			'vibrate'	=> 1,
			'sound'		=> 1,
			'largeIcon'	=> 'large_icon',
			'smallIcon'	=> 'small_icon'
		);
		
		return $this->push($msg, $subscribers); 
    }


}
