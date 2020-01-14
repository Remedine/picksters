<?php
/**
 * Description.
 *
 * @package ExecutiveSuiteIt\picksters\classes;
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 *
 * @description This adds a dashboard to the front-end for logged in users.
 */

namespace ExecutiveSuiteIt\Picksters\Classes;

class Picksters_Dashboard {
	public function __construct() {
		$this->set_frontend_toolbar( true );
		add_action( 'wp_before_admin_bar_render', array( $this, 'customize_admin_toolbar' ) );
		add_action( 'admin_menu', array( $this, 'customize_main_navigation') );
	}

	public function set_frontend_toolbar( $status ) {
		show_admin_bar( $status );
	}

	public function customize_admin_toolbar() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'updates' );
		$wp_admin_bar->remove_menu( 'comments' );
		$wp_admin_bar->remove_menu( 'new-content' );
		$wp_admin_bar->remove_menu( 'customize' );
		$wp_admin_bar->remove_menu( 'wp-logo' );
		$wp_admin_bar->remove_menu( 'about' );
		$wp_admin_bar->remove_menu( 'wporg' );
		$wp_admin_bar->remove_menu( 'query-monitor' );
		$wp_admin_bar->remove_menu( 'customize-divi-theme' );
		$wp_admin_bar->remove_menu( 'environment-notice' );
		$wp_admin_bar->remove_menu( 'et-use-visual-builder' );
		$wp_admin_bar->remove_menu( 'edit' );
		$wp_admin_bar->remove_menu( 'search' );

		//enable to see all nodes on admin toolbar
		//$nodes = $wp_admin_bar->get_nodes();
		//d($nodes);

		if ( current_user_can( 'edit_post' ) ) {
			$wp_admin_bar->add_menu( array(
				'id' => 'picksters_picks',
				'title' => __('Picks', 'picksters'),
				'href' => admin_url()
			));

			$wp_admin_bar->add_menu( array(
				'id' => 'picksters_weekly_picks',
				'title' => __('Weekly Picks', 'picksters'),
				'href' => admin_url() . "post-new.php?post_type=weekly_picks",
				'parent' => 'picksters_picks'
			));
		}
	}

	public function customize_main_navigation() {
		global $menu, $submenu;
		//ddd($menu);
		/*
		old way of unsetting menu items by index

		unset($menu[2]);
		unset($menu[10]);
		unset($menu[3]);
		*/

		// new way to unset menu items by string name
		//remove_menu_page('users.php');
		//remove_menu_page('edit.php?post_type=page');
	}




}