<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->auth_model->verify_session('admin');

		$data['nav'] = 'dashboard';
		$this->load->vars($data);
	}

	public function index()
	{
		$data['ng']['controller'] = 'adminDashboard';
		$this->load->vars($data);

		$this->load->view('web/admin/templates/header');
		$this->load->view('web/admin/welcome_message');
		$this->load->view('web/admin/templates/footer');
	}
}
