<?php
/**
 * Description.
 *
 * @package ${namespace}
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 */

namespace ExecutiveSuiteIt\Picksters;

//moved this function out of the main picksters.php file - add a call back if wanted to initialize this during startup
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

//registers the custom db tables upon plugin activation
register_activation_hook( __FILE__, 'ExecutiveSuiteIt\Picksters\init_create_custom_db_tables' );

/**
 * Creates the initial database tables.
 *
 * @since 1.0.0
 *
 * @return void
 */
abstract class CUSTOM_DB_API {
	public $table_name;
	public $version;
	public $primary_key;

	/**
	 * Get things started.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function __construct() {
	}

	/**
	 * Whitelist of columns.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array
	 */
	public function get_columns() {
		return array();
	}

	/**
	 * Default column values.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return array
	 */
	public function get_column_defaults() {
		return array();
	}

	/**
	 * Retrieve a row by the primary key.
	 *
	 * @since 1.0.0
	 *
	 * @param $row_id
	 *
	 * @access public
	 * @return object
	 */
	public function get( $row_id ) {
		global $wpdb;

		return $wpdb->get_row( $wpdb->prepare( " SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
	}

	/**
	 * retrieve a row by a specific column / value.
	 *
	 * @since 1.0.0
	 *
	 * @param $column
	 * @param $row_id
	 *
	 * @access public
	 * @return object
	 */
	public function get_by( $column, $row_id ) {
		global $wpdb;
		$column = esc_sql( $column );

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $this->table_name WHERE $column = %s LIMIT 1;", $row_id ) );
	}

	/**
	 * Retrieve a specific column's value by the primary key
	 *
	 * @access  public
	 * @since   2.1
	 * @return  string
	 */
	public function get_column( $column, $row_id ) {
		global $wpdb;
		$column = esc_sql( $column );

		return $wpdb->get_var( $wpdb->prepare( "SELECT $column FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id ) );
	}

	/**
	 * Retrieve a specific column's value by the the specified column / value
	 *
	 * @access  public
	 * @since   2.1
	 * @return  string
	 */
	public function get_column_by( $column, $column_where, $column_value ) {
		global $wpdb;
		$column_where = esc_sql( $column_where );
		$column       = esc_sql( $column );

		return $wpdb->get_var( $wpdb->prepare( "SELECT $column FROM $this->table_name WHERE $column_where = %s LIMIT 1;", $column_value ) );
	}
	/**
	 * Insert a new row
	 *
	 * @access  public
	 * @since   2.1
	 * @return  int
	 */
	public function insert( $data, $type = '' ) {
		global $wpdb;

		//set default values
		$data = wp_parse_args( $data, $this->get_column_defaults() );

		do_action( 'pre_insert' . $type, $data );

		//Initialise column format array
		$column_formats = $this->get_columns();

		//Force fields to lower case
		$data = array_change_key_case( $data );

		//White list columns
		$data = array_intersect_key( $data, $column_formats );

		//Reorder $columns_formats to match the order of columns given
		$data_keys = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		$wpdb->insert( $this->table_name, $data, $column_formats );

		do_action( 'post_insert' . $type, $wpdb->insert_id, $data );

		return $wpdb->insert_id;

	}


	/**
	 * Update a row
	 *
	 * @access  public
	 * @since   2.1
	 * @return  bool
	 */
	public function update( $row_id, $data = array(), $where = '' ) {
		global $wpdb;
		// Row ID must be positive integer
		$row_id = absint( $row_id );
		if( empty( $row_id ) ) {
			return false;
		}
		if( empty( $where ) ) {
			$where = $this->primary_key;
		}
		// Initialise column format array
		$column_formats = $this->get_columns();
		// Force fields to lower case
		$data = array_change_key_case( $data );
		// White list columns
		$data = array_intersect_key( $data, $column_formats );
		// Reorder $column_formats to match the order of columns given in $data
		$data_keys = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );
		if ( false === $wpdb->update( $this->table_name, $data, array( $where => $row_id ), $column_formats ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Delete a row identified by the primary key
	 *
	 * @access  public
	 * @since   2.1
	 * @return  bool
	 */
	public function delete( $row_id = 0 ) {
		global $wpdb;
		// Row ID must be positive integer
		$row_id = absint( $row_id );
		if( empty( $row_id ) ) {
			return false;
		}
		if ( false === $wpdb->query( $wpdb->prepare( "DELETE FROM $this->table_name WHERE $this->primary_key = %d", $row_id ) ) ) {
			return false;
		}
		return true;
	}

		/**
		 * Check if the given table exists
		 *
		 * @since  2.4
		 * @param  string $table The table name
		 * @return bool          If the table name exists
		 */
		public function table_exists( $table ) {
			global $wpdb;
			$table = sanitize_text_field( $table );
			return $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE '%s'", $table ) ) === $table;
		}


}
