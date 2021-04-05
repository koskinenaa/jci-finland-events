<?php

namespace JCI\Finland\Events\Api;

class RequestConfig
{

	protected $url;
	protected $parameters;

	public function __construct( string $url, array $data )
	{
		$this->url = $url;
		$this->parameters = array(
			'region' => $data['region'] ?? '',
			'unit' => $data['unit'] ?? '',
			'showalldesc' => 'true',
			'linkson' => 'true',
		);
	}

	public function url()
	{
		return $this->url;
	}

	public function parameters()
	{
		return $this->parameters;
	}

}
