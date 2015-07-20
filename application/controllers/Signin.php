<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->library(array('form_validation', 'session'));
		$this->load->helper('form');
	}

	public function index()
	{
		if ($this->session->authed === TRUE) redirect('/');

		$data['title'] = 'Sign In';
		$this->load->vars($data);

		if ($this->form_validation->run('auth_sign_in') !== FALSE)
		{
			if ($this->input->post('remember', TRUE) == TRUE)
			{
				$remember = TRUE;
			}
			else
			{
				$remember = FALSE;
			}

			$authed = $this->auth_model->sign_in($this->input->post('username', TRUE), $this->input->post('pass', TRUE), $remember);

			if ($authed === TRUE) redirect($this->auth_model->get_url());
		}

		$this->load->view('web/templates/header');
		$this->load->view('web/auth/sign_in.php');
		$this->load->view('web/templates/footer');
	}
}