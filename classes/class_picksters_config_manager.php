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
		$this->create_custom_tables();
		$this->flush_application_rewrite_rules();

	}

	public function add_application_user_roles() {
		add_role( 'picksters_admin', __( 'Picksters Admin', 'picksters' ), array( 'read' => true ) );
		add_role( 'picksters_pickster', __( 'Pickster', 'picksters' ), array( 'read' => true ) );
		add_role( 'picksters_moderator', __( 'Picksters Moderator', 'picksters' ), array( 'read' => true ) );
	}

	public function remove_application_user_roles() {
		//remove_role( 'author' );
		//remove_role( 'editor' );
		//remove_role( 'contributor' );
		//remove_role( 'subscriber' );
	}

	public function add_application_user_capabilities() {
		//Section 1
		$custom_member_capabilities = array(
			'edit_picksters_six_picks',
			'publish_picksters_six_picks',
			'delete_pickster_six_picks',
			'edit_published_picksters_six_picks',
			'create_picksters_six_picks',
			'assign_picksters_six_pick_tag',
		);

		$pickster_admin_role = get_role( 'picksters_admin' );
		$pickster_admin_role->add_cap( 'follow_picksters_activities' );

		$pickster_pickster_role = get_role( 'picksters_pickster' );
		$pickster_pickster_role->add_cap( 'follow_picksters_activities' );

		$pickster_administrator_role = get_role( 'administrator' );
		$pickster_pickster_role->add_cap( 'follow_picksters_activities' );

		foreach ( $custom_member_capabilities as $capability ) {
			$pickster_admin_role->add_cap( $capability );
			$pickster_pickster_role->add_cap( $capability );
			$pickster_administrator_role->add_cap( $capability );

		}

		// Section 2

		$custom_admin_capabilities = array(
			'edit_picksters_six_picks',
			'publish_picksters_six_picks',
			'delete_picksters_six_picks',
			'edit_published_picksters_six_picks',
			'create_picksters_six_picks',
			'delete_published_picksters_six_picks',
			'edit_others_picksters_six_picks',
			'delete_others_picksters_six_picks',
			'assign_picksters_six_pick_tag',
			'delete_picksters_six_pick_tag',
			'edit_picksters_six_pick_tag',
			'manage_picksters_six_pick_tag'
		);

		$picksters_moderator_role    = get_role( 'picksters_moderator' );
		$picksters_admin_role        = get_role( 'picksters_admin' );
		$pickster_administrator_role = get_role( 'administrator' );

		foreach ( $custom_admin_capabilities as $capability ) {
			$picksters_moderator_role->add_cap( $capability );
			$picksters_admin_role->add_cap( $capability );
			$pickster_administrator_role->add_cap( $capability );
		}
	}

	public function flush_application_rewrite_rules() {
		$this->manage_user_routes();
		flush_rewrite_rules();
	}

	public function manage_user_routes() {
		add_rewrite_rule( '^user/([^/]+)/?', 'index.php?control_action=$matches[1]', 'top' );
	}

	public function manage_user_routes_query_vars( $query_vars ) {
		$query_vars[] = 'control_action';

		return $query_vars;
	}

	public function front_controller() {
		global $wp_query, $picksters;
		$control_action = isset( $wp_query->query_vars['control_action'] ) ? $wp_query->query_vars['control_action'] : '';

		switch ( $control_action ) {
			case 'register':
				do_action( 'picksters_before_registration_form' );
				$picksters->registration->display_registration_form();
				break;

			case 'login':
				do_action( 'picksters_before_login_form' );
				$picksters->login->display_login_form();
				break;

			case 'activate':
				do_action( 'picksters_before_activate_user' );
				$picksters->registration->activate_user();
				do_action( 'picksters_after_activate_user' );
				break;

			case 'picks':
				do_action( 'picksters_before_weekly_picks');
				$picksters->weekly_picks->display_weekly_picks_forms();
				do_action( 'picksters_after_weekly_picks');
				break;

			case 'test':
				do_action( 'picksters_before_template');
				$picksters->test->display_data_template();
				do_action( 'picksters_after_template');
				break;

			default:
				break;
		}
	}

	public function create_custom_tables() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$Games_table = $wpdb->prefix . 'games';
		if ( $wpdb->get_var( "show tables like '$Games_table'" ) != $Games_table ) {
			$sql = "CREATE TABLE $Games_table (
                  game_id mediumint(9) NOT NULL Primary Key AUTO_INCREMENT,
                  nfl_game_id VARCHAR(25) NOT NULL UNIQUE,
                  date DATE NOT NULL,
                  game_start_time TIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
                  isotime VARCHAR(100),
                  home_team VARCHAR(38) NOT NULL,
                  away_team VARCHAR(38) NOT NULL,
                  home_team_score mediumint(3),
                  away_team_score mediumint(3),
                  week mediumint(2) NOT NULL,
                  year mediumint(4) NOT NULL,
                  season_type VARCHAR(4) NOT NULL
                ) CHARACTER SET utf8 COLLATE utf8_general_ci;";
			dbDelta( $sql );
		}
		$Picks_table = $wpdb->prefix . 'picks';
		if ( $wpdb->get_var( "show tables like '$Picks_table'" ) != $Picks_table ) {
			$sql2 = "CREATE TABLE $Picks_table (
                    pick_id mediumint(9) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    pick_time DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    pickster_id mediumint(9) NOT NULL,
                    pick VARCHAR(38)
                ) CHARACTER SET utf8 COLLATE utf8_general_ci;";
			dbDelta( $sql2 );
		}
		$Team_table = $wpdb->prefix . 'teams';
		if ( $wpdb->get_var( "show tables like '$Team_table'" ) != $Team_table ) {
			$sql3 = "CREATE TABLE $Team_table (
                 team_id mediumint(2) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                 team_city VARCHAR(40),
                 team_name VARCHAR(40),
                 team_nickname VARCHAR(40),
                 team_nickname2 VARCHAR(40),
                 team_acronym VARCHAR(4)
                 ) CHARACTER SET utf8 COLLATE utf8_general_ci;";
			dbDelta( $sql3 );
		}

	}


}