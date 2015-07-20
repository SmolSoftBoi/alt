<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statuses extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->auth_model->verify_session('admin');

		$data['nav'] = 'statuses';
		$this->load->vars($data);
	}

	public function index()
	{
		$data['ng']['controller'] = 'adminStatuses';
		$this->load->vars($data);

		$this->load->view('web/admin/templates/header');
		$this->load->view('web/admin/statuses/statuses');
		$this->load->view('web/admin/statuses/statuses_modals');
		$this->load->view('web/admin/templates/footer');
	}
}
