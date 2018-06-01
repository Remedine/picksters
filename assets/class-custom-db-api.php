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

	public function get() {}

	public function  get_by() {}

	public function get_column() {}

	public function get_column_by() {}

	public function insert() {}

	public function update() {}

	public function delete() {}

	public function table_exists() {}


}
