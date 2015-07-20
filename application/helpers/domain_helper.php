<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function domain($domain)
{
	return preg_replace('/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/', '$1', $domain);
}

function strip_protocol($uri)
{
	$uri = explode('://', $uri);

	return $uri[1];
}