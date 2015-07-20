<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Me extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->auth_model->verify_session();
		$this->load->model(array('country_model', 'language_model', 'status_model', 'user_model'));
		$this->load->library(array('form_validation', 'upload'));
		$this->load->helper(array('date', 'domain', 'file', 'form'));

		$data['nav'] = 'me';
		$this->load->vars($data);
	}

	public function index()
	{
		$this->auth_model->set_url();

		$data['title'] = 'Me';
		$data['statuses'] = $this->status_model->read_statuses(array(
			'where' => array(
				'user_id' => $this->session->user['user_id']
			),
			'limit' => 20
		));
		$this->load->vars($data);

		$this->load->view('web/templates/header');
		$this->load->view('web/me/me');
		$this->load->view('web/post_modal');
		$this->load->view('web/templates/footer');
	}

	public function settings()
	{
		$data['title'] = 'My Settings';
		$data['user'] = $this->user_model->read_user($this->session->user['user_id']);
		$data['languages'] = $this->language_model->read_languages();
		$data['countries'] = $this->country_model->read_countries();
		$this->load->vars($data);

		if ($this->form_validation->run('settings') !== FALSE)
		{
			$this->user_model->update_user($this->session->user['user_id'], array(
				'language_id' => $this->input->post('language_id', TRUE),
				'country_id'  => $this->input->post('country_id', TRUE),
				'username'    => $this->input->post('username', TRUE),
				'name'        => $this->input->post('name', TRUE),
				'bio'         => $this->input->post('bio', TRUE),
				'location'    => $this->input->post('location', TRUE),
				'site_url'    => $this->input->post('site_url', TRUE),
				'timezone'    => $this->input->post('timezone', TRUE),
				'color_hex'   => $this->input->post('color_hex', TRUE),
				'email'       => array(
					'email' => $this->input->post('email', TRUE)
				)
			));

			$config = array(
				'upload_path'   => './media/',
				'allowed_types' => 'gif|jpg|png',
			);

			$this->upload->initialize($config, FALSE);

			if ($this->upload->do_upload('media') !== FALSE)
			{
				$media = $this->upload->data();

				if ($media['is_image'] == TRUE)
				{
					$this->user_model->update_user($this->session->user['user_id'], array(
						'media_loc' => $media['file_name']
					));
				}
				else
				{
					delete_files($config['upload_path'] . $media['file_name']);
				}
			}
		}

		$this->load->view('web/templates/header');
		$this->load->view('web/me/settings');
		$this->load->view('web/templates/footer');
	}

	public function archive()
	{
		// Get User's Archive
	}
}