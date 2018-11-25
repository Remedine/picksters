<?php
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
use ExecutiveSuiteIt\Picksters\Classes\Picksters_Model_Manager;
use ExecutiveSuiteIt\picksters\classes\Picksters_Model_Weekly_Picks;
use ExecutiveSuiteIt\Picksters\Classes\Picksters_Registration;
use ExecutiveSuiteIt\Picksters\Classes\Picksters_Template_Loader;

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
				self::$instance->template_loader = new Picksters_Template_Loader();
				self::$instance->model_manager   = new Picksters_Model_Manager();
				self::$instance->weekly_picks    = new Picksters_Model_Weekly_Picks();

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
			require_once picksters_plugin_dir . 'classes/class_picksters_template_loader.php';
			require_once picksters_plugin_dir . 'classes/class_picksters_model_manager.php';
			require_once picksters_plugin_dir . 'classes/class_picksters_model_weekly_picks.php';
		}

		public function load_textdomain() {
		}


	}

	function picksters() {
		global $picksters;
		$picksters = picksters::instance();
	}

}
picksters();