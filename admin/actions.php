<?php

namespace JCI\Finland\Events;

function settings_page_url( string $result = '' ) {
	return add_query_arg(
		array(
			'page' => settings_page_slug(),
			'result' => $result,
		),
		admin_url()
	);
}

function events_page_url( string $result = '' ) {
	return add_query_arg(
		array(
			'page' => events_list_page_slug(),
			'result' => $result,
		),
		admin_url()
	);
}

function populate_database() {
	if (
		check_admin_referer(
			'jcifi_populate_database',
			'jcifi_populate_database_nonce'
		)
	) {
		insert_api_events(
			fetch_api_events()
		);
	}

	wp_redirect(
		events_page_url()
	);
	die;
}

function recreate_database($old_value, $value, $option_name) {
	if ( $old_value !== $value ) {
		clear_events();
		insert_api_events(
			fetch_api_events()
		);
	}
}
