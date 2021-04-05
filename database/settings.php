<?php

namespace JCI\Finland\Events;

function db_option_name() {
	return PLUGIN_SLUG . '_db_version';
}

function db_save_version_option() {
	add_option(
		db_option_name(),
		db_version()
	);
}

function db_version_up_to_date() {
	return get_site_option( db_option_name(), '' ) === db_version();
}

function events_fetched_option() {
	return PLUGIN_SLUG . '_fetched';
}

function save_events_fetched_option(int $timestamp) {
	update_option(
		events_fetched_option(),
		$timestamp
	);
}

function events_fetched() {
	$timestamp = get_site_option( events_fetched_option(), 0 );
	if ( $timestamp ) {
		$format = sprintf(
			'%s %s',
			get_option('date_format'),
			get_option('time_format')
		);
		return wp_date( $format, $timestamp );
	} else {
		return 'N/A';
	}
}
