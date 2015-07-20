<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vote extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->auth_model->verify_session();
		$this->load->model('status_model');
	}

	public function index($status_id)
	{
		$this->vote($status_id, 0);
	}

	public function up($status_id)
	{
		$this->vote($status_id, 1);
	}

	public function down($status_id)
	{
		$this->vote($status_id, -1);
	}

	private function vote($status_id, $vote)
	{
		$status = $this->status_model->update_status($status_id, array(
			'vote' => array(
				'user_id' => $this->session->user['user_id'],
				'vote'    => $vote
			)
		));

		redirect($this->status_model->get_url());
	}
}