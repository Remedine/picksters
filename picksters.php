git<?php
/**
 * Description: Picksters fun stuff.
 * Plugin Name:  Picksters
 * Author: Timothy Hill
 * Plugin URI:
 * Version: 1.0.0
 * @package ExecutiveSuiteIt\Picksters
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 * requires WP: 4.9
 * requires PHP: 7.1
 */

namespace ExecutiveSuiteIt\Picksters;

use ExecutiveSuiteIt\Picksters\Classes\class_picksters_login;
use ExecutiveSuiteIt\Picksters\Classes\Picksters_Config_Manager;
use ExecutiveSuiteIt\Picksters\Classes\Picksters_Login;
use ExecutiveSuiteIt\Picksters\Classes\Picksters_Registration;

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Cheating?' );
}

if ( ! class_exists( 'picksters' ) ) {
	class Picksters {
		private static $instance;

		public static function instance() {

			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof picksters ) ) {
				self::$instance = new picksters();
				self::$instance->setup_constants();
				self::$instance->includes();

				add_action( 'admin_enqueue_scripts', array( self::$instance, 'load_admin_scripts' ), 9 );
				add_action( 'wp_enqueue_scripts', array( self::$instance, 'load_scripts' ), 9 );

				//Class object initialization
				self::$instance->config_manager = new Picksters_Config_Manager();
				self::$instance->registration   = new Picksters_Registration();
				self::$instance->login          = new Picksters_Login();

				register_activation_hook( __FILE__, array( self::$instance->config_manager, 'activation_handler' ) );

			}

			return self::$instance;

		}

		public function setup_constants() {
			if ( ! defined( 'picksers_version' ) ) {
				define( 'picksters_version', '1.0' );
			}
			if ( ! defined( 'picksters_plugin_dir' ) ) {
				define( 'picksters_plugin_dir', plugin_dir_path( __FILE__ ) );
			}
			if ( ! defined( 'picksters_plugin_url' ) ) {
				define( 'picksters_plugin_url', plugin_dir_url( __FILE__ ) );
			}
		}

		public function load_scripts() {
		}

		public function load_admin_scripts() {
		}

		private function includes() {
			require_once picksters_plugin_dir . 'classes/class_picksters_config_manager.php';
			require_once picksters_plugin_dir . 'classes/class_picksters_registration.php';
			require_once picksters_plugin_dir . 'classes/class_picksters_login.php';
			require_once picksters_plugin_dir . 'functions.php';
		}

		public function load_textdomain() {
		}

		public function create_custom_tables() {
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$Games_table = $wpdb->prefix . 'games';
			if ( $wpdb->get_var( "show tables like '$Games_table'" ) != $Games_table ) {
				$sql = "CREATE TABLE $Games_table (
                  game_id mediumint(9) NOT NULL Primary Key AUTO_INCREMENT,
                  game_start_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,         
                  user_id mediumint(9) NOT NULL,
                  home_team_id VARCHAR(38) NOT NULL,
                  away_team_id VARCHAR(38) NOT NULL,
                  home_team_score mediumint(3),
                  away_team_score mediumint(3),
                  week mediumint(2) NOT NULL,
                  year mediumint(4) NOT NULL,
                  season_type VARCHAR(4) NOT NULL                  
                );";
				dbDelta( $sql );
			}
			$Picks_table = $wpdb->prefix . 'picks';
			if ( $wpdb->get_var( "show tables like '$Picks_table'" ) != $Picks_table ) {
				$sql = "CREATE TABLE $Picks_table (
                    pick_id mediumint(9) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    pick_time DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
                    pickster_id mediumint(9) NOT NULL,
                    pick VARCHAR(38),
                );";
			};
		}

	}

	function picksters() {
		global $picksters;
		$picksters = picksters::instance();
	}

}
picksters();