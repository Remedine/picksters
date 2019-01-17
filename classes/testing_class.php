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

namespace ExecutiveSuiteIt\Picksters\Classes;




class testing_class {

	//load data file
	public function load_nfl_week() {
		global $picksters;
		$file = picksters_plugin_dir . 'assets/jsondata/nfl.json';
		if ( file_exists( $file ) ) {
			$json = file_get_contents( $file );
			$json_data = $picksters->jsonhandler->decode($json, true);


			return $json_data;
		}
	}

	public function display_test_data() {
		global $picksters;
		$json_data = $this->load_nfl_week();
		$frank = $this->get_json_data(1);
		$json_1 = $picksters->jsonhandler->decode($frank, true);

		include picksters_plugin_dir . 'templates/test.php';
	}

	public function get_json_data($week) {
		$frank = file_get_contents('http://www.nfl.com/feeds-rs/scores/2015/REG/' . $week . '.json');
		return $frank;
	}
}