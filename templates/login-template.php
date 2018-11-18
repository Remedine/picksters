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

global $picksters_login_params;

if ( is_array( $picksters_login_params ) ) {
	extract( $picksters_login_params );
}

get_header(); ?>

<div id='picksters_custom_panel'>
    <div class='picksters-login-form-header'><?php echo __( 'Login', 'picksters' ); ?> </div>
    <div id='picksters-login-errors'>
		<?php
		if ( isset( $picksters_login_params['errors'] ) && count( $picksters_login_params['errors'] ) > 0 ) {
			foreach ( $picksters_login_params['errors'] as $error ) {
				echo '<p class="picksters_frm_error">' . $error . '</p>';
			}
		}
		if ( isset ( $picksters_login_params['success_message'] ) && $picksters_login_params['success_message'] != "" ) {
			echo '<p class="picksters_frm_success">' . $picksters_login_params['success_message'] . '</p>';
		}
		?>
    </div>
    <form method='post' action='<?php echo site_url(); ?>/user/login' id="picksters_login_form"
          name='picksters_login_form'>
        <ul>
            <li>
                <label class='picksters_frm_label' for='username'><?php echo __( 'Username', 'picksters' ) ?></label>
                <input class='picksters_frm_field' type='text' name='picksters_username'
                value='<?php echo isset( $username ) ? $username : $username = ''; ?>' />
            </li>
            <li>
                <label class='picksters_frm_label' for='password'><?php echo __( 'Password', 'picksters' ) ?></label>
                <input class='picksters_frm_field' type='password' name='picksters_password'
                value="" />
            </li>
            <li>
                <label class='picksters_frm_label'>&nbsp;</label>
                <input type='submit' name='picksters_login_submit' value='<?php echo __('Login', 'picksters'); ?>' />
            </li>
        </ul>
    </form>
</div>

<?php get_footer(); ?>

