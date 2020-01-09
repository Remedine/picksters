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


class picksters_content_restrictions {

	public function __construct() {
		add_shortcode( 'picksters_private_content', array( $this, 'private_content_block' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_picksters_restriction_box' ) );
		add_action( 'save_post', array( $this, 'save_picksters_restrictions' ) );
		add_action( 'template_redirect', array( $this, 'validate_restrictions' ), 1 );
		//add_action( 'template_redirect', array( $this, 'validate_site_lockdown_restrictions' ) );
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

	public function get_user_roles_by_id( $user_id ) {
		$user = new WP_User( $user_id );
		if ( ! empty( $user->roles ) && is_array( $user->roles ) ) {
			$this->user_roles = $user->roles;

			return $user->roles;
		} else {
			$this->user_roles = array();

			return array();
		}
	}

	public function add_picksters_restriction_box() {
		global $picksters;

		if ( current_user_can( 'manage_options' ) ) {
			add_meta_box(
				'picksters-restrictions',
				__( 'Restriction Settings', 'picksters' ),
				array( $this, 'add_picksters_restrictions' ),
				$picksters->weekly_picks->post_type,
				'normal',
				'low'
			);
		}
	}

	public function add_picksters_restrictions( $post ) {
		global $picksters, $picksters_restriction_params, $digital_seeds_template_loader;
		$picksters_restriction_params['post'] = $post;
		ob_start();

		$digital_seeds_template_loader->get_template_part( 'restriction', 'meta' );
		$display = ob_get_clean();

		echo $display;
	}

	public function save_picksters_restrictions( $post_id ) {
		if ( ! wp_verify_nonce( $_POST['picksters_restriction_settings_nonce'], 'picksters_restrictions_settings' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_posts', $post_id ) ) {
			return;
		}
		$visibility      = isset( $_POST['picksters_pick_visibility'] ) ? $_POST['picksters_pick_visibility'] : 'none';
		$redirection_url = isset( $_POST['picksters_pick_redirection_url'] ) ? $_POST['picksters_pick_redirection_url'] : '';

		update_post_meta( $post_id, '_picksters_pick_visibility', $visibility );
		update_post_meta( $post_id, '_picksters_pick_redirection_url', $redirection_url );
	}

	public function validate_restrictions() {
		global $picksters, $wp_query;

		if ( current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $wp_query->post->ID ) ) {
			return;
		}
		if ( is_page() || is_single() ) {
			$post_id           = $wp_query->post->ID;
			$protection_status = $this->protection_status( $post_id );
			if ( ! $protection_status ) {
				$post_redirection_url = get_post_meta( $post_id, 'picksters_pick_redirection_url', true );
				if ( trim( $post_redirection_url ) == '' ) {
					$post_redirection_url = get_home_url();
				}
				wp_redirect( $post_redirection_url );
				exit;
			}
		}

		return;
	}

	public function protection_status( $post_id ) {
		$visibility = get_post_meta( $post_id, '_picksters_pick_visibility', true );
		switch ( $visibility ) {
			case 'all':
				return true;
				break;
			case 'guest':
				if ( is_user_logged_in() ) {
					return false;
				} else {
					return true;
					break;
				}
			case 'member':
				if ( is_user_logged_in() ) {
					return true;
				} else {
					return false;
				}
				break;
				}
			return TRUE;
	}

}

