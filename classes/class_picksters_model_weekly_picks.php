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

namespace ExecutiveSuiteIt\picksters\classes;

class Picksters_Model_Weekly_Picks {
	public $post_type;

	public function __construct() {
		$this->post_type = 'weekly_picks';
		add_action( 'init', array( $this, 'create_weekly_picks_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_weekly_picks_meta_boxes' ) );
		add_action( 'init', array( $this, 'process_picks' ) );
	}

	public function create_weekly_picks_post_type() {
		global $picksters;
		$params                       = array();
		$params['post_type']          = $this->post_type;
		$params['singular_post_name'] = __( 'Weekly Pick', 'picksters' );
		$params['plural_post_name']   = __( 'Weekly Picks', 'picksters' );
		$params['description']        = __( 'Make your weekly picks.', 'picksters' );
		$params['supported_fields']   = array( 'title', 'editor' );

		$picksters->model_manager->create_post_type( $params );

	}

	public function add_weekly_picks_meta_boxes() {
		add_meta_boxes( 'picksters_weekly_picks_meta', __( 'Weekly Pick Details', 'picksters' ), array(
			$this,
			'display_weekly_picks_meta_boxes,'
		), $this->post_type );

	}

	public function display_weekly_picks_meta_boxes() {
		global $post;

		$html = "<table class='form-table>";
		$html .= "<tr>";
		$html .= "<th ><label><?php _e('Sticky Status', 'picksters'); ?>*</label></th>";
		$html .= "<td><select class='widefat' name-'picksters_sticky_status'>";
		$html .= "<option value='0'> <?php _e( 'Please Select', 'picksters' ); ?> </option>";

	}

	public function get_current_week() {

		//return $current_week;
	}

	public function get_current_season() {

		//return $current_season;
	}


	public function display_weekly_picks_forms() {
		global $picksters_weekly_picks_params;
		if ( is_user_logged_in() ) {
			$week_games_array = $this->get_weekly_games( $week = 16, $seasonType = 'REG', $year = 2018 );
			include picksters_plugin_dir . 'templates/weekly-pick.php';
		} else {
			wp_redirect( home_url() );
		}
		exit;
	}

	public function process_picks() {
		global $picksters_weekly_picks_params, $picksters_login_params;

		if ( $_POST['picksters_picks_submit'] ) {
			$errors = array();
			if ( is_user_logged_in() ) {
				$picksters_weekly_picks_params['user_id'] = ( get_current_user_id() );
			}


			$how_many_games = $_POST['how_many_games'];
			for ( $i = 1; $i <= $how_many_games; $i ++ ) {
				${'game' . $i} = $_POST[ 'game' . $i ];

				if ( empty( ${'game' . $i} ) ) {
					array_push( $errors, __( 'Oops, you forgot to pick game #' . $i ) );
				}
			}

			$picksters_weekly_picks_params['errors'] = $errors;


		}
	}

	public function get_weekly_games( $week, $seasonType, $year ) {
		global $wpdb;


		$wpdb->show_errors();
		$week_games_array[] = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT home_team, away_team
		FROM wp_games
		WHERE week = %s AND  season_type = %s  AND year = %s", $week, $seasonType, $year
			), ARRAY_A );

		$wpdb->print_error();

		return $week_games_array;


	}
}