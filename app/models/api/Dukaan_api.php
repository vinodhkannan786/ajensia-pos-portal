<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dukaan_api extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
	
	public function countSubscriptions($filters = [])
    {
        if ($filters['username']) {
            $this->db->where('username', $filters['username']);
        } 
		
        $this->db->from('dukaan_subscriber');
        return $this->db->count_all_results();
    }
	


	public function insert($data) {
		if($this->db->insert('dukaan_subscriber',$data)) {    
			$id = $this->db->insert_id();
			$q = $this->db->get_where('dukaan_subscriber', array('id' => $id));
			return $q->row();
		} else {
			return false;
		}		
	}

    public function getSubscriptions($filters = [])
    {
        if ($filters['username']) {
            $this->db->where('username', $filters['username']);
        } 

        return $this->db->get('dukaan_subscriber')->result();
    }	

}
