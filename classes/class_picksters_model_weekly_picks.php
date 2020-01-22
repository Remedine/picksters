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
		add_action( 'save_post', array( $this, 'save_weekly_picks_meta_data' ) );
		add_filter( 'post_updated_messages', array( $this, 'display_errors_weekly_picks_meta' ) );
		add_action( 'admin_notices', array( $this, 'weekly_picks_admin_notices' ) );
	}

	public function create_weekly_picks_post_type() {
		global $picksters;
		$params                       = array();
		$params['post_type']          = $this->post_type;
		$params['singular_post_name'] = __( 'Weekly Picks', 'picksters' );
		$params['plural_post_name']   = __( 'Weekly Picks', 'picksters' );
		$params['description']        = __( 'Make your weekly picks.', 'picksters' );
		$params['supported_fields']   = array( 'title', 'custom-fields' );

		$picksters->model_manager->create_post_type( $params );

	}

	public function add_weekly_picks_meta_boxes() {
		add_meta_box( 'picksters_weekly_picks_meta', __( 'This Weeks Games', 'picksters' ), array(
			$this,
			'display_weekly_picks_meta_boxes'
		), $this->post_type );

	}

	public function display_weekly_picks_meta_boxes() {
		global $picksters, $post, $picksters_weekly_picks_params, $week_games_array, $digital_seeds_template_loader;


		$week_games_array = $this->get_weekly_games( $year , $seasonType, $week );
		$picksters_weekly_picks_params['$this->post_type'];
		$picksters_weekly_picks_params['weekly_picks_meta_nonce'] = wp_create_nonce( 'picksters_weekly_picks_meta_nonce' );
		//ob_start();
		//$digital_seeds_template_loader->get_template_part( 'weekly-pick', 'meta' );

		require_once( picksters_plugin_dir . 'templates/weekly-pick-meta-template.php' );

		//$display= ob_get_clean();
		//echo $display;
	}

	public function save_weekly_picks_meta_data() {
		global $post, $picksters;
		if ( isset( $_POST['weekly_picks_meta_nonce'] ) && ! wp_verify_nonce( $_POST['weekly_picks_meta_nonce'], "picksters_weekly_picks_meta_nonce" ) ) {
			return $post->ID;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post->ID;
		}
		if ( isset( $_POST['post_type'] ) && $this->post_type == $_POST['post_type'] /* && current_user_can( 'edit_picksters_weekly_picks', $post->ID ) */ ) {
			//Implement the validations and data saving
			//Section 1 - validation and sanitation
			$errors             = array();
			$how_many_games     = sanitize_text_field( trim( $_POST['how_many_games'] ) );
			$picked_games_array = array();
			for ( $i = 1; $i <= $how_many_games; $i ++ ) {
				${'game' . $i} = $_POST[ 'game' . $i ];
				//push errors back to weekly picks template
				if ( empty( ${'game' . $i} ) ) {
					array_push( $errors, __( '*Hey, don\'t forget to pick game #' . $i . '!' ) );
				}
				//sanitize user submission and save to array
				$picked_games_array[ 'game' . $i ] = sanitize_text_field( trim( ${'game' . $i} ) );
			}
		}
		//Section 2 -  save if no errors, remove post save and set to draft pass errors as transient
		if ( ! empty( $errors ) ) {
			remove_action( 'save_post', array( $this, 'save_weekly_picks_meta_data' ) );
			$post->post_status = 'draft';
			$post->post_title  = isset( $_POST['post_title'] ) ? sanitize_text_field( $_POST['post_title'] ) : '';
			wp_update_post( $post );
			add_action( 'save_post', array( $this, 'save_weekly_picks_meta_data' ) );
			set_transient( $this->post_type . "_games_$post->ID", $picked_games_array, 60 * 10 );
			set_transient( $this->post_type . "_error_message_$post->ID", $errors, 60 * 10 );
		} else {
			update_post_meta( $post->ID, 'Weekly_picks', $picked_games_array );
		}

	}

	public function display_errors_weekly_picks_meta( $messages ) {
		global $picksters;

		$params                  = array();
		$params['post_type']     = $this->post_type;
		$params['singular_name'] = __( 'Weekly Picks', 'picksters' );
		$params['plural_name']   = __( 'Weekly Picks', 'picksters' );
		$messages                = $picksters->model_manager->generate_messages( $messages, $params );

		return $messages;
	}

	public function weekly_picks_admin_notices() {
		global $post;
		$temp_error_message = get_transient( $this->post_type . "_error_message_$post->ID" );
		delete_transient( $this->post_type . "_error_message_$post->ID" );
		if ( ! ( $temp_error_message ) ) {
			return;
		}
		$how_many_games = count( $temp_error_message );
		for ( $i = 0; $i <= $how_many_games; $i ++ ) {
			$message .= '<div id="picksters_errors" class="error below-h2"><p>' . $temp_error_message[ $i ] . '</p></div>';
		}

		if ( ! empty( $message ) ) {
			echo $message;
		};
		remove_action( 'admin_notices', array( $this, 'weekly_picks_admin_notices' ) );
	}


	/**
	 * Displays the weekly picks html template.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function display_weekly_picks_forms() {
		global $picksters, $picksters_weekly_picks_params, $digital_seeds_template_loader;
		if ( is_user_logged_in() ) {
			$current_place_in_season = $picksters->season_data->current_place_in_season();

			$week_games_array        = $this->get_weekly_games( $year = $current_place_in_season['year'], $seasonType = $current_place_in_season['season_type'], $week = $current_place_in_season['week'] );
			//$digital_seeds_template_loader->get_template_part( 'weekly-pick' );
			include picksters_plugin_dir . 'templates/weekly-pick-template.php';
		} else {
			wp_redirect( home_url() );
		}
		exit;
	}

	/**
	 * Query to get the NFL games for the week from the database. and returns them as an array.
	 *
	 * @since 1.0.0
	 *
	 * @param $year
	 * @param $seasonType
	 * @param $week
	 *
	 * @return array
	 */
	public function get_weekly_games( $year, $seasonType, $week ) {
		global $wpdb;
		// week 21 is a null week after championship game and before the superbowl
		if ($week == 21 ) {
			$week = 22;
		}

		//$wpdb->show_errors();
		$week_games_array[] = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT home_team, away_team
		FROM wp_games
		WHERE year = %d AND  season_type = %s  AND week = %d", $year, $seasonType, $week
			), ARRAY_A );

		//$wpdb->print_error();

		return $week_games_array;


	}

	/**
	 * Process the weekly-picks form data and save to database or push errors back to form.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function process_picks() {
		global $picksters_weekly_picks_params;

		if ( $_POST['picksters_picks_submit'] ) {
			$errors = array();
			if ( is_user_logged_in() ) {
				$picksters_weekly_picks_params['user_id'] = ( get_current_user_id() );
			}

			//make $Game[1-x] build variables dynamically depending upon how many games that week.
			$how_many_games = $_POST['how_many_games'];
			for ( $i = 1; $i <= $how_many_games; $i ++ ) {
				${'game' . $i}                                = $_POST[ 'game' . $i ];
				$picksters_weekly_picks_params[ 'game' . $i ] = $_POST[ 'game' . $i ];


				//push errors back to weekly picks template
				if ( empty( ${'game' . $i} ) ) {
					array_push( $errors, __( '*Oops, you forgot to pick game #' . $i ) );
				}
			}

			$picksters_weekly_picks_params['errors'] = $errors;

			if ( empty( $errors ) ) {

			}


		}
	}



}