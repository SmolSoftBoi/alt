<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
	}

	public function index()
	{
		if ($this->auth_model->verify_session(NULL, FALSE) === TRUE) redirect('/');

		$data['title'] = 'Sign Me Up';
		$this->load->vars($data);

		if ($this->form_validation->run('auth_sign_up') === FALSE)
		{
			$this->load->view('web/templates/header');
			$this->load->view('web/auth/sign_up.php');
			$this->load->view('web/templates/footer');
		}
		else
		{
			$user_id = $this->auth_model->create_user(array(
				'username'  => $this->input->post('username', TRUE),
				'pass'      => $this->input->post('pass1', TRUE),
				'email'     => array(
					'email' => $this->input->post('email', TRUE)
				)
			));

			if ($user_id !== FALSE)
			{
				$authed = $this->auth_model->sign_in($this->input->post('username', TRUE), $this->input->post('pass', TRUE));

				if ($authed === TRUE) redirect($this->auth_model->get_url());
			}
		}
	}
}