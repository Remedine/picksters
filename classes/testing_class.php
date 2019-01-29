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
	public function get_week_json_file( $week, $year, $seasonType ) {
		global $picksters;
		$file = picksters_plugin_dir . 'assets/jsondata/' . $year . '/' . $seasonType . '_' . $week . '.json';
		if ( file_exists( $file ) ) {
			$json      = file_get_contents( $file );
			$json_data = $picksters->jsonhandler->decode( $json, true );

			return $json_data;
		}
	}

	/**
	 * Displays the template file with the data loaded for the week being picked.
	 *
	 * @since 1.0.0
	 *
	 * @param $page_name is the file name for the template without the path or .php at the end
	 *
	 * @param $week is the week for which the data should be loaded
	 *
	 * @param $year is the year for the sport being picked
	 *
	 * @param $seasonType should be 'PRE' 'REG' or 'POST'
	 *
	 * @return void
	 */
	public function display_data_template( $page_name = 'test', $week = 1, $year = 2018, $seasonType = 'POST' ) {
		global $picksters;

		$this->save_season_to_db();
		$json_data = $this->get_week_json_file( $week, $year, $seasonType );

		include picksters_plugin_dir . 'templates/' . $page_name . '.php';
	}

	public function get_json_data_from_source( $year, $seasonType, $week ) {
		$json_feed = file_get_contents( 'http://www.nfl.com/feeds-rs/scores/' . $year . '/' . $seasonType . '/' . $week . '.json' );

		return $json_feed;
	}

	public function create_nfl_season_array() {

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
				'length'     => 5
			)
		);

		return $nfl_season;
	}

	public function save_json_data() {
		$nfl_season = $this->create_nfl_season_array();

		for ( $i = 0; $i <= 2; $i ++ ) {

			$seasonType = $nfl_season[0][ $i ]['SeasonType'];
			$year       = 2018;


			for ( $ii = 1; $ii <= $nfl_season[0][ $i ]['length']; $ii ++ ) {
				$week = $ii;


				if ( $nfl_season[0][ $i ]['SeasonType'] == 'POST' ) {
					$week     += 17;
					$nfl_data = $this->get_json_data_from_source( $year, $seasonType, $week );
				} else {
					$nfl_data = $this->get_json_data_from_source( $year, $seasonType, $week );
				}
				file_put_contents( picksters_plugin_dir . 'assets/jsondata/' . $year . '/' . $seasonType . '_' . $week . '.json', $nfl_data );
			}
		}
	}

	public function save_nfl_data_to_db( $json_data ) {

		for ( $i = 0; $i <= 19; $i ++ ) {

			if ( $json_data['gameScores'][ $i ]['gameSchedule']['visitorDisplayName'] == null ) {
				break;
			};

			$game_id            = $json_data['gameScores'][ $i ]['gameSchedule']['gameId'];
			$year               = $json_data['gameScores'][ $i ]['gameSchedule']['season'];
			$week               = $json_data['gameScores'][ $i ]['gameSchedule']['week'];
			$season_type        = $json_data['gameScores'][ $i ]['gameSchedule']['gameType'];
			$date               = $json_data['gameScores'][ $i ]['gameSchedule']['gameDate'];
			$start_time_eastern = $json_data['gameScores'][ $i ]['gameSchedule']['gameTimeEastern'];
			$isotime            = $json_data['gameScores'][ $i ]['gameSchedule']['isoTime'];
			$home_team          = $json_data['gameScores'][ $i ]['gameSchedule']['homeDisplayName'];
			$visitor_team       = $json_data['gameScores'][ $i ]['gameSchedule']['visitorDisplayName'];
			$home_team_score    = $json_data['gameScores'][ $i ]['score']['homeTeamScore']['pointTotal'];
			$visitor_team_score = $json_data['gameScores'][ $i ]['score']['visitorTeamScore']['pointTotal'];

			global $wpdb;
			$wpdb->insert(
				$wpdb->prefix . 'games',
				array(
					'nfl_game_id'     => $game_id,
					'year'            => $year,
					'week'            => $week,
					'season_type'     => $season_type,
					'date'            => $date,
					'game_start_time' => $start_time_eastern,
					'isotime'         => $isotime,
					'home_team'       => $home_team,
					'away_team'       => $visitor_team,
					'home_team_score' => $home_team_score,
					'away_team_score' => $visitor_team_score
				)
			);
		}
	}

	public function save_season_to_db() {
		$nfl_season = $this->create_nfl_season_array();

		for ( $i = 0; $i <= 2; $i ++ ) {

			$seasonType = $nfl_season[0][ $i ]['SeasonType'];
			$year       = 2018;


			for ( $ii = 1; $ii <= $nfl_season[0][ $i ]['length']; $ii ++ ) {
				$week = $ii;


				if ( $nfl_season[0][ $i ]['SeasonType'] == 'POST' ) {
					$week     += 17;
					$nfl_data = $this->get_week_json_file( $week, $year, $seasonType );
				} else {
					$nfl_data = $this->get_week_json_file( $week, $year, $seasonType );
				}
				$this->save_nfl_data_to_db( $nfl_data );
			}
		}
	}
}