<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signout extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
	}

	public function index()
	{
		$this->auth_model->sign_out();

		redirect($this->auth_model->get_url());
	}
}