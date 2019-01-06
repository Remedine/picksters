<?php
/**
 * Description.
 *
 * @package ExecutiveSuiteIt\Picksters\Templates
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 */

namespace ExecutiveSuiteIt\Picksters\Templates;

global $picksters_weekly_games;


get_header(); ?>

    <div class="picksters-weekly-picks">
		<?php echo __( 'Choose wisely', 'picksters' ); ?>
    </div>
    <div id="picksters-weekly-picks-errors">
		<?php if ( isset( $picksters_weekly_games['errors'] ) && count( $picksters_weekly_games['errors'] ) > 0 ) {
			foreach ( $picksters_weekly_games['errors'] as $error ) {
				echo '<p class="picksters_frm_error">' . $error . '</p>';
			}
		}
		?>
    </div>
    <form id="picksters_weekly_picks" method="post" action="<?php echo get_site_url() . '/user/picks'; ?>">
        <ul>
			<?php
			$counter = 1;

			foreach ( $picksters_weekly_games as $game ) {
                ?>

                <li>
                <label class='game_choice_label'>Game</label>
               <input class='picksters_radio_field' type='radio' id="picksters_radio_game<?php echo $counter ?>"
                           name='game<?php echo $counter ?>' value="<?php echo $game[ home_team_name ] ?>"/>
                    <input class='picksters_radio_field' type='radio' id="picksters_radio_game<?php echo $counter ?>"
                           name="game<?php echo $counter ?>" value="<?php echo $game[ away_team_name ] ?>"/>
                </li>
				<li>
                  <?php
				echo "Game #" . $counter;
				$counter += $counter;
				?>
         	 </li>
      		<?php

			}
			?>
        </ul>
        <input type='submit' name='picksters_picks_submit' value='<?php echo __( 'Submit Picks', 'picksters' ); ?>'/>
    </form>

	<?php get_footer(); ?>