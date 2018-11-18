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


use function ExecutiveSuiteIt\Picksters\picksters_send_user_notification;

class Picksters_Registration {

	public function __construct() {
		add_action( 'init', array( $this, 'register_user' ) );
	}

	public function display_registration_form() {
		global $picksters_registration_params;
		if ( ! is_user_logged_in() ) {
			include picksters_plugin_dir . 'templates/register-template.php';
			exit;
		}
	}


	public function register_user() {
		global $picksters_registration_params, $picksters_login_params;
		if ( $_POST['picksters_reg_submit'] ) {
			$errors     = array();
			$user_login = ( isset ( $_POST['picksters_user'] ) ) ? $_POST['picksters_user'] : '';
			$user_email = ( isset ( $_POST['picksters_email'] ) ) ? $_POST['picksters_email'] : '';
			$user_type  = ( isset ( $_POST['picksters_user_type'] ) ) ? $_POST['picksters_user_type'] : '';

			if ( empty( $user_login ) ) {
				array_push( $errors, __( 'Please enter a username.', 'picksters' ) );
			}
			if ( empty( $user_email ) ) {
				array_push( $errors, __( 'Please enter e-mail.', 'picksters' ) );
			}
			if ( empty ( $user_type ) ) {
				array_push( $errors, __( 'Please enter user type.', 'picksters' ) );
			}

			$sanitized_user_login = sanitize_user( $user_login );

			if ( ! empty( $user_email ) && ! is_email( $user_email ) ) {
				array_push( $errors, __( 'Please enter a valid email.', 'picksters' ) );
			} elseif ( email_exists( $user_email ) ) {
				array_push( $errors, __( 'User with this email already registered.', 'picksters' ) );
			}

			if ( empty( $sanitized_user_login ) || ! validate_username( $user_login ) ) {
				array_push( $errors, __( 'Invalid username.', 'picksters' ) );
			} elseif ( username_exists( $sanitized_user_login ) ) {
				array_push( $errors, __( 'Username already exists.', 'picksters' ) );
			}

			$picksters_registration_params['errors']     = $errors;
			$picksters_registration_params['user_login'] = $user_login;
			$picksters_registration_params['user_email'] = $user_email;
			$picksters_registration_params['user_type']  = $user_type;


			if ( empty( $errors ) ) {
				$user_pass = wp_generate_password();
				$user_id   = wp_insert_user(
					array(
						'user_login' => $sanitized_user_login,
						'user_email' => $user_email,
						'role'       => $user_type,
						'user_pass'  => $user_pass
					)
				);
				if ( ! $user_id ) {
					array_push( $errors, __( 'Registration failed.', 'picksters' ) );
					$picksters_registration_params['errors'] = $errors;
				} else {
					$activation_code = $this->random_string();
					update_user_meta( $user_id, 'picksters_activation_code', $activation_code );
					update_user_meta( $user_id, 'picksters_activation_status', 'inactive' );

					if ( $user_type == 'picksters_premium_member' ) {
						update_user_meta( $user_id, 'picksters_payment_status', 'inactive' );
						// Redirect User to Payment page with User Details
					} else {
						update_user_meta( $user_id, 'picksters_payment_status', 'active' );
						picksters_send_user_notification( $user_id, $user_pass, $activation_code );
						$picksters_login_params['success_message'] = __( 'Registation completed succesffully. Please check your email for activation link.', 'picksters' );
					}
				}

				if ( ! is_user_logged_in() ) {
					wp_set_auth_cookie( $user_id, false, is_ssl() );
					include picksters_plugin_dir . 'templates/login-template.php';
					exit;
				}
			}
		}
	}

	public function random_string() {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstr    = '';
		for ( $i = 0; $i < 15; $i ++ ) {
			$randstr .= $characters[ rand( 0, strlen( $characters ) ) ];
		}

		return $randstr;
	}

	public function activate_user() {
		$activation_code = isset( $_GET['picksters_activation_code'] ) ?
			sanitize_text_field( $_GET['picksters_activation_code'] ) : '';
		$message         = '';

		$user_query = new \WP_User_Query( array(
			'meta_key'   => 'picksters_activation_code',
			'meta_value' => $activation_code
		) );
		$users      = $user_query->get_results();

		if ( ! empty( $users ) ) {
			$user_id = $users[0]->ID;
			update_user_meta( $user_id, 'picksters_activation_status', 'activate' );
			$message = __( 'Account activated super successfully.', 'picksters' );
		} else {
			$message = __( 'Invalid Activation Code', 'picksters' );
		}

		include picksters_plugin_dir . 'templates/info-template.php';
		exit;
	}

}