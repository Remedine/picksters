<?php
/**
 * The API for DB operations for the picks table.
 *
 * @package ${namespace}
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 */

namespace ExecutiveSuiteIt\Picksters;

namespace ExecutiveSuiteIt\Picksters;

/**
 * Get things started with table name, primary key and version
 *
 * @access  public
 * @since   1.0
 */
public function __construct() {
	global $wpdb;

	$this->table_name  = $wpdb->prefix . 'picks';
	$this->primary_key = 'pick_id';
	$this->version     = '1.0';
}

public function create_table() {
	global $wpdb;

	require_once( ABSPATH . 'wp-admin/includes/upgrage.php');

	//$table_name = $wpdb->prefix . 'Pickster_picks';
	//define the picks table columns
	$sql = "CREATE TABLE " . $this->table_name . " (
		pick_id mediumint(30) NOT NULL AUTO_INCREMENT,
		user_name VARCHAR (30) NOT NULL,
		user_id mediumint(30) NOT NULL,
		pick_week mediumint(2) NOT NULL,
		pick_year mediumint(4) NOT NULL,
		game_id mediumint(30) NOT NULL,
		home_team_name VARCHAR(30),
		away_team_name VARCHAR(30),
		pick VARCHAR(50) NOT NULL
		) CHARACTER SET utf8 COLLATE utf8_general_ci;";

	dbDelta( $sql );

	update_option( $this->table_name . '_db_version', $this->version );
}

/*
 * To trigger table creation use during plugin activation
	$db = new PW_Orders_DB;
	$db->create_table();
 */