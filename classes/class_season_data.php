<?php
/**
 * Description.
 *
 * @package ExecutiveSuiteIt\Picksters\Classes;
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 */

namespace ExecutiveSuiteIt\Picksters\Classes;


class Season_Data {

	/*
	 * function to check if a game has started yet and will return true if started
	 *
	 * @since 1.0.0
	 *
	 * @param $date (YYYY-MM-DD)
	 * @param $start_time (HH-MM-SS)
	 *
	 * @return bool - TRUE if game has started
	 */
	public function game_time_compare( $date, $start_time ) {
		$game_time    = $date . ' ' . $start_time;
		$current_time = current_time( 'mysql' );
		$current_time;

		$game_started = false;
		if ( $game_time <= $current_time ) {
			$game_started = true;
		};

		return $game_started;
	}

	/**
	 * Calculates current season, week, and Season_type of NFL games based on today's date.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function current_place_in_season() {
		global $picksters;
		//find the current season year(if in post season will be == to previous calender year)
		$current_time = current_time( 'mysql' );
		$current_date = substr( $current_time, 0, strpos( $current_time, " " ) );
		$date         = explode( '-', $current_date );

		//account for POST season being considered part of previous calendar year
		if ( $date[1] <= 2 ) {
			$date[0] = $date[0] - 1;
		}

		//set season type based on month (assumes August pre - and Jan-Feb post)
		if ( $date[1] == 8 ) {
			$season_type = 'PRE';
		} elseif ( $date[1] <= 2 ) {
			$season_type = 'POST';
		} else {
			$season_type = 'REG';
		}

		//set week
		//get first game data
		$file = picksters_plugin_dir . 'assets/jsondata/' . $date[0] . '/' . 'PRE' . '_' . 1 . '.json';
		if (file_exists($file)) {
			$json_data_first_week_of_season = $this->get_week_json_file( 1, $date[0], 'PRE' );
			$first_game_date                = date( 'Y-m-d', strtotime( $json_data_first_week_of_season['gameScores'][0]['gameSchedule']['gameDate'] ) );
		} else {
			$picksters->test->get_week_json_file( $date[0], 'PRE', 1 );
		}
		//format the date to work with date_diff function
		$datetime1 = date_create( $first_game_date );
		$datetime2 = date_create( $current_date );


		//find the difference in days from the first game of the season - today and +2 to make sure schedule increments on Tuesdays
		$interval = date_diff( $datetime1, $datetime2 );
		$interval = $interval->format( '%a' );
		$interval = $interval + 2;

		//divide by 7 to get weeks and -3 when not in Pre-Season (-4 would give previous week, but we want upcoming)
		if ( $season_type == 'PRE' ) {
			if ( $interval <= 7 ) {
				$week = 2;
			} else {
				$week = intdiv( $interval, 7 );
			}
		} else {
			$week = intdiv( $interval, 7 ) - 3;
		}

		$season = array(
			'year'        => $date[0],
			'season_type' => $season_type,
			'week'        => $week
		);

		return $season;
	}

	//load data file for a single week
	public function get_week_json_file( $week, $year, $seasonType ) {
		global $picksters;
		$file = picksters_plugin_dir . 'assets/jsondata/' . $year . '/' . $seasonType . '_' . $week . '.json';
		if ( file_exists( $file ) ) {
			$json      = file_get_contents( $file );
			$json_data = $picksters->jsonhandler->decode( $json, true );

			return $json_data;
		} else {
			$this->save_json_data();
		}
	}

	//this function is designed to fetch the entire season/schedule and save it to the database
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

	//a helper function used in saving_nfl_data_to_db() function
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

	//a helper function used in saving_nfl_data_to_db() function
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

	//retrieves the json data from NFL live website
	public function get_json_data_from_source( $year, $seasonType, $week ) {
		$json_feed = @file_get_contents( 'http://www.nfl.com/feeds-rs/scores/' . $year . '/' . $seasonType . '/' . $week . '.json' );
		if ( $json_feed === false ) {
			return;
		} else {
			return $json_feed;
		}
	}

	//check for json files and if none retrieves it
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
	public function display_data_template( $page_name = 'test', $week = 1, $year = 2019, $seasonType = 'REG' ) {
		global $picksters, $digital_seeds_template_loader;

		$this->save_season_to_db();
		$json_data = $this->get_week_json_file( $week, $year, $seasonType );

		$digital_seeds_template_loader->get_template_part( 'test' );
		//include picksters_plugin_dir . 'templates/' . $page_name . '.php';
	}

}