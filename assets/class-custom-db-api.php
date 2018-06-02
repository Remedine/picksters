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
	public function __cuonstruct() {
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

	public function update() {
	}

	public function delete() {
	}

	public function table_exists() {
	}


}
