<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function color_random_hex()
{
	$red = dechex(mt_rand(0, 255));
	$green = dechex(mt_rand(0, 255));
	$blue = dechex(mt_rand(0, 255));

	if (strlen($red) === 1) $red = $red . $red;
	if (strlen($green) === 1) $green = $green . $green;
	if (strlen($blue) === 1) $blue = $blue . $blue;

	return strtoupper($red . $green . $blue);
}

function color_hex_to_hsl($color_hex)
{
	$color_hex = str_replace('#', '', $color_hex);

	if (strlen($color_hex) === 3) $color_hex = $color_hex[0] . $color_hex[0] . $color_hex[1] . $color_hex[1] . $color_hex[2] . $color_hex[2];

	if (strlen($color_hex) !== 6) return NULL;

	$red = hexdec($color_hex[0] . $color_hex[1]) / 255;
	$green = hexdec($color_hex[2] . $color_hex[3]) / 255;
	$blue = hexdec($color_hex[4] . $color_hex[5]) / 255;

	$min = min($red, $green, $blue);
	$max = max($red, $green, $blue);
	$del_max = $max - $min;

	$l = ($max + $min) / 2;

	if ($del_max == 0)
	{
		$h = 0;
		$s = 0;
	}
	else
	{
		if ($l < 0.5)
		{
			$s = $del_max / ($max + $min);
		}
		else
		{
			$s = $del_max / (2 - $max - $min);
		}

		$del_red = ((($max - $red) / 6) + ($del_max / 2)) / $del_max;
		$del_green = ((($max - $green) / 6) + ($del_max / 2)) / $del_max;
		$del_blue = ((($max - $blue) / 6) + ($del_max / 2)) / $del_max;

		if ($red == $max)
		{
			$h = $del_blue - $del_green;
		}
		else if ($green == $max)
		{
			$h = (1 / 3) + $del_red - $del_blue;
		}
		else if ($blue == $max)
		{
			$h = (2 / 3) + $del_green - $del_red;
		}

		if ($h < 0) $h++;
		if ($h > 1) $h--;
	}

	return array(
		'h' => $h * 360,
		's' => $s,
		'l' => $l
	);
}

function color_hsl_to_hex($h, $s, $l)
{
	$h = $h / 360;

	if ($s == 0)
	{
		$red = $l * 255;
		$green = $l * 255;
		$blue = $l * 255;
	}
	else
	{
		if ($l < 0.5)
		{
			$v2 = $l * (1 + $s);
		}
		else
		{
			$v2 = ($l + $s) - ($s * $l);
		}

		$v1 = 2 * $l - $v2;

		$red = round(255 * color_hue_to_rgb($v1, $v2, $h + (1 / 3)));
		$green = round(255 * color_hue_to_rgb($v1, $v2, $h));
		$blue = round(255 * color_hue_to_rgb($v1, $v2, $h - (1 / 3)));
	}

	$red = dechex($red);
	$green = dechex($green);
	$blue = dechex($blue);

	if (strlen($red) === 1) $red = $red . $red;
	if (strlen($green) === 1) $green = $green . $green;
	if (strlen($blue) === 1) $blue = $blue . $blue;

	return strtoupper($red . $green . $blue);
}

function color_hue_to_rgb($v1, $v2, $h)
{
	if ($h < 0) $h++;
	if ($h > 1) $h--;

	if ((6 * $h) < 1) return $v1 + ($v2 - $v1) * 6 * $h;
	if ((2 * $h) < 1) return $v2;
	if ((3 * $h) < 2) return $v1 + ($v2 - $v1) * ((2 / 3) - $h) * 6;

	return $v1;
}