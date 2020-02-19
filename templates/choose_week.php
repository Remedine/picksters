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
<div class="week_chooser">
    <form action="<?php echo get_site_url() . '/user/picks'; ?>" method="post" id="week_chooser_form">
        Name: <input type="text" name="name"><br>
        E-mail: <input type="text" name="email"><br>
        <input type="submit">
    </form>
</div>