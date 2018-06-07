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

public
/**
 * When triggered it creates the picks table.
 *
 * @since 1.0.0
 *
 * @return void
 */
function create_table() {
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

/**
 * Get columns and formats
 *
 * @access  public
 * @since   1.0
 */
public function get_columns() {
	return array(
		pick_id => '%d',
		user_name => '%s',
		user_id  => '%d',
		pick_week  => '%d',
		pick_year => '%d',
		game_id => '%d',
		home_team_name => '%s',
		away_team_name => '%s',
		pick=> '%s',
	);
}

/**
 * Get default column values
 *
 * @access  public
 * @since   1.0
 */
public function get_column_defaults() {
	return array(
		pick_id => '0',
		user_name => '',
		user_id  => '',
		pick_week  => '',
		pick_year => '',
		game_id => '',
		home_team_name => '',
		away_team_name => '',
		pick=> '',
	);
}

/**
 * Retrieve orders from the database
 *
 * @access  public
 * @since   1.0
 * @param   array $args
 * @param   bool  $count  Return only the total number of results found (optional)
 */

public function get_picks( $args = array(), $count = false) {
	global $wpdb;

	$defaults = array(
		'number' => 20,
		'offset' => 0,
		'user_id' => '',
		'pick_id' => 0,
		'pick_year' => '',
		'pick_week' => '',
		'orderby' => 'pick_id',
		'order' => 'DESC',
	);

	$args = wp_parse_args($args, $defaults);

	if( $args['number'] < 1 ) {
		$args['number'] = 9999999999999;
	}

	$where = '';

	// specific referrals
	if( ! empty( $args['pick_id'])) {

		if( is_array($args['pick_id'])) {
			$order_ids = implode(',', $args['order_id']);
		} else {
			$order_ids = intval($args['order_id']);
		}

		$where .= "WHERE 'order_id' IN {$order_ids} ) ";
	}

	if( ! empty($args['']))
}