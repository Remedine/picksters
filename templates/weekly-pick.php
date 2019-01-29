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

global $picksters_weekly_picks_params;

if ( is_array( $picksters_weekly_picks_params ) ) {
    extract($picksters_weekly_picks_params);
}


get_header(); ?>

    <div class="picksters-weekly-picks">
		<?php echo __( 'Make Your Picks | Choose Wisely!', 'picksters' ); ?>
    </div>
    <div id="picksters-weekly-picks-errors">
		<?php if ( isset( $picksters_weekly_picks_params['errors'] ) && count( $picksters_weekly_picks_params['errors'] ) > 0 ) {
			foreach ( $picksters_weekly_picks_params['errors'] as $error ) {
				echo '<p class="picksters_frm_error">' . $error . '</p>';
			}
		}
		?>
    </div>
    <form id="picksters_weekly_picks" method="post" action="<?php echo get_site_url() . '/user/picks'; ?>">
        <ul>
			<?php

			for ( $i = 0; $i <= 19; $i ++ ) {
                $how_many_games += 1;
				if ( $week_games_array[0][ $i ]['home_team'] == null ) {

					break;
				}

				?>

                <li>
                    <label class='game_choice_label'>Game #<?php echo $how_many_games ?></label>
                    <input class='picksters_radio_field' type='radio' id="picksters_radio_game<?php echo $i ?>"
                           name='game<?php echo $how_many_games?>'
                           value="<?php echo $week_games_array[0][ $i ]['home_team'] ?>"/>
					<?php echo $week_games_array[0][ $i ]['home_team'] ?> <br>
                    <input class='picksters_radio_field' type='radio' id="picksters_radio_game<?php echo $how_many_games ?>"
                           name="game<?php echo $how_many_games ?>"
                           value="<?php echo $week_games_array[0][ $i ]['away_team'] ?>"/>
					<?php echo $week_games_array[0][ $i ]['away_team'] ?>
                    <input type="hidden" name="how_many_games" value="<?php echo $how_many_games ?>" />
                </li>
                <li>
                </li>
				<?php

			}
			?>
        </ul>


        <input type='submit' name='picksters_picks_submit' value='<?php echo __( 'Submit Picks', 'picksters' ); ?>'/>
    </form>

	<?php get_footer(); ?>