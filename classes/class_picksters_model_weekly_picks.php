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
		$this->post_type = 'picksters_weekly_picks';
		add_action( 'init', array( $this, 'create_weekly_picks_post_type' ) );
	}

	public function create_weekly_picks_post_type() {
		global $picksters;
		$params                       = array();
		$params['post_type']          = $this->post_type;
		$params['singular_post_name'] = __( 'Weekly Pick', 'picksters' );
		$params['plural_post_name']   = __( 'Weekly Picks', 'picksters' );
		$params['description']        = __( 'Make your weekly picks.', 'picksters' );
		$params['supported_fields']   = array( 'Title,', 'editor' );

		$picksters->model_manager->create_post_type( $params );

	}

}