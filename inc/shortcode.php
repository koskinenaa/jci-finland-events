<?php

namespace JCI\Finland\Events;

function shortcode_name() {
	return 'jcifi_events';
}

function register_shortcode($atts = array(), $content = null) {
	$repository = class_events_repository();
	$events = apply_filters(
		'jcifi_events',
		$repository->all()
	);

	ob_start();
	foreach ($events as $event) {
		view(
			'event',
			array(
				'event' => $event,
			),
			false
		);
	}
	$html = ob_get_clean();

	return sprintf(
		'<div class="%s">%s</div>',
		events_get_wrap_classes(),
		$html
	);
}
