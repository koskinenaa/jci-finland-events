<?php

namespace JCI\Finland\Events\Api;

class Request
{

	protected $config;

	public function __construct(RequestConfig $config)
	{
		$this->config = $config;
	}

	public function query()
	{
		$response = wp_remote_get(
			$this->url(),
			array()
		);

		$data = json_decode(
			wp_remote_retrieve_body($response),
			true
		);

		return !empty($data['Events']) && is_array($data['Events']) ? $data['Events'] : array();
	}

	public function url()
	{
		return add_query_arg(
			$this->config->parameters(),
			$this->config->url()
		);
	}

}
