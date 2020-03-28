<?php
/**
 * Description.
 *
 * @package ExecutiveSuiteIt\Picksters\Templates;
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 */

namespace ExecutiveSuiteIt\Picksters\Templates;

//get_header();
?>

<div class="season_type_chooser">
	<form class="ajax_choose_week" method="post" id="season_type_chooser_form">

		<label for="season_type">Choose a season_type:</label>
		<select id="season_type" name="season_type" form="season_type_chooser_form">
			<option value="PRE">Pre Season</option>
			<option value="REG">Regular Season</option>
			<option value="POST">Post Season</option>
		</select>

        <input type="hidden" name="action" value="season_form"/>
	<!--	<input type='submit' id="season_type_chooser_button" name='picksters_season_type_submit' value='<?php echo __( 'Submit Season Type', 'picksters' ); ?>'/>
-->
	</form>
    <p class="report-a-bug-response"></p>
</div>
<div id="season_response_container"></div>

<?php //get_footer();?>