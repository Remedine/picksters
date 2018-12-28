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

namespace ExecutiveSuiteIt\picksters\classes;

class Picksters_Model_Weekly_Picks {
	public $post_type;

	public function __construct() {
		$this->post_type = 'weekly_picks';
		add_action( 'init', array( $this, 'create_weekly_picks_post_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_weekly_picks_meta_boxes' ) );
	}

	public function create_weekly_picks_post_type() {
		global $picksters;
		$params                       = array();
		$params['post_type']          = $this->post_type;
		$params['singular_post_name'] = __( 'Weekly Pick', 'picksters' );
		$params['plural_post_name']   = __( 'Weekly Picks', 'picksters' );
		$params['description']        = __( 'Make your weekly picks.', 'picksters' );
		$params['supported_fields']   = array( 'title', 'editor' );

		$picksters->model_manager->create_post_type( $params );

	}

	public function add_weekly_picks_meta_boxes() {
		add_meta_boxes( 'picksters_weekly_picks_meta', __( 'Weekly Pick Details', 'picksters' ), array(
			$this,
			'display_weekly_picks_meta_boxes,'
		), $this->post_type );

	}

	public function display_weekly_picks_meta_boxes() {
		global $post;

		$html = "<table class='form-table>";
		$html .= "<tr>";
		$html .= "<th ><label><?php _e('Sticky Status', 'picksters'); ?>*</label></th>";
		$html .= "<td><select class='widefat' name-'picksters_sticky_status'>";
		$html .= "<option value='0'> <?php _e( 'Please Select', 'picksters' ); ?> </option>";

    }

public function get_current_week(){
	$current_week = '2';
	return $current_week;
}

public function get_current_season() {
	$current_season = '2017';
	return $current_season;
}


public function display_weekly_picks_forms() {
	global $picksters_login_params;
	if ( is_user_logged_in() ) {
		include picksters_plugin_dir . 'templates/weekly-pick.php';
	} else {
		wp_redirect( home_url() );
	}
	exit;
}

public function get_weekly_games() {
  global $wpdb;
  $current_week = $this->get_current_week();
  $current_season = $this->get_current_season();

    $wpdb->show_errors();
	$week_games_array = $wpdb->get_results(
    	$wpdb->prepare(
    	"SELECT home_team, away_team
		FROM $wpdb->games
		WHERE season = %d AND week = %d", $current_season, $current_week
    ) );

	$wpdb->print_error();

  return $week_games_array;
}
}