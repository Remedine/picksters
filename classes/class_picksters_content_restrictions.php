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


class class_picksters_content_restrictions {
	public function __construct() {
		add_shortcode( 'picksters_private_content', array( $this, 'private_content_block' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_topic_restriction_box' ) );
		add_action( 'save_post', array( $this, 'save_topic_restrictions' ) );
	}

	//this is for content restrictions based upon shortcode
	public function private_content_block( $atts, $content ) {
		global $picksters, $wpdb;

		if ( current_user_can( 'manage_options' ) ) {
			return do_shortcode( $content );
		}

		if ( ! is_user_logged_in() ) {
			return __( 'Login to access this content', 'picksters' );
		}

		foreach ( $atts as $sh_attr => $sh_value ) {
			switch ( $sh_attr ) {
				case 'allowed_roles':
					$this->status = $this->allowed_roles_filters( $atts, $sh_value );
					break;
			}

			if ( ! $this->status ) {
				break;
			}
		}

		if ( ! $this->status ) {
			return __( 'You don\'t have permission to access this content', 'picksters' );
		} else {
			return do_shortcode( $content );
		}
	}

	//this is for content restrictions based upon shortcode
	public function allowed_roles_filter( $atts, $sh_value ) {
		global $picksters;
		extract( $atts );

		$user_roles = $this->get_user_roles_by_id( get_current_user_id() );
		$roles      = explode( ',', $sh_value );

		if ( is_array( $roles ) && count( $roles ) > 1 ) {
			foreach ( $roles as $role ) {
				if ( in_array( $role, $user_roles ) ) {
					return true;
				}
			}
		}

		return false;
	}

	//this is for content restrictions based upon topic restrictions
	public function add_topic_restriction_box() {
		global $picksters;
		if ( current_user_can( 'manage_options' ) ) {
			add_meta_box(
				'picksters-restrictions',
				__( 'Restriction Settings', 'picksters' ),
				array( $this, 'add_pickster_restrictions' ),
				$picksters->picks->post_type,
				'normal',
				'low' );
		}
	}

	public function add_picksters_restrictions( $post ) {
		global $picksters, $picksters_restriction_params;
		$picksters_restriction_params['post'] = $post;
		ob_start();
		$picksters->template_loader->get_template_part( 'picksters-restriction-meta');

		$display = ob_get_clean();
		echo $display;
	}

}