<?php
/**
 * Description.
 *
 * @package ExecutiveSuiteIt\Picksters\Templates
 * @Since 1.0.0
 * @author Timothy Hill
 * @link https://executivesuiteit.com
 * @license
 */

namespace ExecutiveSuiteIt\Picksters\Templates;

global $picksters_registration_params;

if ( is_array( $picksters_registration_params ) ) {
	extract( $picksters_registration_params );
}

get_header(); ?>

<div class="picksters-registration-form-header">
	<?php echo __( 'Register New Account', 'picksters' ); ?>
</div>
<div id="picksters-registration-errors">
	<?php if ( isset( $picksters_registration_params['errors'] ) && count( $picksters_registration_params['errors'] ) > 0 ) {
		foreach ( $picksters_registration_params['errors'] as $error ) {
			echo '<p class="picksters_frm_error">' . $error . '</p>';
		}
	}
	?>
</div>
<form id="picksters-registration-form" method="post" action="<?php echo get_site_url() . '/user/register'; ?>">
    <ul>
        <li>
            <label class="picksters_frm_label">
				<?php echo __( 'Username', 'picksters' ); ?>
            </label>
            <input class="picksters_frm_field" type="text" id="picksters_user" name="picksters_user" value="<?php echo isset( $user_login ) ? $user_login : ''; ?>" />
        </li>
        <li>
            <label class="picksters_frm_label">
                <?php echo __('E-mail', 'picksters'); ?>
            </label>
            <input class="picksters_frm_field" type="email" id="picksters_email" name="picksters_email" value="<?php echo isset( $user_email ) ? $user_email : ''; ?>" />
        </li>
        <li>
            <label class="picksters_frm_label">
                <?php echo __('UserType', 'picksters') ?>
            </label>
            <select class="picksters_frm_field" name="picksters_user_type">
                <option <?php echo (isset( $user_type ) && $user_type=='picksters_admin' ) ? 'selected' : ''; ?> value="picksters_admin"><?php echo __('Admin', 'picksters'); ?></option>
                <option <?php echo (isset( $user_type ) && $user_type=='picksters_moderator' ) ? 'selected' : ''; ?> value="picksters_moderator"><?php echo __('Moderator', 'picksters'); ?></option>
                <option <?php echo (isset( $user_type ) && $user_type=='picksters_pickster' ) ? 'selected' : ''; ?> value="picksters_pickster"><?php echo __('Pickster', 'picksters'); ?></option>
            </select>
        </li>
        <li>
            <label class="picksters_frm_label" for="">&nbsp;</label>
            <input type="submit" name="picksters_reg_submit" value="<?php echo __('Register', 'picksters'); ?>" />
        </li>
    </ul>
</form>
</div>
<?php get_footer(); ?>
