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
		return $this->selectQuery();
	}

	public function event( int $id )
	{
		if ( ! $id ) {
			return;
		}

		$args = $this->queryArgs(array());
		$args['base'] = "SELECT * FROM {$this->config->table} WHERE `id` = {$id}";

		$query = $this->queryResult(
			'select',
			$this->config->db->get_results(
				$this->buildQueryString($args),
				OBJECT
			)
		);

		return $query->items ? $query->items[0] : null;
	}

	public function count()
	{
		return absint(
			$this->config->db->get_var(
				"SELECT COUNT('id') FROM {$this->config->table}"
			)
		);
	}

	public function selectQuery( array $args = array() )
	{
		$args = $this->queryArgs($args);
		$args['base'] = "SELECT * FROM {$this->config->table}";

		return $this->queryResult(
			'select',
			$this->config->db->get_results(
				$this->buildQueryString($args),
				OBJECT
			),
			$this->count()
		);
	}

	public function insertMany( array $events = array() )
	{
		if ( ! $events ) {
			return;
		}

		/**
		  * Prepare insert values
		  */
		$properties = $this->mapProperties(array());
		$propertyKeys = $duplicateMap = array();

		foreach ($properties as $key => $value) {
			$propertyKeys[] = '`' . $key . '`';
			$duplicateMap[] = '`' . $key . '`' . ' = VALUES(`' . $key . '`)';
		}

		$propertyKeys = implode(', ', $propertyKeys);
		$duplicateMap = implode(', ', $duplicateMap);

		/**
		  * Prepare insert rows
		  */
		$rows = implode(
			', ',
			array_map(
				array($this, 'prepareInsertRow'),
				$events
			)
		);

		/**
		  * Insert events
		  */
		return $this->config->db->query(
			"
				INSERT INTO {$this->config->table}
				({$propertyKeys})
				VALUES
				{$rows}
				ON DUPLICATE KEY UPDATE
				{$duplicateMap}
			"
		);
	}

	public function clearEvents()
	{
		return $this->config->db->query(
			"TRUNCATE TABLE {$this->config->table}"
		);
	}

	protected function queryResult( string $type, $items = null, $total = null )
	{
		$result = new stdClass;
		$result->type = $type;
		$result->total = $total;
		$result->items = array();

		if ( $items && is_array($items) ) {
			$result->items = array_map(
				array($this, 'newEvent'),
				$items
			);
		}

		return $result;
	}

	protected function buildQueryString( array $args = array() )
	{
		$sql = array(
			'base' => '',
			'where' => '',
			'limit' => '',
			'offset' => '',
		);

		foreach ($args as $key => $value) {
			if ( null === $value ) {
				continue;
			}

			switch ($key) {
				case 'base':
					$sql[$key] = $value;
					break;

				case 'limit':
				case 'offset':
					$sql[$key] = strtoupper($key) . ' ' . absint($value);
					break;

				default:
					break;
			}
		}

		return implode(' ', $sql);
	}

	protected function queryArgs( array $args = array() )
	{
		$defaults = array(
			'limit' => null,
			'offset' => null,
		);

		foreach ($defaults as $key => $value) {
			if ( isset($args[$key]) && null !== $args[$key] ) {
				$defaults[$key] = $args[$key];
			}
		}

		return $defaults;
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

		$eventUrl = $properties['Link'] ?? null;
		$eventId = 0;
		if ( $eventUrl ) {
			$parsedEventUrl = parse_url($eventUrl, PHP_URL_QUERY);
			if ( $parsedEventUrl ) {
				parse_str($parsedEventUrl, $eventUrlParts);
				if (
					! empty($eventUrlParts['ID']) &&
					is_numeric($eventUrlParts['ID'])
				) {
					$eventId = intval($eventUrlParts['ID']);
				}
			}
		}

		return array(
			'event_id' => $eventId,
			'title' => $properties['Title'] ?? null,
			'description' => $properties['Description'] ?? null,
			'location' => $properties['Location'] ?? null,
			'geolocation' => $properties['GeoLocation'] ?? null,
			'banner_url' => $properties['BannerURL'] ?? null,
			'link_url' => $eventUrl,
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
			$this->config->formats
		);
	}
}
