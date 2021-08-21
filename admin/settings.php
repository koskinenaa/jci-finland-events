<?php

namespace JCI\Finland\Events;

function setting_names() {
	$group = settings_group();
	$keys = array_keys(
		config('settings')
	);
	$out = array();
	foreach ($keys as $key) {
		$out[$key] = "{$group}_{$key}";
	}
	return $out;
}

function settings() {
	$out = array();
	foreach (setting_names() as $key => $setting) {
		$out[$key] = get_option($setting);
	}
	return $out;
}

function clear_settings() {
	$settings = array_merge(
		array(
			db_option_name(),
			events_fetched_option(),
		),
		setting_names()
	);
	foreach ($settings as $setting) {
		delete_option($setting);
	}
}

function options_page() {
	add_submenu_page(
		events_list_page_slug(),
		__('Settings', 'jcifi'),
		__('Settings', 'jcifi'),
		options_page_capability_requirement(),
		settings_page_slug(),
		__NAMESPACE__ . '\\render_options_page',
		null
	);
}

function options_page_capability_requirement() {
	return apply_filters(
		'jci_finland_events_options_page_capability',
		'manage_options'
	);
}

function settings_page_slug() {
	return 'jci-finland-events-settings';
}

function settings_group() {
	return 'jci_finland_events';
}

function register_settings() {
	$saved = settings();
	$settings = config('settings');
	$group = settings_group();
	$page = settings_page_slug();

	foreach ($settings as $key => $config) {
		$section = "{$group}_section_{$key}";

		register_setting(
			$group,
			"{$group}_{$key}",
			array(
				'type' => $config['type'],
				'description' => $config['description'],
				'sanitize_callback' => $config['sanitize_callback'],
				'show_in_rest' => $config['show_in_rest'],
				'default' => $config['default'],
			)
		);

		add_settings_section(
			$section,
			'',
			__NAMESPACE__ . '\\render_section',
			$page
		);

		add_settings_field(
			$key,
			$config['title'],
			__NAMESPACE__ . '\\render_field',
			$page,
			$section,
			array(
				'key' => "{$group}_{$key}",
				'field' => $config['field'],
				'value' => $saved[$key] ?? '',
			)
		);
	}
}

function render_options_page() {
	view(
		'settings',
		array(),
		true
	);
}

function render_section($args = null) {
	echo '';
}

function render_field($args = null) {
	switch ($args['field']['type']) {
		case 'text':
			printf(
				'<input type="%s" name="%s" value="%s">',
				esc_attr($args['field']['type']),
				esc_attr($args['key']),
				esc_attr(get_option($args['key']))
			);
			break;

		case 'select':
			$options = array();
			if ( ! empty( $args['field']['placeholder'] ) ) {
				$options[] = render_option('', $args['field']['placeholder'], null);
			}

			if ( ! empty( $args['field']['options'] ) ) {
				if ( ! empty( $args['value'] ) ) {
					$selected = $args['value'];
				} else {
					$selected = $args['field']['selected'] ?? '';
				}
				$options = array_merge(
					$options,
					render_options($args['field']['options'], $selected)
				);
			}

			printf(
				'<select name="%s">%s</select>',
				esc_attr($args['key']),
				implode('', $options)
			);
			break;

		case 'checkbox':
			printf(
				'<input type="%s" name="%s" value="1" %s>',
				esc_attr($args['field']['type']),
				esc_attr($args['key']),
				checked(get_option($args['key']), '1', false)
			);
			break;

		default:
			break;
	}

}

function render_options(array $options, $current = '') {
	$out = array();
	foreach ($options as $value => $name) {
		$out[] = render_option( $value, $name, $current );
	}
	return $out;
}

function render_option($value, $name, $current = '') {
	return sprintf(
		'<option value="%s" %s>%s</option>',
		esc_attr( $value ),
		selected( $value, $current, false ),
		esc_html( $name )
	);
}
