<?php

namespace JCI\Finland\Events;

function cron_activate() {
	if ( ! wp_next_scheduled( cron_hook() ) ) {
        wp_schedule_event(
			time(),
			'hourly',
			cron_hook()
		);
    }
}

function cron_deactivate() {
	wp_unschedule_event(
		wp_next_scheduled(
			cron_hook()
		),
		cron_hook()
	);
}

function cron_hook() {
	return PLUGIN_SLUG . '_schedule';
}

function cron_action() {
	insert_api_events(
		fetch_api_events()
	);
}
