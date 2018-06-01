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
	public function __cuonstruct() {}

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
	 * @access public
	 * @return object
	 */
	public function get( $row_id ) {
		global $wpdb;
		return $wpdb->get_row( $wpdb->prepare( " SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id) );
	}

	/**
	 * retrieve a row by a specific column / value.
	 *
	 * @since 1.0.0
	 *
	 * @param $column
	 * @param $row_id
	 * @access public
	 * @return object
	 */
	public function  get_by( $column, $row_id ) {
		global  $wpdb;
		$column = esc_sql( $column );
		return $wpdb->get_row($wpdb->prepare( "SELECT * FROM $this->table_name WHERE $column = %s LIMIT 1;", row_id ) );
	}

	public function get_column() {}

	public function get_column_by() {}

	public function insert() {}

	public function update() {}

	public function delete() {}

	public function table_exists() {}


}
