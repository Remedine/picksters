<?php
/**
 * Description
 *
 * @package ExecutiveSuiteIt\Picksters\Templates;
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 */

namespace ExecutiveSuiteIt\Picksters\Templates;
?>
<div id="picksters-settings-panel">
	<h2><?php echo __('Picksters Application Settings', 'picksters');?></h2>
	<form name="picksters_settings-form" id="picksters-setting-form" method="POST">
		<div id="picksters-season-setting" class="picksters-settings-tab">
			<?php echo__('Picksters Season Settings', 'picksters'); ?>
		</div>
		<div class="picksters-season-content">
			<div id="picksters-season-setting-content" class="picksters-settings-tab-content">
				<div class="label"><?php echo __('Picksters Season', 'picksters');?></div>

			<?php /*
                    Unfinished module - refer to page 239

				<div class ="field"><input type="text" id="picksters-season-year" name="picksters-season-year" value="<?php ?>

			*/