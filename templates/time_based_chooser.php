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
get_header();
?>

<div>
    <form class="ajax_choose_week" action="<?php echo get_site_url() . '/user/picks'; ?>" method="post" id="time_based_chooser_button">
        <input name="time_based" type="hidden" value="true">
        <input type='submit' name='time_based_submit' value='<?php echo __( 'Click here to choose your week.', 'picksters' ); ?>'/>
    </form>
    <p class="report-a-bug-response"></p>
</div>