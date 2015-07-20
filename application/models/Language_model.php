<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function read_languages()
	{
		$language_items = $this->db->get('languages');

		if ($language_items->num_rows() === 0) return NULL;

		return $language_items->result_array();
	}
}