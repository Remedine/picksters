<?php
/**
 * Description.
 *
 * @package  ExecutiveSuiteIt\Picksters\Templates
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 */

namespace  ExecutiveSuiteIt\Picksters\Templates;

global $wp_roles, $picksters, $topic_restrictions_params;
extract ($topic_restrictions_params);

$visibility = get_post_meta( $post->ID, '_picksters_pick_visibility', true );
$redirection_url = get_post_meta( $post->ID, '_picksters_pick_redirection_url', true);

?>
<div class="picksters_pick_meta_row">
	<div class="picksters_pick_meta_row_label"><strong><?php _e('Visibility','picksters'); ?></strong></div>
	<div class="picksters_pick_meta_row_field">
		<select id="picksters_pick_visibility" name="picksters_pick_visibility" >
			<option value="none" <?php selected('none, $visibility'); ?>>
				<?php _e('Please Select', 'picksters'); ?>
			</option>
			<option value="all" <?php selected('all', $visibility); ?> >
				<?php _e('Everyone', 'picksters'); ?>
			</option>
			<option value="guest" <?php selected('guest', $visibility); ?> >
				<?php _e('Guest', 'picksters'); ?>
			</option>
			<option value="pickster" <?php selected('pickster', $visibility); ?> >
				<?php _e('Pickster', 'picksters'); ?>
			</option>
		</select>
	</div>
	<div class="picksters_pick_meta_row">
		<div class="picksters_pick_meta_row_label"><strong><?php _e('Redirection Url', 'picksters'); ?></strong></div>
		<div class="picksters_pick_meta_row_field">
			<input type='text' id='picksters_pick_redirection_url' name="picksters_pick_redirection_url" value="<?php echo $redirection_url; ?>" />
		</div>
	</div>
	<?php wp_nonce_field( 'picksters_restriction_settings', 'picksters_restriction_settings_nonce' ); ?>
</div>
