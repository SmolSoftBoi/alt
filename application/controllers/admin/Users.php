<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->auth_model->verify_session('admin');

		$data['nav'] = 'users';
		$this->load->vars($data);
	}

	public function index()
	{
		$data['ng']['controller'] = 'adminUsers';
		$this->load->vars($data);

		$this->load->view('web/admin/templates/header');
		$this->load->view('web/admin/users/users');
		$this->load->view('web/admin/users/users_modals');
		$this->load->view('web/admin/templates/footer');
	}
}
