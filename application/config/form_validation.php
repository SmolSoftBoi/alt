<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['error_prefix'] = '<div class="alert alert-danger" role="alert">';
$config['error_suffix'] = '</div>';

$config['auth_sign_in'] = array(
	array(
		'field' => 'username',
		'label' => 'Username or Email',
		'rules' => 'trim|required|max_length[45]'
	),
	array(
		'field' => 'pass',
		'label' => 'Password',
		'rules' => 'trim|required'
	)
);

$config['auth_sign_up'] = array(
	array(
		'field' => 'username',
		'label' => 'Username',
		'rules' => 'trim|required|max_length[45]|is_unique[users.username]'
	),
	array(
		'field' => 'email',
		'label' => 'Email',
		'rules' => 'trim|required|valid_email|max_length[45]|is_unique[emails.email]'
	),
	array(
		'field' => 'pass1',
		'label' => 'Password',
		'rules' => 'trim|required'
	),
	array(
		'field' => 'pass2',
		'label' => 'Confirm Password',
		'rules' => 'trim|required|matches[pass1]'
	)
);

$config['post'] = array(
	array(
		'field' => 'status',
		'label' => 'Status',
		'rules' => 'trim|required|max_length[150]'
	)
);

$config['settings'] = array(
	array(
		'field' => 'username',
		'label' => 'Username',
		'rules' => 'trim|required|max_length[45]'
	),
	array(
		'field' => 'email',
		'label' => 'Email',
		'rules' => 'trim|required|valid_email|max_length[45]'
	),
	array(
		'field' => 'language_id',
		'label' => 'Language',
		'rules' => 'trim|required|is_natural_no_zero'
	),
	array(
		'field' => 'country_id',
		'label' => 'Country',
		'rules' => 'trim|required|is_natural_no_zero'
	),
	array(
		'field' => 'timezone',
		'label' => 'Timezone',
		'rules' => 'trim|required|max_length[6]'
	),
	array(
		'field' => 'color_hex',
		'label' => 'Color',
		'rules' => 'trim|required|max_length[6]'
	)
);