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
		add_action( 'wp_ajax_season_form', array( $this, 'send_season_form' )  ); // This is for authenticated users
		add_action( 'wp_ajax_nopriv_season_form', array( 'send_season_form' )  );
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

	/**
	 * In this function we will handle the season chooser form input and response.
	 *
	 * @return void
	 */

	public function send_season_form() {

		// This is a secure process to validate if this request comes from a valid source.
		if (check_ajax_referer( 'season-form-nonce' ) == TRUE) {

		};
		

		//do something
		$response['season_type'] = $_POST['season_input'];
		$response['success'] = TRUE;



		wp_send_json_success(json_encode($response));

		die();
	}

}