<?php

namespace JCI\Finland\Events\Admin;

use WP_List_Table;
use JCI\Finland\Events\Repository\Events;

// https://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/
// TODO: screen options, search

class EventListTable extends WP_List_Table
{

	protected $repository;
	protected $count;

	public function __construct( Events $repository ) {
		parent::__construct(
			array(
				'singular' => 'event',
				'plural'   => 'events',
				'ajax'     => false,
			)
		);

		$this->repository = $repository;
	}

	public function items_count()
	{
		return $this->count;
	}

	public function prepare_items()
	{
		$current_page = $this->get_pagenum();
		$per_page = $this->get_items_per_page('jcifi_events_per_page', 20);
		$offset = $current_page > 1 ? ($current_page - 1) * $per_page : null;

		$this->_column_headers = array(
			$this->get_columns(), // visible
			array(), // hidden
			$this->get_sortable_columns(), // sortable,
			'event_title' // primary
		);

		$query = $this->repository->selectQuery(array(
			'limit' => $per_page,
			'offset' => $offset,
		));

		$this->items = $query->items;
		$this->count = $query->total;

		$this->set_pagination_args(
			array(
				'total_items' => $this->count,
				'per_page'    => $per_page,
				'total_pages' => ceil( $this->count / $per_page ),
			)
		);
	}

	public function get_columns()
	{
		return array(
			// 'cb' => '<input type="checkbox" />',
			// 'id' => __('', 'jcifi'),
			// 'event_id' => __('ID', 'jcifi'),
			// 'post_id' => __('', 'jcifi'),
			// 'thumbnail_id' => __('', 'jcifi'),
			'event_title' => __('Title', 'jcifi'),
			// 'description' => __('Description', 'jcifi'),
			'location' => __('Location', 'jcifi'),
			// 'geolocation' => __('Geolocation', 'jcifi'),
			// 'banner_url' => __('Banner', 'jcifi'),
			// 'link_url' => __('Link', 'jcifi'),
			'internal' => __('Internal', 'jcifi'),
			'start_date' => __('Start date', 'jcifi'),
			'start_time' => __('Start time', 'jcifi'),
			'end_date' => __('End date', 'jcifi'),
			'end_time' => __('End time', 'jcifi'),
			'organization' => __('Organization', 'jcifi'),
			// 'organization_id' => __('Organization ID', 'jcifi'),
			'region' => __('Region', 'jcifi'),
			// 'region_id' => __('Region ID', 'jcifi'),
		);
	}

	public function manage_columns()
	{
		$this->get_column_info();
    return $this->_column_headers[0];
	}

	protected function get_sortable_columns()
	{
    return array();
  }

	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-edit[]" value="%s" />', $item->id
		);
	}

	protected function column_default( $item, $column_name )
	{
		switch ($column_name) {
			case 'internal':
				return $item->internal ? '&check;' : '&times;';
				break;

			default:
				return $item->$column_name ?? '';
				break;
		}
	}

	protected function column_event_title( $item )
	{
		$url = sprintf(
			'?page=%s&action=%s&event=%s',
			esc_attr( $_GET['page'] ?? '' ),
			'edit',
			$item->id
		);

  	return sprintf(
			'<a href="%s"><strong>%s</strong></a> %s',
			$url,
			$item->title,
			$this->row_actions(array(
				'edit' => sprintf(
					'<a href="%s">%s</a>',
					$url,
					__('Edit')
				),
			))
		);
	}

}
