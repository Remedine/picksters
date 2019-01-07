<?php
/**
 * Description.
 *
 * @package ${namespace}
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 */

namespace ExecutiveSuiteIt\Picksters\Templates;

get_header(); ?>

<div>
	<?php
	foreach ($json_data as $data) {
	?>

	<br>
	<?php
		print_r($data);
	?><br><?php
	}; ?>
</div>
