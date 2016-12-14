<?php
/**
 * Login Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $woocommerce,$lt_opt; ?>


<?php  wc_print_notices();  ?>

<?php do_action('woocommerce_before_customer_login_form'); ?>

<?php if (get_option('woocommerce_enable_myaccount_registration')=='yes') : ?>

<div class="row" id="customer_login">

	<div class="large-6 columns">

<?php endif; ?>

		<h2><?php esc_html_e( 'Login', 'altotheme' ); ?></h2>

		<form method="post" class="login">

			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<p class="form-row form-row-wide">
				<label for="username"><?php esc_html_e( 'Username or email address', 'altotheme' ); ?> <span class="required">*</span></label>
				<input type="text" class="input-text" name="username" id="username" />
			</p>
			<p class="form-row form-row-wide">
				<label for="password"><?php esc_html_e( 'Password', 'altotheme' ); ?> <span class="required">*</span></label>
				<input class="input-text" type="password" name="password" id="password" />
			</p>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<p class="form-row">
				<?php wp_nonce_field( 'woocommerce-login' ); ?>
				<input type="submit" class="button" name="login" value="<?php esc_html_e( 'Login', 'altotheme' ); ?>" /> 
				<a class="lost_password" href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'altotheme' ); ?></a>
				<label for="rememberme" class="inline">
					<input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <?php esc_html_e( 'Remember me', 'altotheme' ); ?>
				</label>
			</p>
		

			<?php do_action( 'woocommerce_login_form_end' ); ?>

		</form>

<?php if (get_option('woocommerce_enable_myaccount_registration')=='yes') : ?>

	</div>

	<div class="large-6 columns">

		<h2><?php esc_html_e( 'Register', 'altotheme' ); ?></h2>
		<form method="post" class="register">

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( get_option( 'woocommerce_registration_generate_username' ) === 'no' ) : ?>

				<p class="form-row form-row-wide">
					<label for="reg_username"><?php esc_html_e( 'Username', 'altotheme' ); ?> <span class="required">*</span></label>
					<input type="text" class="input-text" name="username" id="reg_username" value="<?php if ( ! empty( $_POST['username'] ) ) esc_attr ( $_POST['username'] ); ?>" />
				</p>

			<?php endif; ?>

			<p class="form-row form-row-wide">
				<label for="reg_email"><?php esc_html_e( 'Email address', 'altotheme' ); ?> <span class="required">*</span></label>
				<input type="email" class="input-text" name="email" id="reg_email" value="<?php if ( ! empty( $_POST['email'] ) ) esc_attr ( $_POST['email'] ); ?>" />
			</p>

			<p class="form-row form-row-wide">
				<label for="reg_password"><?php esc_html_e( 'Password', 'altotheme' ); ?> <span class="required">*</span></label>
				<input type="password" class="input-text" name="password" id="reg_password" value="<?php if ( ! empty( $_POST['password'] ) ) esc_attr ( $_POST['password'] ); ?>" />
			</p>

			<div style="left:-999em; position:absolute;"><label for="trap"><?php esc_html_e( 'Anti-spam', 'altotheme' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

			<?php do_action( 'woocommerce_register_form' ); ?>
			<?php do_action( 'register_form' ); ?>

			<p class="form-row">
				<?php wp_nonce_field( 'woocommerce-register' ); ?>
				<input type="submit" class="button" name="register" value="<?php esc_html_e( 'Register', 'altotheme' ); ?>" />
			</p>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>

	</div>

</div>
<?php endif; ?>

<?php do_action('woocommerce_after_customer_login_form'); ?>