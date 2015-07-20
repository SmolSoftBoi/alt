<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

	private $index = FALSE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('status_model');

		set_time_limit(0);
	}

	public function index()
	{
		$this->index = TRUE;

		$this->hourly();

		show_404();
	}

	public function hourly()
	{
		$config = array(
			'auto' => TRUE
		);

		$this->status_model->count_statuses($config);
		$this->status_model->count_votes($config);

		if ( ! $this->index) show_404();
	}
}