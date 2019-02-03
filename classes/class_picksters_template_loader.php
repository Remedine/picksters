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

class Picksters_Template_Loader {
	public function get_template_part( $slug, $name = null, $load = true ) {
		do_action( 'picksters_get_template_part' . $slug, $slug, $name );
		//Setup possible parts
		$templates = array();
		if ( isset( $name ) ) {
			$templates[] = $slug . '-' . $name . '-template.php';
		}
		$templates[] = $slug . '-templates.php';
		//Allow template parts to be filtered
		$templates = apply_filters( 'picksters_get_template_part', $templates, $slug, $name );
		//Return the part that is found
		return $this->locate_template( $templates, $load = true, $require_once = true );

	}

	public function locate_template( $template_names, $load = false, $require_once = true ) {
		//No file found yet
		$located = false;
		//Travers through template files
		foreach( (array) $template_names as $template_name ) {
			//Continue if template is empty
			if( empty( $template_name ) ) {
				continue;
			}
			$template_name = ltrim( $template_name, '/' );
			//Check templates for frontend section
			if( file_exists(  picksters_plugin_dir  . 'templates/' . $template_name ) ) {
				$located =  picksters_plugin_dir  . 'templates/' . $template_name;

				//Check templates for admin section
			}
			if ( (true == $load ) && ! empty( $located ) ) {
				load_template( $located, $require_once );
				return $located;
			}
		}
	}
}