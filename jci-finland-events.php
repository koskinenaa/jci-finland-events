<?php
/**
  * Plugin Name: JCI Finland Events
  * Version: 0.0.1
  * Description: Imports events from the JCI Finland intranet to a WordPress site.
  * Author: Antti Koskinen
  * Text Domain: jcifi
  * Domain Path: /languages
  */

namespace JCI\Finland\Events;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define( __NAMESPACE__ . '\\PLUGIN_VERSION', '0.0.1' );
define( __NAMESPACE__ . '\\PLUGIN_SLUG', 'jcifi_events' );
define( __NAMESPACE__ . '\\PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( __NAMESPACE__ . '\\PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( __NAMESPACE__ . '\\API_BASE_URL', 'https://jci-event-middleware.gambit.site/api/jcievent' );

/**
  * Plugin activation
  */
register_activation_hook( __FILE__, __NAMESPACE__ . '\\activation' );
function activation() {
	/**
	  * Setup database
	  */
	load_file('database/settings', true, true);
	load_file('database/functions', true, true);
	load_file('database/install', true, true);
	if ( ! db_installed() ) {
		db_install();
		db_save_version_option();
	}
}

/**
  * Plugin uninstall
  */
register_uninstall_hook( __FILE__, __NAMESPACE__ . '\\uninstall' );
function uninstall() {
	/**
	  * Clear tables
	  */
	db_drop();

	/**
	  * Clear general settings
	  */
	clear_settings();
}

/**
  * Autoloader
  */
spl_autoload_register( __NAMESPACE__ . '\\autoloader' );
function autoloader( $class ) {
	if ( false !== strpos( $class, __NAMESPACE__ ) ) {
		$parts = array_filter(
			explode(
				'\\',
				str_replace(
					__NAMESPACE__,
					'',
					$class
				)
			)
		);
		$file = PLUGIN_PATH . 'class' . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts) . '.php';
		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
}

/**
  * Bootstrap plugin
  */
add_action( 'plugins_loaded', __NAMESPACE__ . '\\init' );
function init() {
	/**
	  * Text Domain
	  */
	add_action( 'plugins_loaded', __NAMESPACE__ . '\\textdomain' );

	/**
	  * Files
	  */
	foreach (plugin_files() as $file) {
		load_file($file, true, true);
	}

	/**
	  * Settings
	  */
	add_action('admin_menu', __NAMESPACE__ . '\\options_page');
	add_action('admin_init', __NAMESPACE__ . '\\register_settings');

	add_action('update_option_jci_finland_events_region', __NAMESPACE__ . '\\recreate_database', 10, 3);
	add_action('update_option_jci_finland_events_unit', __NAMESPACE__ . '\\recreate_database', 10, 3);

	/**
	  * Admin actions
	  */
	add_action( 'admin_post_populate_database', __NAMESPACE__ . '\\populate_database' );

	/**
	  * Assets
	  */
	add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets');

	/**
	  * Front end
	  */
	add_shortcode( shortcode_name(), __NAMESPACE__ . '\\register_shortcode' );
}

function textdomain() {
	load_plugin_textdomain(
		'jcifi',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages/'
	);
}

function assets_enabled() {
	global $post;
	return is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, shortcode_name() );
}

function assets() {
	if ( ! apply_filters( 'jcifi_events_assets_enabled', assets_enabled() ) ) {
		return;
	}

	/**
	  * Scripts
	  */
	if ( apply_filters( 'jcifi_events_scripts_enabled', true ) ) {
		wp_enqueue_script(
			'jcfi-events',
			PLUGIN_URL . 'assets/scripts.js',
			array(),
			PLUGIN_VERSION,
			true
		);
	}

	/**
	  * Styles
	  */
	if ( apply_filters( 'jcifi_events_styles_enabled', true ) ) {
		wp_enqueue_style(
			'jcfi-events',
			PLUGIN_URL . 'assets/styles.css',
			array(),
			PLUGIN_VERSION,
			'all'
		);
	}
}

function plugin_files() {
	return array(
		'database/functions',
		'database/settings',
		'inc/helpers',
		'inc/shortcode',
		'inc/template-functions',
		'inc/template-actions',
		'admin/actions',
		'admin/settings',
		'class/functions',
	);
}

function load_file( string $file, bool $require = false, bool $once = false ) {
	if ( file_exists( PLUGIN_PATH . "{$file}.php" ) ) {
		if ( $require && $once ) {
			require_once PLUGIN_PATH . "{$file}.php";
		} else if ( $require ) {
			require PLUGIN_PATH . "{$file}.php";
		} else if ( $once ) {
			include_once PLUGIN_PATH . "{$file}.php";
		} else {
			include PLUGIN_PATH . "{$file}.php";
		}
	}
}
