<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('image_lib');
	}

	public function image_crop($source_image, $width, $height)
	{
		$config = array(
			'source_image' => $source_image
		);

		$size = $this->image_size($source_image);
		$ratio = $this->image_ratio($size['width'], $size['height']);

		$max = max($width, $height);

		$config['width'] = $max;
		$config['height'] = $max;

		if ($ratio < 1) $config['height'] = ceil($max * $ratio);
		if ($ratio > 1) $config['width'] = ceil($max * $ratio);

		$this->image_lib->initialize($config);

		$resize = $this->image_lib->resize();

		if ($resize === FALSE) return FALSE;

		$this->image_lib->clear();

		$config['maintain_ratio'] = FALSE;
		$config['x_axis'] = floor(($config['width'] - $width) / 2);
		$config['y_axis'] = floor(($config['height'] - $height) / 2);
		$config['width'] = $width;
		$config['height'] = $height;

		$this->image_lib->initialize($config);

		$crop = $this->image_lib->crop();

		if ($crop === FALSE) return FALSE;

		return TRUE;
	}

	private function image_size($source_image)
	{
		list($width, $height) = getimagesize($source_image);

		return array(
			'width'  => $width,
			'height' => $height
		);
	}

	private function image_ratio($width, $height)
	{
		return $width / $height;
	}
}