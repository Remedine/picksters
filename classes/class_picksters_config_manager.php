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

class Picksters_Config_Manager {

	public function __construct() {
		add_action( 'init', array( $this, 'manage_user_routes' ) );
		add_filter( 'query_vars', array( $this, 'manage_user_routes_query_vars' ) );
		add_action( 'template_redirect', array( $this, 'front_controller' ) );
	}

	public function activation_handler() {
		$this->add_application_user_roles();
		$this->remove_application_user_roles();
		$this->add_application_user_capabilities();
		$this->flush_application_rewrite_rules();
		$this->create_custom_tables();
	}

	public function remove_application_user_roles() {
		remove_role( 'author' );
		remove_role( 'editor' );
		remove_role( 'contributor' );
		remove_role( 'subscriber' );
	}

	public function front_controller() {
		global $wp_query, $picksters;
		$control_action = isset( $wp_query->query_vars['control_action'] ) ? $wp_query->query_vars['control_action'] : '';

		switch ( $control_action ) {
			case 'register':
				do_action( 'picksters_before_registration_form' );
				$picksters->registration->display_registration_form();
				break;

			case 'activate':
				do_action( 'picksters_before_activate_user' );
				$picksters->registration->activate_user();
				do_action( 'picksters_after_activate_user' );
				break;

			case 'login':
				do_action( 'picksters_before_login_form' );
				$picksters->login->display_login_form();
				break;

		}
	}

	public function add_application_user_roles() {
		add_role( 'picksters_admin', __( 'Picksters Admin', 'picksters' ), array( 'read' => true ) );
		add_role( 'picksters_pickster', __( 'Pickster', 'picksters' ), array( 'read' => true ) );
		add_role( 'picksters_moderator', __( 'Picksters Moderator', 'picksters' ), array( 'read' => true ) );
	}

	public function add_application_user_capabilities() {
		$role = get_role( 'picksters_admin' );
		$role->add_cap( 'follow_forum_activities' );

		$role = get_role( 'picksters_moderator' );
		$role->add_cap( 'follow_forum_activities' );
	}

	public function manage_user_routes() {
		add_rewrite_rule( '^user/([^/]+)/?', 'index.php?control_action=$matches[1]', 'top' );
	}

	public function manage_user_routes_query_vars( $query_vars ) {
		$query_vars[] = 'control_action';

		return $query_vars;
	}

	public function flush_application_rewrite_rules() {
		$this->manage_user_routes();
		flush_rewrite_rules();
	}

}