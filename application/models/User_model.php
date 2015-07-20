<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	private $color_hex = '337ab7';

	private $color_hsl;

	private $usernames;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model(array('auth_model', 'media_model'));
		$this->load->library(array('email', 'session'));
		$this->load->helper(array('color', 'date', 'domain'));

		$this->color_hsl = color_hex_to_hsl($this->color_hex);
	}

	public function get_user_id($username)
	{
		$user_id = $this->db->select('user_id')->get_where('users', array(
			'username' => $username
		), 1);

		if ($user_id->num_rows() === 1) return $user_id->row()->user_id;

		$user_id = $this->db->select('user_id')->get_where('emails', array(
			'email' => $username
		), 1);

		if ($user_id->num_rows() === 1) return $user_id->row()->user_id;

		return NULL;
	}

	public function read_user($user_id, $config = array())
	{
		$config = array_merge(array(
			'expand' => NULL,
			'json'   => FALSE
		), $config);

		if ( ! is_null($config['expand'])) if ( ! is_array($config['expand'])) $config['expand'] = array($config['expand']);

		$user_item = $this->db->get_where('users', array(
			'user_id' => $user_id
		), 1);

		if ($user_item->num_rows() === 0) return NULL;

		$user_item = $user_item->row_array();

		$user_item['display_name'] = $user_item['name'];
		$user_item['media_url'] = NULL;

		if ( ! empty($user_item['name'])) $user_item['display_name'] = $user_item['name'];

		if ( ! is_null($user_item['media_loc'])) $user_item['media_url'] = site_url('media/' . $user_item['media_loc']);

		$user_item['email'] = $this->db->get_where('emails', array(
			'user_id' => $user_id
		), 1)->row_array();

		if ( ! is_null($config['expand']))
		{
			if (in_array('roles', $config['expand']))
			{
				$user_role_rels = $this->db->get_where('user_role_rels', array(
					'user_id' => $user_id
				));

				$user_item['roles_count'] = $user_role_rels->num_rows();
				$user_item['roles'] = array();

				foreach ($user_role_rels->result_array() as $user_role_rel_key => $user_role_rel)
				{
					$role = $this->db->get_where('roles', array(
						'role_id' => $user_role_rel['role_id']
					), 1)->row_array();

					$user_item['roles'][$user_role_rel_key] = $role['key'];
				}
			}
		}

		if ($config['json'] == TRUE) return $this->user_to_json($user_item);

		return $user_item;
	}

	public function read_users($config = array())
	{
		$default_config = array(
			'limit'     => NULL,
			'expand'    => NULL,
			'json'      => FALSE
		);

		if (isset($config['where'])) $config['where'] = array_merge($default_config['where'], $config['where']);
		if (isset($config['order_by'])) $config['order_by'] = array_merge($default_config['order_by'], $config['order_by']);

		$config = array_merge($default_config, $config);

		if ( ! is_null($config['expand'])) if ( ! is_array($config['expand'])) $config['expand'] = array($config['expand']);

		$users = $this->db->select('user_id')->get('users', $config['limit']);

		if ($users->num_rows() === 0) return NULL;

		foreach ($users->result_array() as $user_key => $user)
		{
			$user_items[$user_key] = $this->read_user($user['user_id'], $config);
		}

		return $user_items;
	}

	public function create_user($user_item, $config = array())
	{
		$timestamp = now();

		$config = array_merge(array(
			'auto' => FALSE
		), $config);

		$auth = $this->auth_model->pass_gen($user_item['pass']);

		$email = array(
			'email'       => $user_item['email'],
			'default'     => 1,
			'c_timestamp' => $timestamp
		);

		$language = $this->db->get_where('languages', array(
			'code' => 'ENG'
		), 1)->row_array();

		$country = $this->db->get_where('countries', array(
			'code' => 'GB'
		), 1)->row_array();

		$user = array(
			'language_id' => $language['language_id'],
			'country_id'  => $country['country_id'],
			'username'    => $user_item['username'],
			'timezone'    => 'UTC',
			'salt'        => $auth['salt'],
			'pass'        => $auth['pass'],
			'c_timestamp' => $timestamp
		);

		$color_hsl = color_hex_to_hsl(color_random_hex());

		$user['color_hex'] = color_hsl_to_hex($color_hsl['h'], $this->color_hsl['s'], $this->color_hsl['l']);

		if (isset($user_item['base_id'])) $user['base_id'] = $user_item['base_item'];
		if (isset($user_item['name'])) $user['name'] = $user_item['name'];
		if (isset($user_item['bio'])) $user['bio'] = $user_item['bio'];
		if (isset($user_item['location'])) $user['location'] = $user_item['location'];
		if (isset($user_item['site_url'])) $user['site_url'] = $user_item['site_url'];
		if (isset($user_item['media_loc'])) $user['media_loc'] = $user_item['media_loc'];
		if (isset($user_item['color_hex'])) $user['color_hex'] = $user_item['color_hex'];
		if (isset($user_item['status_count'])) $user['status_count'] = $user_item['status_count'];
		if (isset($user_item['following_count'])) $user['following_count'] = $user_item['following_count'];
		if (isset($user_item['followers_count'])) $user['followers_count'] = $user_item['followers_count'];
		if (isset($user_item['vote_count'])) $user['vote_count'] = $user_item['vote_count'];
		if (isset($user_item['base_timestamp'])) $user['basetimestamp'] = $user_item['base_timestamp'];
		if (isset($user_item['c_timestamp'])) $user['c_timestamp'] = $user_item['c_timestamp'];
		if (isset($user_item['u_timestamp'])) $user['u_timestamp'] = $user_item['u_timestamp'];

		$email_item = array(
			'email'       => $user_item['email']['email'],
			'c_timestamp' => $timestamp
		);

		if (isset($user_item['email']['default'])) $email_item['default'] = $user_item['email']['default'];
		if (isset($user_item['email']['verified'])) $email_item['verified'] = $user_item['email']['verified'];

		$this->db->trans_start();

		$this->db->insert('users', $user);

		$user_id = $this->db->insert_id();

		$email_item['user_id'] = $user_id;

		$email_verification = $this->create_email($email_item);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) return FALSE;

		if ($email_verification === TRUE) return $user_id;

		$this->email->to($email['email']);
		$this->email->from('blackhole@' . domain(base_url()), 'alt');
		$this->email->subject('You\'ve Signed Yourself Up!');

		$message  = $this->load->view('email/templates/header', array(), TRUE);
		$message .= $this->load->view('email/auth/verify', array(
			'name' => $user['username'],
			'code' => $email_verification['code']
		), TRUE);
		$message .= $this->load->view('email/templates/footer', array(), TRUE);

		return $user_id;
	}

	public function update_user($user_id, $user_item, $config = array())
	{
		$timestamp = now();

		$config = array_merge(array(
			'auto' => FALSE
		), $config);

		$current_user = $this->read_user($user_id);

		if (isset($user_item['base_id'])) if ($current_user['base_id'] != $user_item['base_id']) $user['base_id'] = $user_item['base_id'];
		if (isset($user_item['language_id']) && ! is_null($user_item['language_id'])) if ($current_user['language_id'] != $user_item['language_id']) $user['language_id'] = $user_item['language_id'];
		if (isset($user_item['country_id']) && ! is_null($user_item['country_id'])) if ($current_user['country_id'] != $user_item['country_id']) $user['country_id'] = $user_item['country_id'];
		if (isset($user_item['username']) && ! is_null($user_item['username'])) if ($current_user['username'] != $user_item['username']) $user['username'] = $user_item['username'];
		if (isset($user_item['name'])) if ($current_user['name'] != $user_item['name']) $user['name'] = $user_item['name'];
		if (isset($user_item['bio'])) if ($current_user['bio'] != $user_item['bio']) $user['bio'] = $user_item['bio'];
		if (isset($user_item['location'])) if ($current_user['location'] != $user_item['location']) $user['location'] = $user_item['location'];
		if (isset($user_item['site_url'])) if ($current_user['site_url'] != $user_item['site_url']) $user['site_url'] = $user_item['site_url'];
		if (isset($user_item['color_hex']) && ! is_null($user_item['color_hex'])) if ($current_user['color_hex'] != $user_item['color_hex']) $user['color_hex'] = $user_item['color_hex'];
		if (isset($user_item['timezone']) && ! is_null($user_item['timezone'])) if ($current_user['timezone'] != $user_item['timezone']) $user['timezone'] = $user_item['timezone'];
		if (isset($user_item['status_count']) && ! is_null($user_item['status_count'])) if ($current_user['status_count'] != $user_item['status_count']) $user['status_count'] = $user_item['status_count'];
		if (isset($user_item['following_count'])  && ! is_null($user_item['following_count'])) if ($current_user['following_count'] != $user_item['following_count']) $user['following_count'] = $user_item['following_count'];
		if (isset($user_item['followers_count']) && ! is_null($user_item['followers_count'])) if ($current_user['followers_count'] != $user_item['followers_count']) $user['followers_count'] = $user_item['followers_count'];
		if (isset($user_item['vote_count']) && ! is_null($user_item['vote_count'])) if ($current_user['vote_count'] != $user_item['vote_count']) $user['vote_count'] = $user_item['vote_count'];
		if (isset($user_item['verified']) && ! is_null($user_item['verified'])) if ($current_user['verified'] != $user_item['verified']) $user['verified'] = $user_item['verified'];
		if (isset($user_item['base_timestamp']) && ! is_null($user_item['base_timestamp'])) if ($current_user['base_timestamp'] != $user_item['c_timestamp']) $user['c_timestamp'] = $user_item['c_timestamp'];
		if (isset($user_item['base_timestamp']) && ! is_null($user_item['base_timestamp'])) if ($current_user['c_timestamp'] != $user_item['c_timestamp']) $user['c_timestamp'] = $user_item['c_timestamp'];
		if (isset($user_item['u_timestamp'])) if ($current_user['u_timestamp'] != $user_item['u_timestamp']) $user['u_timestamp'] = $user_item['u_timestamp'];

		if (isset($user_item['media_loc'])) if ($current_user['media_loc'] != $user_item['media_loc'])
		{
			$crop = $this->media_model->image_crop('./media/' . $user_item['media_loc'], 1280, 1280);

			if ($crop === TRUE) $user['media_loc'] = $user_item['media_loc'];
		}

		if (isset($user)) if ($config['auto'] === FALSE) if ( ! isset($user['u_timestamp'])) $user['u_timestamp'] = $timestamp;

		if (isset($user_item['email'])) if ($current_user['email']['email'] != $user_item['email']) $email['email'] = $user_item['email'];

		if (isset($email)) if ($config['auto'] === FALSE) if ( ! isset($email['u_timestamp'])) $email = array(
			'u_timetamp' => $timestamp
		);

		$this->db->trans_start();

		if (isset($user)) $this->db->update('users', $user, array(
			'user_id' => $user_id
		));

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) return FALSE;

		if ($this->session->authed === TRUE) $this->set_session_data($this->session->user['user_id'], $this->session->remember);

		return TRUE;
	}

	public function set_session_data($user_id)
	{
		$user = $this->read_user($user_id, array(
			'expand' => 'roles'
		));

		if ( ! is_null($user))
		{
			$data = array(
				'user' => array(
					'user_id'      => $user['user_id'],
					'username'     => $user['username'],
					'name'         => $user['name'],
					'display_name' => $user['display_name'],
					'media_url'    => $user['media_url'],
					'color_hex'    => $user['color_hex'],
					'c_timestamp'  => $user['c_timestamp'],
					'email'        => array(
						'email' => $user['email']['email']
					),
					'roles'        => $user['roles']
				)
			);

			$this->session->set_userdata($data);
		}
	}

	public function check_allowed_username($username)
	{
		$this->config->load('usernames', TRUE);

		$usernames_config = $this->config->item('usernames');

		$this->usernames = $usernames_config['usernames'];

		if (in_array(strtolower($username), $this->usernames)) return FALSE;

		return TRUE;
	}

	public function check_unique_username($username, $exclude = NULL)
	{
		$username = $this->db->get_where('users', array(
			'username' => $username
		), 1);

		if ($username->num_rows() === 0) return TRUE;

		if ( ! is_null($exclude)) if ($username->row()->username == $exclude) return TRUE;

		return FALSE;
	}

	public function check_unique_email($email, $exclude = NULL)
	{
		$email = $this->db->get_where('emails', array(
			'email' => $email
		), 1);

		if ($email->num_rows() === 0) return TRUE;

		if ( ! is_null($exclude)) if ($email->row()->email == $exclude) return TRUE;

		return FALSE;
	}

	private function create_email($email_item)
	{
		$timestamp = now();

		$email = array(
			'user_id'     => $email_item['user_id'],
			'email'       => $email_item['email'],
			'c_timestamp' => $timestamp
		);

		if (isset($email_item['default'])) $email['default'] = $email_item['default'];
		if (isset($email_item['verified'])) $email['verified'] = $email_item['verified'];

		$this->db->insert('emails', $email);

		$email_id = $this->db->insert_id();

		$verified = FALSE;

		if (isset($email['verified'])) if ($email['verified'] == TRUE) $verified = TRUE;

		if ($verified) return TRUE;

		$email_verification = array(
			'email_id'    => $email_id,
			'code'        => $this->auth_model->code_gen(),
			'c_timestamp' => $email['c_timestamp']
		);

		$this->db->insert('email_verification', $email_verification);

		return $email_verification['code'];
	}

	private function user_to_json($user_item)
	{
		$user_json = array(
			'userId'         => intval($user_item['user_id']),
			'baseId'         => $user_item['base_id'],
			'languageId'     => intval($user_item['language_id']),
			'countryId'      => intval($user_item['country_id']),
			'username'       => $user_item['username'],
			'name'           => $user_item['name'],
			'displayName'    => $user_item['display_name'],
			'bio'            => $user_item['bio'],
			'location'       => $user_item['location'],
			'siteUrl'        => $user_item['site_url'],
			'mediaUrl'       => $user_item['media_url'],
			'colorHex'       => $user_item['color_hex'],
			'timezone'       => $user_item['timezone'],
			'statusCount'    => intval($user_item['status_count']),
			'followingCount' => intval($user_item['following_count']),
			'followersCount' => intval($user_item['followers_count']),
			'voteCount'      => intval($user_item['vote_count']),
			'verified'       => intval($user_item['verified']),
			'baseTimestamp'  => intval($user_item['base_timestamp']),
			'baseIso8601'    => date('c', $user_item['base_timestamp']),
			'cTimestamp'     => intval($user_item['c_timestamp']),
			'cIso8601'       => date('c', $user_item['c_timestamp']),
			'uTimestamp'     => intval($user_item['u_timestamp']),
			'uIso8601'       => date('c', $user_item['u_timestamp']),
			'email'          => array(
				'emailId' => intval($user_item['email']['email_id']),
				'email'   => $user_item['email']['email']
			)
		);

		if ( ! is_null($user_item['base_id'])) $user_json['baseId'] = intval($user_item['base_id']);
		if (is_null($user_item['base_timestamp'])) $user_json['baseIso8601'] = NULL;

		return $user_json;
	}
}