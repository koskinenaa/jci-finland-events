<?php

namespace JCI\Finland\Events;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

function events_list_page() {
	$hook = add_menu_page(
		__('JCI Finland Events', 'jcifi'),
		__('JCI Finland Events', 'jcifi'),
		events_list_page_capability_requirement(),
		events_list_page_slug(),
		__NAMESPACE__ . '\\render_events_page',
		'dashicons-calendar-alt',
		null
	);
	add_action( "load-$hook", __NAMESPACE__ . '\\events_list_page_screen_options' );
}

function events_list_page_screen_options() {
	add_screen_option(
		'per_page',
		array(
      'label' => __('Events', 'jcifi'),
      'default' => 20,
      'option' => 'jcifi_events_per_page'
    )
	);
}

function events_list_table_set_option($status, $option, $value) {
  return $value;
}

function events_list_page_capability_requirement() {
	return apply_filters(
		'jci_finland_events_list_page_capability_requirement',
		'manage_options'
	);
}

function events_list_page_slug() {
	return 'jci-finland-events';
}

function render_events_page() {
	if ( is_event_edit_view() ) {
		$event = class_events_repository()->event(
			get_current_event_id()
		);
	}

	if ( ! empty($event) ) {
		view(
			'event-edit',
			array(
				'event' => $event,
			),
			true
		);
	} else {
		$table = events_list_table();
		$table->prepare_items();

		view(
			'events-list',
			array(
				'table' => $table,
				'events_fetched' => events_fetched(),
			),
			true
		);
	}
}

function is_event_edit_view() {
	$page = $_GET['page'] ?? '';
	$action = get_current_action();
	$event = get_current_event_id();
	return $page === events_list_page_slug() && $action === 'edit' && is_numeric($event);
}

function get_current_action() {
	$action = $_GET['action'] ?? '';
	$actions = array('edit');
	return $action && in_array($action, $actions) ? $action : null;
}

function get_current_event_id() {
	return ! empty($_GET['event']) ? absint($_GET['event']) : 0;
}
