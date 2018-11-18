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

namespace ExecutiveSuiteIt\Picksters;


function picksters_send_user_notification( $user_id, $plaintext_pass = '', $activate_code = '' ) {
	$user       = new \WP_User( $user_id );
	$user_login = stripslashes( $user->user_login );
	$user_email = stripslashes( $user->user_email );

	$message = sprintf( __( 'New user registration on %s:', 'picksters' ), get_option( 'blogname' ) ) . '\r\n\r\n';
	$message .= sprintf( __( 'Username: %s', 'picksters' ), $user_login ) . '\r\n\r\n';
	$message .= sprintf( __( 'Email: %s', 'picksters' ), $user_email ) . '\r\n';

	@wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New user registration', 'picksters' ), get_option( 'blogname' ) ), $message );

	if ( empty( $plaintext_pass ) ) {
		return;
	}

	$act_link = site_url() . "/user/activate/?picksters_activation_code=$activate_code";

	$message = __( 'Hi there', 'picksters' ) . '\r\n\r\n';
	$message .= sprintf( __( 'Welcome to %s! Please activate your account using the link:', 'picksters' ), get_option( 'blogname' ) ) . '\r\n\r\n';
	$message .= sprintf(__('<a href="%s">%s</a>', 'picksters'), $act_link, $act_link) . '\r\n';
	$message .= sprintf(__('Username: %s', 'picksters'), $user_login) . '\r\n';
	$message .= sprintf(__ ('Password: %s,', 'picksters'), $plaintext_pass) . '\r\n\r\n';
	wp_mail($user_email, sprintf(__('[%s] Your username and password', 'picksters'), get_option('blogname')), $message);

}