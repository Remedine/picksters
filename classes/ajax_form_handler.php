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
			'nonce'      => wp_create_nonce( 'nonce' ),
			'ajaxURL'    => admin_url( 'admin-ajax.php' ),
			'send_label' => __( 'Send report', 'reportabug' )
		);

		wp_localize_script( 'ajax_script', 'settings', $jp );


	}


	// Here we register our "send_form" function to handle our AJAX request, do you remember the "superhypermega" hidden field? Yes, this is what it refers, the "send_form" action.
add_action( 'wp_ajax_season_form', 'send_form' ); // This is for authenticated users
//add_action('wp_ajax_nopriv_send_form', 'send_form'); // This is for unauthenticated users.

	/**
	 * In this function we will handle the form inputs and send our email.
	 *
	 * @return void
	 */

	function send_form() {

		// This is a secure process to validate if this request comes from a valid source.
		check_ajax_referer( 'secure-nonce-name', 'security' );

		/**
		 * First we make some validations,
		 * I think you are able to put better validations and sanitizations. =)
		 */

		if ( empty( $_POST["name"] ) ) {
			echo "Insert your name please";
			wp_die();
		}

		if ( ! filter_var( $_POST["email"], FILTER_VALIDATE_EMAIL ) ) {
			echo 'Insert your email please';
			wp_die();
		}

		if ( empty( $_POST["comment"] ) ) {
			echo "Insert your comment please";
			wp_die();
		}

		// This is the email where you want to send the comments.
		$to = 'ourcompanysemail@example.com';

		// Your message subject.
		$subject = 'Now message from a client!';

		$body = 'From: ' . $_POST['name'] . '\n';
		$body .= 'Email: ' . $_POST['name'] . '\n';
		$body .= 'Message: ' . $_POST['comment'] . '\n';

		// This are the message headers.
		// You can learn more about them here: https://developer.wordpress.org/reference/functions/wp_mail/
		$headers = array( 'Content-Type: text/html; charset=UTF-8' );

		wp_mail( $to, $subject, $body, $headers );

		echo 'Done!';
		wp_die();
	}

}