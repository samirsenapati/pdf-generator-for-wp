<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://finaldoc.com/
 * @since 1.0.0
 *
 * @package    finalpdf
 * @subpackage finalpdf/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly.
}
global $finalpdf_obj, $wps_finalpdf_gen_flag, $finalpdf_save_check_flag;
$finalpdf_default_tabs = $finalpdf_obj->wps_finalpdf_plug_default_tabs();
$finalpdf_tab_key = '';
?>
<header>
	<?php
	// desc - This hook is used for trial.
	do_action( 'wps_finalpdf_settings_saved_notice' );
	?>
	<div class="wps-header-container wps-bg-white wps-r-8">
		<h1 class="wps-header-title"><?php echo esc_attr( 'FinalDoc' ); ?></h1>
	</div>
</header>
<main class="wps-main wps-bg-white wps-r-8">
	<section class="wps-section">
		<div>
			<?php
				// desc - This hook is used for trial.
			do_action( 'admin_init' );
				// if submenu is directly clicked on woocommerce.
			$finalpdf_genaral_settings = apply_filters(
				'finalpdf_home_settings_array',
				array(

					array(
						'title'       => __( 'Enable Tracking', 'finalpdf' ),
						'type'        => 'radio-switch',
						'name'        => 'finalpdf_enable_tracking',
						'id'          => 'finalpdf_enable_tracking',
						'value'       => get_option( 'finalpdf_enable_tracking' ),
						'class'       => 'pgfw-radio-switch-class',
						'options'     => array(
							'yes' => __( 'YES', 'finalpdf' ),
							'no'  => __( 'NO', 'finalpdf' ),
						),
					),
					array(
						'type'  => 'button',
						'id'    => 'finalpdf_tracking_save_button',
						'button_text' => __( 'Save', 'finalpdf' ),
						'class' => 'pgfw-button-class',
					),
				)
			);
			?>
			<form action="" method="POST" class="wps-finalpdf-gen-section-form">
				<div class="finalpdf-secion-wrap">
					<?php
					$finalpdf_general_html = $finalpdf_obj->wps_finalpdf_plug_generate_html( $finalpdf_genaral_settings );
					echo esc_html( $finalpdf_general_html );

					?>
					<input type="hidden" id="updatenonce" name="updatenonce" value="<?php echo esc_attr( wp_create_nonce() ); ?>" />
	
				</div>
			</form>
			<?php
			do_action( 'admin_init' );
			$all_plugins = get_plugins();
			?>
		</div>
	</section>
	<style type="text/css">
		.cards {
			   display: flex;
			   flex-wrap: wrap;
			   padding: 20px 40px;
		}
		.card {
			flex: 1 0 518px;
			box-sizing: border-box;
			margin: 1rem 3.25em;
			text-align: center;
		}

	</style>
	<div class="centered">
		<section class="cards">
			<?php foreach ( get_plugins() as $key => $value ) : ?>
				<?php if ( 'FinalDoc' === $value['Author'] ) : ?>
					<article class="card">
						<div class="container">
							<h4><b><?php echo wp_kses_post( $value['Name'] ); ?></b></h4> 
							<p><?php echo wp_kses_post( $value['Version'] ); ?></p> 
							<p><?php echo wp_kses_post( $value['Description'] ); ?></p>
						</div>
					</article>
				<?php endif; ?>
			<?php endforeach; ?>
		</section>
	</div>
