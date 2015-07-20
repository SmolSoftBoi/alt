<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->auth_model->verify_session();
		$this->load->model('status_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		if ($this->form_validation->run('post') !== FALSE)
		{
			$status_id = $this->status_model->create_status(array(
				'user_id' => $this->session->user['user_id'],
				'status'  => $this->input->post('status', TRUE)
			));
		}

		redirect($this->status_model->get_url());
	}
}