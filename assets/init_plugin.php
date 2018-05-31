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
	$table_name = $wpdb->prefix . 'Pickster_teams';
	//define the table structure
	$sql        = "CREATE TABLE " . $table_name . " (
		id mediumint(3) NOT NULL AUTO_INCREMENT,
		team_name VARCHAR(30),
		team_city VARCHAR(50),
		team_nickname_1 VARCHAR (20),
		team_nickname_2 VARCHAR (20)
		);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	//execute the query creating our table
	dbDelta( $sql );
}
