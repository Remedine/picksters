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

<div class="week_chooser" id="choose_week" style="display:none">
    <form class="ajax_choose_week" action="<?php echo get_site_url() . '/user/picks'; ?>" method="post" id="week_chooser_form">

        <label for="week">Choose a week:</label>
        <select id="pre_week" class="week" name="pre_week" form="week_chooser_form">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select>

        <select id="reg_week" class="week" name="reg_week" form="week_chooser_form">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
        </select>

        <select id="post_week" class="week" name="post_week" form="week_chooser_form">
            <option value="18">Wildcard Game</option>
            <option value="19">Divisional Game</option>
            <option value="20">Championship Game</option>
            <option value="22">Superbowl Game</option>
        </select>

        <input type='submit' class="submit" name='picksters_week_submit' value='<?php echo __( 'Submit Week', 'picksters' ); ?>'/>

    </form>
    <p class="report-a-bug-response"></p>
</div>