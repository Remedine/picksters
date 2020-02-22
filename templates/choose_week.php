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

?>

<div class="week_chooser">
    <form action="<?php echo get_site_url() . '/user/picks'; ?>" method="post" id="week_chooser_form">

        <label for="week">Choose a week:</label>
        <select id="week" name="week" form="week_chooser_form">
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

        <input type='submit' name='picksters_week_submit' value='<?php echo __( 'Submit Picks', 'picksters' ); ?>'/>

    </form>
</div>