<?php
/**
 * The template file for the weekly-picks meta box for the weekly picks custom post type.
 *
 * @package namespace ExecutiveSuiteIt\Picksters\Templates;
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 */

namespace ExecutiveSuiteIt\Picksters\Templates;

global $picksters_weekly_picks_params;

if ( is_array( $picksters_weekly_picks_params ) ) {
	extract( $picksters_weekly_picks_params );
}

$picked_games_array = get_transient( $this->post_type . "_games_$post->ID");
delete_transient( $this->post_type . "_games_$post->ID" );
?>

<input type="hidden" name="weekly-picks-meta-nonce" value="<?php echo $picksters_weekly_picks_params['weekly_picks_meta_nonce']; ?>"/>

<table class="form-table">
	<?php
	for ( $i = 0; $i <= 19; $i ++ ) {
		$how_many_games += 1;
    ?>
		<input type="hidden" name="how_many_games" value="<?php echo $how_many_games -1; ?>"/>
	<?php
		if ( $week_games_array[0][ $i ]['home_team'] == null ) {

			break;
		}
		?>
        <tr>
            <th style=''>
                <label class='game_choice_label'>Game # <?php echo $how_many_games ?></label>
            </th>
            <td>
                <input class='picksters_radio_field' type='radio' id="picksters_radio_game<?php echo $i ?> "
                       name='game<?php echo $how_many_games?>'
                       value="<?php echo $week_games_array[0][ $i ]['home_team'];?>"
                       <?php
                       $ii = $i +1;
                       if( ! empty ($picked_games_array['game' . $ii ]) && $picked_games_array['game' . $ii ] == $week_games_array[0][ $i ]['home_team']) {
                           echo 'checked="checked"';
                       }
                       ?>
                />
	            <?php echo $week_games_array[0][ $i ]['home_team'] ?>
                <input class='picksters_radio_field' type='radio' id="picksters_radio_game<?php echo $how_many_games ?>"
                       name="game<?php echo $how_many_games ?>"
                       value="<?php echo $week_games_array[0][ $i ]['away_team'];?>"
                       <?php
                       $ii = $i +1;
                       if( ! empty ($picked_games_array['game' . $ii ]) && $picked_games_array['game' . $ii ] == $week_games_array[0][ $i ]['away_team']) {
                           echo 'checked="checked"';
                       }
                       ?>
                />
	            <?php echo $week_games_array[0][ $i ]['away_team'] ?>
            </td>
        </tr>
	<?php } ?>
</table>
