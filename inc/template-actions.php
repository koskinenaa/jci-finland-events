<?php

namespace JCI\Finland\Events;

add_action('jcifi_event_header', __NAMESPACE__ . '\\event_title', 10, 1);
add_action('jcifi_event_header', __NAMESPACE__ . '\\event_link', 15, 1);

add_action('jcifi_event_header', __NAMESPACE__ . '\\event_organizer', 20, 1);
add_action('jcifi_event_header', __NAMESPACE__ . '\\event_dates', 30, 1);
add_action('jcifi_event_header', __NAMESPACE__ . '\\event_venue', 40, 1);

add_action('jcifi_event_body', __NAMESPACE__ . '\\event_toggled_description', 10, 1);
