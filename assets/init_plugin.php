<?php
/**
 * Description: Initializes the plugin with custom database tables and registration options upon plugin activation.
 *
 * @package ExecutiveSuiteIt\Picksters
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 */

namespace ExecutiveSuiteIt\Picksters;

//registers the custom db tables upon plugin activation
register_activation_hook( __FILE__, 'init_create_custom_db_tables' );

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
	$table_name = $wpdb->prefix . 'Pickster_picks';
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
