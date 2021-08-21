<?php

namespace JCI\Finland\Events;

use stdClass;

/**
  * Api\Request
  */
function class_api_request() {
	return new Api\Request(
		new Api\RequestConfig(
			API_BASE_URL,
			settings()
		)
	);
}

function fetch_api_events() {
	$remote = class_api_request();
	save_events_fetched_option(time());
	return $remote->query();
}

/**
  * Repository\Events
  */
function class_events_repository() {
	global $wpdb;

	$config = new Repository\EventsConfig;
	$config->db = $wpdb;
	$config->table = db_table_name($wpdb->prefix);
	$config->model = Model\Event::class;
	$config->formats = new stdClass;
	$config->formats->date = get_option('date_format');
	$config->formats->time = get_option('time_format');

	return new Repository\Events($config);
}

function insert_api_events(array $events) {
	$repository = class_events_repository();
	$insert = $repository->insertMany($events);
}

function clear_events() {
	class_events_repository()->clearEvents();
}

/**
  * Admin\EventsList
  */
function events_list_table() {
	return new Admin\EventListTable(
		class_events_repository()
	);
}
