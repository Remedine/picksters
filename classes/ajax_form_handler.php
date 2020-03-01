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
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
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

	function scripts() {

		wp_enqueue_style( 'report-a-bug', picksters_plugin_dir . 'assets/css/report_a_bug.css');
		wp_enqueue_script( 'report-a-bug', picksters_plugin_dir . 'assets/js/scripts.js', array( 'jquery' ), null, true );

		// set variables for script
		wp_localize_script( 'report-a-bug', 'settings', array( 'send_label' => __( 'Send report', 'reportabug' ) ) );

	}



}