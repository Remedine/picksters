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
 * Setup the plugin's constants and adds .
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

/**
 * Creates the initial database tables.
 *
 * @since 1.0.0
 *
 * @return void
 */
function init_create_custom_db_tables() {
	global $wpdb;
	//define the custom table name
/*	$table_name = $wpdb->prefix . 'Pickster_picks';
	//define the table structure
	$sql = "CREATE TABLE " . $table_name . " (
		pick_id mediumint(30) AUTO_INCREMENT PRIMARY KEY,
		user_name VARCHAR (30) NOT NULL,
		user_id mediumint(30) NOT NULL,
		pick_week mediumint(2) NOT NULL,
		pick_year mediumint(4) NOT NULL,
		game_id mediumint(30) NOT NULL,
		home_team_name VARCHAR(30),
		away_team_name VARCHAR(30),
		pick VARCHAR(50) NOT NULL
		);";
*/
	$table_name = $wpdb->prefix . 'Pickster_games';

	$sql2 = "CREATE TABLE " . $table_name . " (
		game_id mediumint(30) AUTO_INCREMENT PRIMARY KEY,
		week mediumint(2) NOT NULL,
		year mediumint(4) NOT NULL,
		season_type VARCHAR(9) NOT NULL,
		home_team_name VARCHAR(30),
		away_team_name VARCHAR(30),
		home_team_final_score mediumint(3) NOT NULL,
		away_team_final_score mediumint(3) NOT NULL		
		);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	//execute the query creating our table
	dbDelta( $sql );
	dbDelta( $sql2 );

}

function plugin_launch() {
	init_constants();

	//registers the custom db tables upon plugin activation
	register_activation_hook( __FILE__, 'ExecutiveSuiteIt\Picksters\init_create_custom_db_tables' );

	//load the file loader file
	require_once( __DIR__ . '/config/loader.php' );

}

plugin_launch();