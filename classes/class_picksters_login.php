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


class Picksters_Login {

	public function __construct() {
		add_action( 'init', array( $this, 'login_user' ) );
		add_filter( 'authenticate', array( $this, 'authenticate_user' ), 30, 3 );
	}

	public function display_login_form() {
		global $picksters_weekly_games;
		if ( ! is_user_logged_in() ) {
			include picksters_plugin_dir . 'templates/login-template.php';
		} else {
			wp_redirect( home_url() );
		}
		exit;
	}

	public function login_user() {
		global $picksters_login_params;
		$errors = array();

		if ( $_POST['picksters_login_submit'] ) {
			$username = isset( $_POST['picksters_username'] ) ? $_POST['picksters_username'] : '';
			$password = isset( $_POST['picksters_password'] ) ? $_POST['picksters_password'] : '';

			if ( empty( $username ) ) {
				array_push( $errors, __( 'Please enter a username.', 'picksters' ) );
			}

			if ( empty( $password ) ) {
				array_push( $errors, __( 'Please enter a password.', 'picksters' ) );
			}

			if ( count( $errors ) > 0 ) {
				include picksters_plugin_dir . 'templates/login-template.php';
				exit;
			}

			$credentials                  = array();
			$credentials['user_login']    = $username;
			$credentials['user_login']    = sanitize_user( $credentials['user_login'] );
			$credentials['user_password'] = $password;
			$credentials['remember']      = false;
			//rest of the code

			$user = wp_signon( $credentials, false );
			if ( is_wp_error( $user ) ) {
				array_push( $errors, $user->get_error_message() );
				$picksters_login_params['errors'] = $errors;
			} else {
				wp_redirect( home_url() );
				exit;
			}
		}
	}

	public function authenticate_user( $user, $username, $password ) {
		if( ! empty($username) && !is_wp_error($user) ) {
			$user = get_user_by('login', $username );
			if( !in_array( 'administrator', (array) $user->roles ) ) {
				$activate_status = '';
				$activate_status = get_user_meta( $user->data->ID, 'picksters_activation_status', true );
				if ('inactive' == $activate_status ){
					$user = new \WP_Error( 'denied', __('<strong>ERROR</strong>: Please activate your account.', 'picksters' ) );
				}
			}
		}
		return $user;
	}
}