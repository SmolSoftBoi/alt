<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Country_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function read_countries()
	{
		$country_items = $this->db->get('countries');

		if ($country_items->num_rows() === 0) return NULL;

		return $country_items->result_array();
	}
}