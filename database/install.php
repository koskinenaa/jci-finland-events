<?php

namespace JCI\Finland\Events;

function db_install() {
	global $wpdb;

	$table_name = db_table_name( $wpdb->prefix );
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id INT NOT NULL AUTO_INCREMENT,
		title TEXT,
		description LONGTEXT NULL,
		location TEXT NULL,
		geolocation VARCHAR(255) NULL,
		banner_url TEXT NULL,
		link_url VARCHAR(255) NULL,
		internal BOOLEAN,
		start_date DATE DEFAULT '0000-00-00' NOT NULL,
		start_time TIME DEFAULT '00:00:00' NULL,
		end_date DATE DEFAULT '0000-00-00' NULL,
		end_time TIME DEFAULT '00:00:00' NULL,
		organization VARCHAR(255) NULL,
		organization_id INT NULL,
		region VARCHAR(255) NULL,
		region_id INT NULL,
		PRIMARY KEY (id),
		INDEX INTERNAL_INDEX (internal),
		INDEX ORGANIZATION_ID_INDEX (organization_id),
		INDEX REGION_ID_INDEX (region_id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
