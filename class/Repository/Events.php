<?php

namespace JCI\Finland\Events\Repository;

use stdClass;

class Events
{

	protected $config;

	public function __construct(EventsConfig $config)
	{
		$this->config = $config;
	}

	public function all()
	{
		$query = $this->config->db->get_results(
			"SELECT * FROM {$this->config->table}",
			OBJECT
		);

		if ( is_array($query) ) {
			return array_map(
				array($this, 'newEvent'),
				$query
			);
		} else {
			return array();
		}
	}

	public function count()
	{
		return absint(
			$this->config->db->get_var(
				"SELECT COUNT('id') FROM {$this->config->table}"
			)
		);
	}

	public function insertMany( array $events = array() )
	{
		if ( ! $events ) {
			return;
		}

		/**
		  * Clear previous events to use latest event data
		  */
		$this->clearEvents();

		/**
		  * Prepare insert values
		  */
		$properties = $this->mapProperties(array());
		$propertyKeys = implode(
			', ',
			array_map(
				function($key) {
					return '`' . $key . '`';
				},
				array_keys($properties)
			)
		);

		/**
		  * Prepare insert rows
		  */
		$rows = array();
		foreach ($events as $event) {
			$rows[] = $this->prepareInsertRow($event);
		}
		$rows = implode(', ', $rows);

		/**
		  * Insert events
		  */
		return $this->config->db->query(
			"
				INSERT INTO {$this->config->table}
				({$propertyKeys})
				VALUES
				{$rows}
			"
		);
	}

	public function clearEvents()
	{
		return $this->config->db->query(
			"TRUNCATE TABLE {$this->config->table}"
		);
	}

	protected function prepareInsertRow( array $data )
	{
		$properties = $this->mapProperties($data);
		$placeholders = array();

		foreach ($properties as $key => $value) {
			$normalized = $this->normalizeProperty($key, $value);
			$placeholders[] = $normalized['placeholder'];
			$properties[$key] = $normalized['value'];
		}

		return $this->config->db->prepare(
			'(' . implode(', ', $placeholders) . ')',
			array_values($properties)
		);
	}

	protected function normalizeProperty(string $property, $value = null)
	{
		switch ($property) {
			case 'organization_id':
			case 'region_id':
			case 'internal':
				return array(
					'placeholder' => '%d',
					'value' => absint($value),
				);
				break;

			case 'description':
				// No divs
				$value = mb_ereg_replace(
					'/<div(.*?)>(.*?)<\/div>/',
					'<p>$2</p>',
					$value
				);
				// No empty p
				$value = str_replace(
					array(
						'<p></p>',
						'<p><br></p>',
					),
					array(
						'',
						'',
					),
					$value
				);
				return array(
					'placeholder' => '%s',
					'value' => $value,
				);
				break;

			case 'banner_url':
			case 'link_url':
				return array(
					'placeholder' => '%s',
					'value' => esc_url_raw($value, array('http', 'https')),
				);
				break;

			default:
				return array(
					'placeholder' => '%s',
					'value' => sanitize_text_field($value),
				);
				break;
		}
	}

	protected function mapProperties( array $properties = array() )
	{
		$start = $properties['EventDate'] ?? '';
		$end = $properties['EndDate'] ?? '';

		return array(
			'title' => $properties['Title'] ?? null,
			'description' => $properties['Description'] ?? null,
			'location' => $properties['Location'] ?? null,
			'geolocation' => $properties['GeoLocation'] ?? null,
			'banner_url' => $properties['BannerURL'] ?? null,
			'link_url' => $properties['Link'] ?? null,
			'internal' => $properties['Internal'] ?? 0,
			'start_date' => $start ? date('Y-m-d', strtotime($start)) : null,
			'start_time' => $start ? date('H:i:s', strtotime($start)) : null,
			'end_date' => $end ? date('Y-m-d', strtotime($end)) : null,
			'end_time' => $end ? date('H:i:s', strtotime($end)) : null,
			'organization' => $properties['Chapter'] ?? null,
			'organization_id' => $properties['Nkk'] ?? null,
			'region' => $properties['Region'] ?? null,
			'region_id' => $properties['Alue'] ?? null,
		);
	}

	protected function newEvent( stdClass $data )
	{
		$model = $this->config->model;
		return new $model(
			$data,
			$this->config->dateFormat,
			$this->config->timeFormat
		);
	}
}
