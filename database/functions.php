<?php

namespace JCI\Finland\Events;

function db_version() {
	return PLUGIN_VERSION;
}

function db_table_name(string $prefix = '') {
	return $prefix . PLUGIN_SLUG;
}

function db_installed() {
	global $wpdb;
	$query = $wpdb->query(
		$wpdb->prepare(
	        "SHOW TABLES LIKE '%s'",
			db_table_name( $wpdb->prefix )
        )
	);
	return $query ? true : false;
}

function db_drop() {
	global $wpdb;
	$db_name = db_table_name( $wpdb->prefix );
	$wpdb->query(
		"DROP TABLE IF EXISTS {$db_name}"
	);
}
