<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('status_model', 'user_model'));
	}

	public function index()
	{
		show_404();
	}

	public function status($resource = NULL)
	{
		$config = array(
			'json' => TRUE
		);

		if ( ! is_null($this->input->get('config', TRUE))) $config = array_merge($config, json_decode($this->input->get('config', TRUE), TRUE));

		$data = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);

		if (isset($data['config'])) $config = array_merge($data['config']);

		switch (strtolower($resource))
		{
			case 'readstatus':
				$output = $this->status_model->read_status($this->input->get('statusId', TRUE), $config);
				break;
			case 'readstatuses':
				$output = $this->status_model->read_statuses($config);
				break;
			case 'createstatus':
				$this->auth_model->verify_session();

				$data['statusItem']['userId'] = $this->session->user['user_id'];

				$output = $this->status_model->create_status($data['statusItem'], $config);

				break;
			case 'updatestatus':
				$this->auth_model->verify_session();

				if (isset($data['statusItem']['vote'])) $data['statusItem']['vote']['userId'] = $this->session->user['user_id'];

				$output = $this->status_model->update_status($data['statusId'], $data['statusItem'], $config);

				break;
			default:
				show_404();
				break;
		}

		$this->output->set_content_type('json')->set_output(json_encode(array(
			'authed' => $this->session->authed,
			'data' => $output
		)));
	}

	public function user($resource = NULL)
	{
		$config = array(
			'json' => TRUE
		);

		if ( ! is_null($this->input->get('config', TRUE))) $config = array_merge($config, json_decode($this->input->get('config', TRUE), TRUE));

		$data = json_decode($this->security->xss_clean($this->input->raw_input_stream), TRUE);

		if (isset($data['config'])) $config = array_merge($data['config']);

		switch (strtolower($resource))
		{
			case 'readuser':
				$output = $this->user_model->read_user($this->input->get('userId', TRUE), $config);
				break;
			case 'readusers':
				$output = $this->user_model->read_users($config);
				break;
			default:
				show_404();
				break;
		}

		$this->output->set_content_type('json')->set_output(json_encode($output));
	}

	public function validation($resource)
	{
		switch (strtolower($resource))
		{
			case 'allowed':
				switch (strtolower($this->input->get('field', TRUE)))
				{
					case 'username':
						$output = array(
							'valid' => $this->user_model->check_allowed_username($this->input->get('value', TRUE))
						);
						break;
				}
				break;
			case 'unique':
				switch (strtolower($this->input->get('field', TRUE)))
				{
					case 'username':
						$output = array(
							'valid' => $this->user_model->check_unique_username($this->input->get('value', TRUE), $this->input->get('exclude', TRUE))
						);
						break;
					case 'email':
						$output = array(
							'valid' => $this->user_model->check_unique_email($this->input->get('value', TRUE), $this->input->get('exclude', TRUE))
						);
						break;
				}
				break;
			default:
				show_404();
				break;
		}

		$this->output->set_content_type('json')->set_output(json_encode($output));
	}
}