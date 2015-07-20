<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('user_model');
		$this->load->helper(array('date', 'domain'));
	}

	public function read_status($status_id, $config = array())
	{
		$default_config = array(
			'where'     => array(
				'status_id' => $status_id
			),
			'expand'    => NULL,
			'calculate' => NULL,
			'auto'      => FALSE,
			'json'      => FALSE
		);

		if (isset($config['where'])) $config['where'] = array_merge($default_config['where'], $config['where']);

		$config = array_merge($default_config, $config);

		if ( ! is_null($config['expand'])) if ( ! is_array($config['expand'])) $config['expand'] = array($config['expand']);
		if ( ! is_null($config['calculate'])) if ( ! is_array($config['calculate'])) $config['calculate'] = array($config['calculate']);

		foreach ($config['where'] as $field => $value) if ($this->db->field_exists($field, 'statuses')) $this->db->where($field, $value, TRUE);

		$status_item = $this->db->get('statuses', 1);

		if (is_null($status_item->num_rows())) return NULL;

		$status_item = $status_item->row_array();

		if ($config['auto'] === FALSE) if ($this->session->authed === TRUE)
		{
			$vote_id = $this->get_vote_id_where(array(
				'status_id' => $status_id,
				'user_id'   => $this->session->user['user_id']
			));

			$status_item['vote'] = $this->read_vote($vote_id, $config);
		}

		if ( ! is_null($config['expand']))
		{
			if (in_array('replies', $config['expand']))
			{
				$replies = $this->read_statuses(array(
					'reply_status_id' => $status_item['status_id']
				));
			}
		}

		if ($config['json'] == TRUE) return $this->status_to_json($status_item);

		return $status_item;
	}

	public function read_statuses($config = array())
	{
		$default_config = array(
			'where'     => array(
				'reply_status_id' => NULL
			),
			'order_by'  => array(
				'c_timestamp' => 'DESC'
			),
			'limit'     => NULL,
			'offset'    => NULL,
			'expand'    => NULL,
			'calculate' => NULL,
			'json'      => FALSE
		);

		if (isset($config['where'])) $config['where'] = array_merge($default_config['where'], $config['where']);
		if (isset($config['order_by'])) $config['order_by'] = array_merge($default_config['order_by'], $config['order_by']);

		$config = array_merge($default_config, $config);

		if ( ! is_null($config['expand'])) if ( ! is_array($config['expand'])) $config['expand'] = array($config['expand']);
		if ( ! is_null($config['calculate'])) if ( ! is_array($config['calculate'])) $config['calculate'] = array($config['calculate']);

		if ( ! is_null($config['where'])) foreach ($config['where'] as $field => $value) if ($this->db->field_exists(trim(str_replace(array(
			'<',
			'=',
			'>'
		), '', $field)), 'statuses')) $this->db->where($field, $value, TRUE);

		foreach ($config['order_by'] as $field => $direction) $this->db->order_by($field, $direction, TRUE);

		$statuses = $this->db->select('status_id')->get('statuses', $config['limit'], $config['offset']);

		if ($statuses->num_rows() === 0) return NULL;

		foreach ($statuses->result_array() as $status_key => $status)
		{
			$status_items[$status_key] = $this->read_status($status['status_id'], $config);
		}

		return $status_items;
	}

	public function create_status($status_item, $config = array())
	{
		$timestamp = now();

		$config = array_merge_recursive(array(
			'auto' => FALSE,
			'json' => FALSE
		), $config);

		if ($config['json'] == TRUE) $status_item = $this->json_to_status($status_item);

		$user_item = $this->user_model->read_user($status_item['user_id']);

		$status = array(
			'user_id'     => $status_item['user_id'],
			'status'      => $status_item['status'],
			'c_timestamp' => $timestamp
		);

		if (isset($status_item['reply_status_id'])) $status['reply_status_id'] = $status_item['reply_status_id'];
		if (isset($status_item['geo_lat'])) $status['geo_lat'] = $status_item['geo_lat'];
		if (isset($status_item['geo_long'])) $status['geo_long'] = $status_item['geo_long'];
		if (isset($status_item['score'])) $status['score'] = $status_item['score'];
		if (isset($status_item['c_timestamp'])) $status['c_timestamp'] = $status_item['c_timestamp'];

		$user = array(
			'status_count' => $user_item['vote_count'] + 1
		);

		$this->db->trans_start();

		$this->db->insert('statuses', $status);

		$status_id = $this->db->insert_id();

		$this->user_model->update_user($user_item['user_id'], $user);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) return FALSE;

		if ($config['json'] == TRUE) return $this->read_status($status_id, $config);

		return $status_id;
	}

	public function update_status($status_id, $status_item, $config = array())
	{
		$timestamp = now();

		$config = array_merge(array(
			'auto' => FALSE,
			'json' => FALSE
		), $config);

		if ($config['json'] == TRUE) $status_item = $this->json_to_status($status_item);

		$current_status = $this->read_status($status_id);

		if (isset($status_item['score'])) if ($current_status['score'] != $status_item['score']) $status['score'] = $status_item['score'];

		if (isset($status_item['vote']))
		{
			$vote = array(
				'status_id' => $status_id,
				'user_id'   => $status_item['vote']['user_id'],
				'vote'      => $status_item['vote']['vote']
			);

			if (isset($status_item['vote']['c_timestamp'])) $vote['c_timestamp'] = $status_item['vote']['c_timestamp'];
			if (isset($status_item['vote']['u_timestamp'])) $vote['u_timestamp'] = $status_item['vote']['u_timestamp'];
		}

		$this->db->trans_start();

		if (isset($status)) $this->db->update('statuses', $status, array(
			'status_id' => $status_id
		));

		if (isset($vote)) $this->create_update_vote($vote);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) return FALSE;

		if ($config['json'] == TRUE) return $this->read_status($status_id, $config);

		return TRUE;
	}

	private function get_vote_id_where($where)
	{
		$vote_id = $this->db->select('status_vote_id')->get_where('status_votes', $where, 1);

		if ($vote_id->num_rows() === 1) return $vote_id->row()->status_vote_id;

		return NULL;
	}

	private function read_vote($vote_id, $config = array())
	{
		$default_config = array(
			'expand' => NULL,
			'json'   => FALSE
		);

		$config = array_merge($default_config, $config);

		if ( ! is_null($config['expand'])) if ( ! is_array($config['expand'])) $config['expand'] = array($config['expand']);

		$vote_item = $this->db->get_where('status_votes', array(
			'status_vote_id' => $vote_id
		), 1);

		if ($vote_item->num_rows() === 0) return NULL;

		$vote_item = $vote_item->row_array();

		if ( ! is_null($config['expand']))
		{
			if (in_array('status', $config['expand']))
			{
				$vote_item['status'] = $this->read_status($vote_item['status_id'], array_merge($config, array(
					'auto' => TRUE
				)));
			}
		}

		if ($config['json'] == TRUE) return $this->vote_to_json($vote_item);

		return $vote_item;
	}

	private function read_votes($config = array())
	{
		$votes = $this->db->select('status_vote_id')->get('status_votes');

		if ($votes->num_rows() === 0) return NULL;

		foreach ($votes->result_array() as $vote_key => $vote)
		{
			$vote_items[$vote_key] = $this->read_vote($vote['status_vote_id'], $config);
		}

		return $vote_items;
	}

	private function create_vote($vote_item, $config = array())
	{
		$timestamp = now();

		$config = array_merge(array(
			'auto' => FALSE
		), $config);

		$current_status = $this->read_status($vote_item['status_id']);

		$vote = array(
			'status_id'   => $vote_item['status_id'],
			'user_id'     => $vote_item['user_id'],
			'vote'        => $vote_item['vote'],
			'c_timestamp' => $timestamp
		);

		if (isset($vote_item['c_timestamp'])) $vote['c_timestamp'] = $vote_item['c_timestamp'];

		$status = array(
			'score' => $current_status['score'] + $vote_item['vote']
		);

		$this->db->trans_start();

		$this->db->insert('status_votes', $vote);

		$vote_id = $this->db->insert_id();

		$this->update_status($vote_item['status_id'], $status, $config);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) return FALSE;

		return $vote_id;
	}

	private function update_vote($vote_id, $vote_item, $config = array())
	{
		$timestamp = now();

		$config = array_merge(array(
			'auto' => FALSE
		), $config);

		$current_vote = $this->read_vote($vote_id, array_merge(array(
			'expand' => 'status',
			'auto'   => TRUE
		), $config));

		if (isset($vote_item['vote'])) if ($current_vote['vote'] != $vote_item['vote']) $vote['vote'] = $vote_item['vote'];
		if (isset($vote_item['u_timestamp'])) if ($current_vote['u_timestamp'] != $vote_item['u_timestamp']) $vote['u_timestamp'] = $vote_item['u_timestamp'];

		if (isset($vote)) if ($config['auto'] === FALSE) if ( ! isset($vote['u_timestamp'])) $vote['u_timestamp'] = $timestamp;

		if (isset($vote))
		{
			$status = array(
				'score' => $current_vote['status']['score'] - $current_vote['vote'] + $vote['vote']
			);
		}

		$this->db->trans_start();

		if (isset($vote))
		{
			$this->db->update('status_votes', $vote, array(
				'status_vote_id' => $vote_id
			));

			$this->update_status($current_vote['status_id'], $status, $config);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) return FALSE;

		return TRUE;
	}

	private function create_update_vote($vote_item, $config = array())
	{
		$current_vote_id = $this->get_vote_id_where(array(
			'status_id' => $vote_item['status_id'],
			'user_id'   => $vote_item['user_id']
		));

		if (is_null($current_vote_id))
		{
			$vote = array(
				'status_id' => $vote_item['status_id'],
				'user_id'   => $vote_item['user_id'],
				'vote'      => $vote_item['vote']
			);

			if (isset($vote_item['c_timestamp'])) $vote['c_timestamp'] = $vote_item['c_timestamp'];

			$vote = $this->create_vote($vote, $config);
		}
		else
		{
			$current_vote = $this->read_vote($current_vote_id);

			$vote = array(
				'vote' => $vote_item['vote']
			);

			if (isset($vote_item['u_timestamp'])) $vote['u_timestamp'] = $vote_item['u_timestamp'];

			$vote = $this->update_vote($current_vote['status_vote_id'], $vote, $config);
		}

		return $vote;
	}

	public function count_statuses($config = array())
	{
		$status_items = $this->read_statuses($config);

		foreach ($status_items as $status_item)
		{
			if (isset($users[$status_item['user_id']]))
			{
				$users[$status_item['user_id']] += 1;
			}
			else
			{
				$users[$status_item['user_id']] = 1;
			}
		}

		foreach ($users as $user_id => $status_count)
		{
			$this->user_model->update_user($user_id, array(
				'status_count' => $status_count
			), $config);
		}
	}

	public function count_votes($config = array())
	{
		$vote_items = $this->read_votes($config);

		foreach ($vote_items as $vote_item)
		{
			if ($vote_item['vote'] !== 0)
			{
				if (isset($users[$vote_item['user_id']]))
				{
					$users[$vote_item['user_id']] += 1;
				}
				else
				{
					$users[$vote_item
					['user_id']] = 1;
				}
			}
		}

		foreach ($users as $user_id => $vote_count)
		{
			$this->user_model->update_user($user_id, array(
				'vote_count' => $vote_count
			), $config);
		}
	}

	public function set_url()
	{
		$this->input->set_cookie(array(
			'name'   => 'status_url',
			'value'  => uri_string(),
			'expire' => $this->config->item('csrf_expire')
		));
	}

	public function get_url()
	{
		$url = $this->input->cookie($this->config->item('cookie_prefix') . 'status_url', TRUE);

		if (is_null($url)) return '/';

		return $url;
	}

	private function status_to_json($status_item)
	{
		$status_json = array(
			'statusId'      => intval($status_item['status_id']),
			'replyStatusId' => $status_item['reply_status_id'],
			'userId'        => intval($status_item['user_id']),
			'status'        => $status_item['status'],
			'geoLat'        => $status_item['geo_lat'],
			'geoLong'       => $status_item['geo_long'],
			'score'         => intval($status_item['score']),
			'cTimestamp'    => intval($status_item['c_timestamp']),
			'cIso8601'      => date('c', $status_item['c_timestamp']),
			'uTimestamp'    => intval($status_item['u_timestamp']),
			'uIso8601'      => date('c', $status_item['u_timestamp'])
		);

		if ( ! is_null($status_item['reply_status_id'])) $status_json['replyStatusId'] = intval($status_item['reply_status_id']);
		if ( ! is_null($status_item['geo_lat'])) $status_json['geoLat'] = floatval($status_item['geo_lat']);
		if ( ! is_null($status_item['geo_long'])) $status_json['geoLong'] = floatval($status_item['geo_long']);

		if (isset($status_item['vote'])) $status_json['vote'] = $status_item['vote'];

		return $status_json;
	}

	private function json_to_status($status_json)
	{
		if (isset($status_json['replyStatusId'])) $status_item['reply_status_id'] = $status_json['replyStatusId'];
		if (isset($status_json['status'])) $status_item['status'] = $status_json['status'];
		if (isset($status_json['userId'])) $status_item['user_id'] = $status_json['userId'];
		if (isset($status_json['geoLat'])) $status_item['geo_lat'] = $status_json['geoLat'];
		if (isset($status_json['geoLong'])) $status_item['geo_long'] = $status_json['geoLong'];
		if (isset($status_json['score'])) $status_item['score'] = $status_json['score'];
		if (isset($status_json['cTimestamp'])) $status_item['c_timestamp'] = $status_json['cTimestamp'];
		if (isset($status_json['uTimestamp'])) $status_item['u_timestamp'] = $status_json['uTimestamp'];

		if (isset($status_json['vote'])) $status_item['vote'] = $this->json_to_vote($status_json['vote']);

		return $status_item;
	}

	private function vote_to_json($vote_item)
	{
		$vote_json = array(
			'statusVoteId' => intval($vote_item['status_vote_id']),
			'vote'         => intval($vote_item['vote'])
		);

		return $vote_json;
	}

	private function json_to_vote($vote_json)
	{
		if (isset($vote_json['statusVoteId'])) $vote_item['status_vote_id'] = $vote_json['statusVoteId'];
		if (isset($vote_json['statusId'])) $vote_item['status_id'] = $vote_json['statusId'];
		if (isset($vote_json['userId'])) $vote_item['user_id'] = $vote_json['userId'];
		if (isset($vote_json['vote'])) $vote_item['vote'] = $vote_json['vote'];
		if (isset($vote_json['cTimestamp'])) $vote_item['cTimestamp'] = $vote_json['cTimestamp'];
		if (isset($vote_json['uTimestamp'])) $vote_item['uTimestamp'] = $vote_json['uTimestamp'];

		return $vote_item;
	}
}