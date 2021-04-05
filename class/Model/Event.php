<?php

namespace JCI\Finland\Events\Model;

use stdClass;

class Event
{

	protected $attributes = array(
		'id',
		'title',
		'description',
		'location',
		'geolocation',
		'banner_url',
		'link_url',
		'internal',
		'start_date',
		'start_time',
		'end_date',
		'end_time',
		'organization',
		'organization_id',
		'region',
		'region_id',
	);

	protected $dates = array(
		'start_date',
		'end_date',
	);

	protected $times = array(
		'start_time',
		'end_time',
	);

	public function __construct(stdClass $data, string $dateFormat, string $timeFormat) {
		$this->mapAttributes($data, $dateFormat, $timeFormat);
	}

	public function start()
	{
		return implode(
			' @ ',
			array(
				$this->start_date,
				$this->start_time,
			)
		);
	}

	public function end()
	{
		return implode(
			' @ ',
			array(
				$this->end_date,
				$this->end_time,
			)
		);
	}

	public function hasEnd()
	{
		return $this->end_date || $this->end_time;
	}

	protected function mapAttributes(stdClass $data, string $dateFormat, string $timeFormat) {
		$dates = array_flip($this->dates);
		$times = array_flip($this->times);

		foreach ($this->attributes as $attribute) {
			$value = $data->$attribute ?? '';

			if ( $value ) {
				// TODO: avoid using non-class functions i.e. wp_date
				if ( isset( $dates[$attribute] ) ) {
					$value = wp_date( $dateFormat, strtotime( $value ) );
				} else if ( isset( $times[$attribute] ) ) {
					$value = wp_date( $timeFormat, strtotime( $value ) );
				}
			}

			$this->$attribute = $value;
		}
	}

}
