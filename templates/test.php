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



        for( $bob = 0; $bob <=19; $bob++ ) {

	        if( $json_data['gameScores'][ $bob ]['gameSchedule']['visitorDisplayName'] == null ) { break; };

            echo 'Visiting Team:               ' . $json_data['gameScores'][ $bob ]['gameSchedule']['visitorDisplayName'] . ' ..... SCORE:    ' .
	             $json_data['gameScores'][ $bob ]['score']['visitorTeamScore']['pointTotal'] . '  VS  ' . '   Home Team:    ' .
	             $json_data['gameScores'][ $bob ]['gameSchedule']['homeDisplayName'] . ' ' . ' ..... SCORE:    ' .
	             $json_data['gameScores'][ $bob ]['score']['homeTeamScore']['pointTotal'] . '<br>';
        }

    for( $bob = 0; $bob <=19; $bob++ ) {

	    if( $json_1['gameScores'][ $bob ]['gameSchedule']['visitorDisplayName'] == null ) { break; };

	    echo '<br>Visiting Team:               ' . $json_1['gameScores'][ $bob ]['gameSchedule']['visitorDisplayName'] . ' ..... SCORE:    ' .
	         $json_1['gameScores'][ $bob ]['score']['visitorTeamScore']['pointTotal'] . '  VS  ' . '   Home Team:    ' .
	         $json_1['gameScores'][ $bob ]['gameSchedule']['homeDisplayName'] . ' ' . ' ..... SCORE:    ' .
	         $json_1['gameScores'][ $bob ]['score']['homeTeamScore']['pointTotal'] . '<br>';
    }



	?>
</div>
