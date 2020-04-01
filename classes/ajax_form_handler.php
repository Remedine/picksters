<?php
/**
 * Created by PhpStorm.
 * User: AlkalineTim
 * namespace ExecutiveSuiteIt\Picksters\Classes;
 * Date: 2/27/2020
 * Time: 11:13 PM
 */

namespace ExecutiveSuiteIt\Picksters\Classes;


class ajax_form_handler {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'ajax_scripts' ) );
		add_action( 'wp_ajax_week_form', array( $this, 'send_week_form' ) );
	}

	public function week_form() {
		include picksters_plugin_dir . 'templates/choose_week.php';
	}

	public function year_form() {
		include picksters_plugin_dir . 'templates/choose_year.php';
	}

	public function season_form() {
		include picksters_plugin_dir . 'templates/choose_season_type.php';

	}

	public function time_based() {
		include picksters_plugin_dir . 'templates/time_based_chooser.php';
	}

	public function user_based_chooser() {
		include picksters_plugin_dir . 'templates/user_input_chooser.php';
	}

	public function ajax_scripts() {


		wp_enqueue_style( 'week_pick_form', picksters_plugin_url . 'assets/css/week_pick_form.css' );
		wp_enqueue_script( 'ajax_script', picksters_plugin_url . 'assets/js/ajax_script.js', array( 'jquery' ), null, true );


		$jp = array(
			'nonce'      => wp_create_nonce( 'season-form-nonce' ),
			'ajaxURL'    => admin_url( 'admin-ajax.php' ),
			'send_label' => __( 'Send report', 'reportabug' )
		);

		wp_localize_script( 'ajax_script', 'settings', $jp );


	}


	public function send_week_form() {
		global $picksters, $pass_user_input;
		$user_id = get_current_user_id();
		if ( check_ajax_referer( 'season-form-nonce' ) == true ) {
			$response['season_input'] = $_POST['season_input'];
			switch ( $response['season_input'] ) {
				case 'PRE':
					$response['week'] = $_POST['pre_week'];
					break;
				case 'REG':
					$response['week'] = $_POST['reg_week'];
					break;
				case 'POST':
					$response['week'] = $_POST['post_week'];
					break;
			}
			//check if picks have been made
			$response['picked'] = $picksters->weekly_picks->check_if_picked_already( $user_id, '2019', $response['season_input'], $response['week']);
			if ($response['picked'] == false ) {
				$pass_user_input['season_input'] = $response['season_input'];
				$pass_user_input['week'] = $response['week'];
				$url = get_site_url() . '/user/make_picks/';
				wp_safe_redirect( $url );
				exit;
			}
			wp_send_json_success( json_encode( $response ) );
		}

		die();
	}

}