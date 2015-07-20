<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

	private $version = 1;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
		$this->load->library('session');
		$this->load->helper(array('url', 'uuid'));

		if ($this->session->authed === TRUE)
		{
			$this->set_session_expiration($this->session->remember);

			if ($this->session->version < $this->version) $this->set_session_data($this->session->user['user_id'], $this->session->remember);
		}
	}

	public function sign_in($username, $pass, $remember = FALSE)
	{
		$authed = FALSE;

		$user_id = $this->user_model->get_user_id($username);

		if (is_null($user_id)) return $authed;

		$user = $this->user_model->read_user($user_id);

		if ($this->encrypt_pass($pass, $user['salt']) === $user['pass'])
		{
			$this->session->set_userdata(array(
				'authed' => TRUE
			));

			$this->set_session_data($user['user_id'], $remember);

			$authed = TRUE;
		}

		$this->session->set_flashdata(array(
			'errors' => array(
				'auth' => $authed
			)
		));

		return $authed;
	}

	public function sign_out()
	{
		$this->session->sess_destroy();
	}

	public function verify_session($roles = NULL, $redirect = TRUE)
	{
		$verify = FALSE;

		if ($this->session->authed === TRUE) $verify = TRUE;

		if ($this->session->authed === TRUE && ! is_null($roles))
		{
			$user = $this->user_model->read_user($this->session->user['user_id'], array(
				'expand' => 'roles'
			));

			if ( ! is_array($roles)) $roles = array($roles);

			$verify = FALSE;

			foreach ($roles as $role)
			{
				if (in_array($role, $user['roles']))
				{
					$verify = TRUE;
					continue;
				}
			}
		}

		if ($redirect === TRUE) if ($verify === FALSE) $this->redirect_sign_in();

		return $verify;
	}

	public function create_user($user_item)
	{
		return $this->user_model->create_user($user_item);
	}

	public function pass_gen($pass)
	{
		$salt = hash('sha1', uuidgen());
		$pass = hash('sha512', $pass . $salt);

		return array(
			'salt' => $salt,
			'pass' => $pass
		);
	}

	public function code_gen()
	{
		$i = TRUE;
		while ($i)
		{
			$code = substr(uuidgen(), 0, 8);

			if ($this->db->get_where('email_verification', array(
				'code' => $code
			), 1)->num_rows() === 0)
			{
				return $code;
			}
		}
	}

	public function set_url()
	{
		$this->input->set_cookie(array(
			'name'   => 'auth_url',
			'value'  => uri_string(),
			'expire' => $this->config->item('csrf_expire')
		));
	}

	public function get_url()
	{
		$url = $this->input->cookie($this->config->item('cookie_prefix') . 'auth_url', TRUE);

		if (is_null($url)) return '/';

		return $url;
	}

	private function redirect_sign_in()
	{
		$this->set_url();

		redirect('signin');
	}

	private function encrypt_pass($pass, $salt)
	{
		return hash('sha512', $pass . $salt);
	}

	private function set_session_data($user_id, $remember = FALSE)
	{
		$this->set_session_expiration($remember);

		$this->session->set_userdata(array(
			'version'  => $this->version,
			'remember' => $remember
		));

		$this->user_model->set_session_data($user_id);
	}

	private function set_session_expiration($remember)
	{
		if ($remember)
		{
			$this->config->set_item('sess_expiration', 60 * 60 * 24 * 7 * 52);
			$this->config->set_item('sess_time_to_update', (60 * 60 * 24 * 7 * 52) / 12);
		}
	}
}