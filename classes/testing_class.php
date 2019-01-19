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
			$json      = file_get_contents( $file );
			$json_data = $picksters->jsonhandler->decode( $json, true );


			return $json_data;
		}
	}

	public function display_test_data() {
		global $picksters;
		$json_data = $this->load_nfl_week();
		//$frank     = $this->get_json_data( 3 );
		//$json_1    = $picksters->jsonhandler->decode( $frank, true );

		$this->save_json_data();

		include picksters_plugin_dir . 'templates/test.php';
	}

	public function get_json_data( $year, $seasonType, $week ) {
		$frank = file_get_contents( 'http://www.nfl.com/feeds-rs/scores/' . $year . '/' . $seasonType . '/' . $week . '.json' );

		return $frank;
	}

	public function save_json_data() {
		$nfl_season[] = array(
			array(
				'SeasonType' => 'PRE',
				'length'     => 4
			),
			array(
				'SeasonType' => 'REG',
				'length'     => 17
			),
			array(
				'SeasonType' => 'POST',
				'length'     => 3
			)
		);


		for ( $i = 0; $i <= 2; $i ++ ) {

			$seasonType = $nfl_season[0][$i]['SeasonType'];
			$year       = 2018;



			for ( $ii = 1; $ii <= $nfl_season[0][ $i ]['length']; $ii ++ ) {
				$week     = $ii;


				if ($nfl_season[0][$i]['SeasonType'] == 'POST') {
					$week += 17;
					$nfl_data = $this->get_json_data( $year, $seasonType, $week );
				} else {
					$nfl_data = $this->get_json_data( $year, $seasonType, $week );
				}
				file_put_contents( picksters_plugin_dir . 'assets/jsondata/' . $year . '/' . $seasonType . '_' . $week . '.json', $nfl_data );
			}
		}
	}
}