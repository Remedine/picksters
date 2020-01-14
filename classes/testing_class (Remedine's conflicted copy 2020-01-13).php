<?php
/**
 * Description.
 *
 * @package ExecutiveSuiteIt\Picksters\Classes
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
		} else {
			$this->save_json_season_data();
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
	public function display_data_template( $page_name = 'test', $week = 5, $year = 2019, $seasonType = 'REG' ) {
		global $picksters;

		//$this->save_season_to_db();
		$this->current_place_in_season();
		$json_data = $this->get_week_json_file( $week, $year, $seasonType );

		include picksters_plugin_dir . 'templates/' . $page_name . '.php';
	}

//set current season, week and year
	public function current_place_in_season() {
		//find the current season year(if in post season will be == to previous calender year)
		$current_time = current_time( 'mysql' );
		$current_date = substr( $current_time, 0, strpos( $current_time, " " ) );
		$date         = explode( '-', $current_date );
		//$today_date is used later in this function, but needs set up here before I manipulate the $date variable
		//$today_date = implode($date);
		//d($date, $today_date);
		if ( $date[1] <= 2 ) {
			$date[0] = $date[0] - 1;
		}

		//set season type based on month (assumes August pre - and Jan-Feb post
		if ($date[1] == 8) {
			$season_type = 'PRE';
		} elseif ($date[1] <=2 ) {
			$season_type = 'POST';
		} else {
			$season_type = 'REG';
		}

		d($date[0]);
		//set week
		//get first game data
		$json_data_first_week_of_season = $this->get_week_json_file( 1, $date[0], 'PRE' );
		$first_game_date = date( 'Y-m-d', strtotime($json_data_first_week_of_season['gameScores'][0]['gameSchedule']['gameDate']));
		d($first_game_date, $current_date);
		$datetime1 = date_create($first_game_date);
		$datetime2 = date_create($current_date);
		d($datetime1,$datetime2);
		$interval = date_diff($datetime1, $datetime2);
		//$first_game_date = explode( '-', $first_game_date);
		//$first_game_date = implode($first_game_date);
		//$compare_dates = $today_date - $first_game_date;
		//d($compare_dates);
		d($interval);
		//$week = (int) $interval['days'] / 7;
		//d($week);

		//d($first_game_date, $json_data_first_week_of_season, $today_date, $week );

		$season = array(
			'year' => $date[0],
			'season_type' => $season_type,
			'week' => $week
		);
		ddd( $season );
		return $season;
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

	public function get_json_data_from_source( $year, $seasonType, $week ) {
		$json_feed = @file_get_contents( 'http://www.nfl.com/feeds-rs/scores/' . $year . '/' . $seasonType . '/' . $week . '.json' );
		if ( $json_feed === false ) {
			return;
		} else {
			return $json_feed;
		}
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
				if ( $nfl_data == null ) {
				} else {
					file_put_contents( picksters_plugin_dir . 'assets/jsondata/' . $year . '/' . $seasonType . '_' . $week . '.json', $nfl_data );
				}
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
			$date               = date( 'Y-m-d', strtotime( $json_data['gameScores'][ $i ]['gameSchedule']['gameDate'] ) );
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
			$year       = 2019;


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

	//function to retrieve latest Json data
	public function update_week_data( $year, $season, $week ) {

	}

	//function to compare two json file and replace the old with the new
	public function compare_week_data( $year, $season, $week ) {
		global $picksters;

		//get the data from the source and from our stored file
		$this->get_json_data_from_source( $year, $season, $week );
		$this->get_week_json_file( $week, $year, $season );


	}
	//
}