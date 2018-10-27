<?php
/**
 * Picksters Plugin
 *
 * @package     Digitalseeds\Picksters\Support
 * @author      hellofromTonya
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Picksters
 * Plugin URI:  https://digitalseeds.marketing/picksters
 * Description: An NFL weekly picks app.
 * Version:     1.0.0
 * Author:      Timothy Hill
 * Author URI:  https://digitalseeds.marketing
 * Text Domain: Picksters
 * License:
 * License URI:
 */

namespace Digitalseeds\Picksters\Support;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\load_assets' );
/**
 * Load assets.
 *
 * @since 1.0.0
 *
 * @return void
 */
function load_assets() {
	wp_enqueue_script( 'sandbox_workspace_script', SANDBOX_WORKSPACE_URL . 'assets/js/my_script.js' );
}