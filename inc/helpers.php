<?php

namespace JCI\Finland\Events;

/**
  * Config
  */
function config(string $name) {
	return config_exists($name) ? load_config($name) : array();
}

function config_path(string $name) {
	return PLUGIN_PATH  . "config/${name}.php";
}

function config_exists(string $name) {
	return file_exists(
		config_path($name)
	);
}

function load_config(string $name) {
	return include config_path($name);
}

/**
  * Views
  */
function view(string $name, $data = null, bool $once = false) {
	if ( view_exists( $name ) ) {
		if ( $once ) {
			include_once view_path($name);
		} else {
			include view_path($name);
		}
	}
}

function view_exists(string $name) {
	return file_exists(
		view_path( $name )
	);
}

function view_path(string $name) {
	return PLUGIN_PATH  . "view/${name}.php";
}

function jcifi_kses( string $text ) {
	return wp_kses(
		$text,
		array(
			'a' => array(
				'href' => array(),
				'title' => array()
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
			'p' => array(),
			'ol' => array(),
			'ul' => array(),
			'li' => array(),
		)
	);
}
