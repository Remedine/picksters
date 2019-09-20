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

namespace ExecutiveSuiteIt\Picksters\Templates;

get_header(); ?>

<div id="hi">


    <?php


        for( $i = 0; $i <= 19; $i++ ) {

	        if( $json_data['gameScores'][ $i ]['gameSchedule']['visitorDisplayName'] == null ) { break; };




            echo 'Visiting Team:               ' . $json_data['gameScores'][ $i ]['gameSchedule']['visitorDisplayName'] . ' ..... SCORE:    ' .
                 $json_data['gameScores'][ $i ]['score']['visitorTeamScore']['pointTotal'] . '  VS  ' . '   Home Team:    ' .
                 $json_data['gameScores'][ $i ]['gameSchedule']['homeDisplayName'] . ' ' . ' ..... SCORE:    ' .
                 $json_data['gameScores'][ $i ]['score']['homeTeamScore']['pointTotal'] . '<br>'
                . $game_id = $json_data['gameScores'][ $i ]['gameSchedule']['gameId'];
        }


if ( current_user_can( 'administrator' ) ) {
    global $wpdb;
    //echo "<pre>";
    //print_r( $wpdb->queries );
    //echo "</pre>";
}


	?>
</div>
