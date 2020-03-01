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

<div>
	<form action="<?php echo get_site_url() . '/user/picks'; ?>" method="post" id="user_input_chooser_button">
		<input name="user_input" type="hidden" value="true">
		<button>Click here to get this week's game choices.</button>
	</form>
    <p class="report-a-bug-response"></p>
</div>