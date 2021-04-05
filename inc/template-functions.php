<?php

namespace JCI\Finland\Events;

/**
  * Events classes
  */
function events_get_wrap_classes() {
	$classes = array(
		'events',
		'events--jcifi',
	);

	return esc_attr(
		apply_filters(
			'jcifi_event_wrap_classes',
			implode(' ', $classes)
		)
	);
}

function events_wrap_classes() {
	echo events_get_wrap_classes();
}

/**
  * Event classes
  */
function event_get_classes( Model\Event $event ) {
	$classes = array(
		'event',
		'event--jcifi',
	);

	if ( $event->organization_id ) {
		$classes[] = 'organization-' . $event->organization_id;
	}

	if ( $event->region_id ) {
		$classes[] = 'region-' . $event->region_id;
	}

	echo esc_attr(
		apply_filters(
			'jcifi_event_classes',
			implode(' ', $classes)
		)
	);
}

function event_classes( Model\Event $event ) {
	echo event_get_classes( $event );
}

/**
  * Event title
  */
function event_get_title( Model\Event $event ) {
	return sprintf(
		'<h2 class="event__title">%s</h2>',
		esc_html( $event->title )
	);
}

function event_title( Model\Event $event ) {
	echo event_get_title($event);
}

/**
  * Event organizer
  */
function event_get_organizer( Model\Event $event ) {
	$organizer = array();

	if (
		apply_filters( 'jcifi_event_region_enabled', true, $event ) &&
		$event->region
	) {
		$organizer['region'] = event_get_region($event);
	}

	if (
		apply_filters( 'jcifi_event_organization_enabled', true, $event ) &&
		$event->organization
	) {
		$organizer['organization'] = event_get_organization($event);
	}

	$organizer = apply_filters( 'jcifi_event_get_organizer', $organizer, $event );

	if ( $organizer ) {
		return sprintf(
			'<div class="event__organizer">%s</div>',
			implode(
				'',
				$organizer
			)
		);
	}
}

function event_organizer( Model\Event $event ) {
	echo event_get_organizer( $event );
}

function event_get_region( Model\Event $event ) {
	return sprintf(
		'<div class="event__meta event__region">%s</div>',
		esc_html( $event->region )
	);
}

function event_region( Model\Event $event ) {
	echo event_get_region( $event );
}

function event_get_organization( Model\Event $event ) {
	return sprintf(
		'<div class="event__meta event__organization">%s</div>',
		esc_html( $event->organization )
	);
}

function event_organization( Model\Event $event ) {
	echo event_get_organization( $event );
}

/**
  * Event dates
  */
function event_get_dates( Model\Event $event ) {
	$dates = array();

	if ( apply_filters( 'jcifi_event_start_enabled', true, $event ) ) {
		$dates['start'] = event_get_start($event);
	}

	if (
		apply_filters( 'jcifi_event_end_enabled', true, $event ) &&
		$event->hasEnd()
	) {
		$dates['end'] = event_get_end($event);
	}

	$dates = apply_filters( 'jcifi_event_get_dates', $dates, $event );

	if ( $dates ) {
		return sprintf(
			'<div class="event__dates">%s</div>',
			implode(
				'',
				$dates
			)
		);
	}
}

function event_dates( Model\Event $event ) {
	echo event_get_dates( $event );
}

function event_get_start( Model\Event $event ) {
	return sprintf(
		'<div class="event__meta event__date event__start">
			<span class="label">%s</span><time>%s</time>
		</div>',
		esc_html_x('Starts', 'Event start datetime label', 'jcifi'),
		esc_html( $event->start() )
	);
}

function event_start( Model\Event $event ) {
	echo event_get_start( $event );
}

function event_get_end( Model\Event $event ) {
	return sprintf(
		'<div class="event__meta event__date event__end">
			<span class="label">%s</span><time>%s</time>
		</div>',
		esc_html_x('Ends', 'Event end datetime label', 'jcifi'),
		esc_html( $event->end() )
	);
}

function event_end( Model\Event $event ) {
	echo event_get_end( $event );
}

/**
  * Event venue
  */
function event_get_venue( Model\Event $event ) {
	$venue = array();

	if ( apply_filters( 'jcifi_event_address_enabled', true, $event ) ) {
		$venue['address'] = event_get_address($event);
	}

	$venue = apply_filters( 'jcifi_event_get_venue', $venue, $event );

	if ( $venue ) {
		return sprintf(
			'<div class="event__venue">%s</div>',
			implode(
				'',
				$venue
			)
		);
	}
}

function event_venue( Model\Event $event ) {
	echo event_get_venue( $event );
}

function event_get_address( Model\Event $event ) {
	return sprintf(
		'<address class="event__meta event__address">
			<span class="label">%s</span>
			%s
		</address>',
		esc_html_x('Venue', 'Event address label', 'jcifi'),
		esc_html( $event->location )
	);
}

function event_address( Model\Event $event ) {
	echo event_get_address( $event );
}

/**
  * Event description
  */
function event_get_description( Model\Event $event ) {
	return sprintf(
		'<div class="event__description">%s</div>',
		wpautop( jcifi_kses($event->description) )
	);
}

function event_description( Model\Event $event ) {
	echo event_get_description( $event );
}

function event_get_toggled_description( Model\Event $event ) {
	return sprintf(
		'<button id="event-toggle-%1$d" class="event__toggle button btn" data-jcifi-event-toggle data-text-open="%2$s" data-text-close="%4$s" aria-controls="event-description-%1$d" aria-expanded="false" aria-haspopup="true">%2$s</button>
		<div id="event-description-%1$d" class="event__description" aria-labelledby="event-toggle-%1$d" hidden>
			%3$s
			<button class="event__close button btn" type="button" data-jcifi-event-toggle-close>%4$s</button>
		</div>',
		esc_attr($event->id),
		esc_html_x('Read more', 'Event description toggle label', 'jcifi'),
		wpautop( jcifi_kses($event->description) ),
		esc_html_x('Close', 'Event description toggle close label', 'jcifi')
	);
}

function event_toggled_description( Model\Event $event ) {
	echo event_get_toggled_description( $event );
}

/**
  * Event link
  */
function event_get_link( Model\Event $event ) {
	return sprintf(
		'<a class="event__link" href="%s">%s</a>',
		esc_url($event->link_url),
		esc_html_x('View on Intranet', 'Event external link text','jcifi')
	);
}

function event_link( Model\Event $event ) {
	echo event_get_link( $event );
}
