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

class Picksters_Model_Six_Picks {
	public $post_type;
	public $six_pick_category_taxonomy;
	public $six_pick_tag_taxonomy;
	public $error_message;


	public function __construct() {
		global $picksters;

		$this->post_type                  = 'six_picks';
		$this->six_pick_category_taxonomy = "six_pick_cat;";
		$this->six_pick_tag_taxonomy      = "six_pick_tag";

		add_action( 'init', array( $this, 'create_six_picks_post_type' ) );

		//add_action( 'init', array( $this, 'create_six_pick_custom_taxonomies' ) );
	}

	public function create_six_picks_post_type() {
		global $picksters;

		$params                       = array();

		$params['post_type']          = $this->post_type;
		$params['singular_post_name'] = __( 'Six Picks', 'picksters' );
		$params['plural_post_name']   = __( 'Six Picks', 'picksters' );
		$params['description']        = __( 'Six picks', 'picksters' );
		$params['supported_fields']   = array( 'title', 'editor' );
		$params['capabilities']       = array(
			'edit_post'              => 'edit_picksters_six_picks',
			'read_post'              => 'read_picksters_six_picks',
			'delete_post'            => 'delete_picksters_six_picks',
			'create_post'            => 'create_picksters_six_picks',
			'edit_others_post'       => 'edit_others_picksters_six_picks',
			'publish_posts'          => 'publish_picksters_six_picks',
			'read_private_posts'     => 'read',
			'read'                   => 'read',
			'delete_posts'           => 'delete_picksters_six_picks',
			'delete_private_post'    => 'delete_private_picksters_six_picks',
			'delete_published_posts' => 'delete_published_picksters_six_picks',
			'delete_others_posts'    => 'delete_others_picksters_six_picks',
			'edit_private_posts'     => 'edit_private_picksters_six_picks',
			'edit_published_posts'   => 'edit_published_picksters_six_picks',
		);


		$picksters->model_manager->create_post_type( $params );
	}

	public function create_six_pick_custom_taxonomies() {
		global $picksters;

		$params = array();

		$params['category_taxonomy'] = $this->six_pick_category_taxonomy;
		$params['post_type']         = $this->post_type;
		$params['singular_name']     = __( 'Six Pick Category', 'picksters' );
		$params['plural_name']       = __( 'Six Pick Categor', 'pickters' );
		$params['hierarchical']      = true;
		$picksters->model_manager->create_custom_taxonomies( $params );

		$params['category_taxonomy'] = $this->six_pick_tag_taxonomy;
		$params['post_type']         = $this->post_type;
		$params['singular_name']     = __( 'Six Pick Tag', 'picksters' );
		$params['plural_name']       = __( 'Six Pick Tag', 'picksters' );
		$params['capabilities']      = array(
			'manage_terms' => 'manage_picksters_six_pick_tag',
			'edit_terms'   => 'edit_picksters_six_pick_tag',
			'delete_terms' => 'delete_picksters_six_pick_tag',
			'assign_terms' => 'assign_picksters_six_pick_tag'
		);

		$params['hierarchical'] = false;
		$picksters->model_manager->create_custom_taxonomies( $params );
	}
}