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


class Picksters_Picks_Query extends WP_Query {
	function __construct( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'post_type' => 'weekly_picks',
			'meta_query' => array(
				array(
					'key' => 'week_number'
				)
			)
		));
		parent::__construct( $args );
	}
}