<?php
/**
 * Description: Picksters fun stuff.
 * Plugin Name:  Picksters
 * Author: Timothy Hill
 * Plugin URI:
 * Version: 1.0.0
 * @package ExecutiveSuiteIt\Picksters
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 * requires WP: 4.9
 * requires PHP: 7.1
 */

namespace ExecutiveSuiteIt\Picksters;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Cheating?' );
}


/**
 * Setup the plugin's constants.
 *
 * @since 1.0.0
 *
 * @return void
 */
function init_constants() {
	$plugin_url = plugin_dir_url( __FILE__ );
	if ( is_ssl() ) {
		$plugin_url = str_replace( 'http://', 'https://', $plugin_url );
	}

	define( 'Picksters_URL', $plugin_url );
	define( 'Picksters_DIR', plugin_dir_path( __FILE__) );
}


function plugin_launch() {
	init_constants();

	require_once( __DIR__ . '/assets/vendor/autoload.php' );
}

plugin_launch();