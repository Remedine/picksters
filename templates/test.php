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



        for( $bob = 0; $bob <=15; $bob++ ) {

	        echo 'Visiting Team:               ' . $json_data['gameScores'][ $bob ]['gameSchedule']['visitorDisplayName'] . ' ..... SCORE:    ' .
	             $json_data['gameScores'][ $bob ]['score']['visitorTeamScore']['pointTotal'] . '  VS  ' . '   Home Team:    ' .
	             $json_data['gameScores'][ $bob ]['gameSchedule']['homeDisplayName'] . ' ' . ' ..... SCORE:    ' .
	             $json_data['gameScores'][ $bob ]['score']['homeTeamScore']['pointTotal'] . '<br>';
        }
	?>
</div>
