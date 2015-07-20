<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->model('status_model');
		$this->load->helper('date');

		$this->auth_model->set_url();

		$data['nav'] = 'home';

		$config = array(
			'limit' => 20
		);

		if ($this->session->authed !== TRUE) $config['order_by'] = array(
			'score' => 'DESC'
		);

		$data['statuses'] = $this->status_model->read_statuses($config);

		$this->load->vars($data);

		$this->load->view('web/templates/header');
		$this->load->view('web/welcome_message');
		$this->load->view('web/post_modal');
		$this->load->view('web/templates/footer');
	}
}
