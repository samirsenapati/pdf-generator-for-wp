<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://finaldoc.io/
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}
$secure_nonce      = wp_create_nonce( 'wps-finalpdf-auth-nonce' );
$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-finalpdf-auth-nonce' );
if ( ! $id_nonce_verified ) {
		wp_die( esc_html__( 'Nonce Not verified', 'finalpdf' ) );
}
global $finalpdf_obj;

$finalpdf_active_tab              = isset( $_GET['finalpdf_tab'] ) ? sanitize_key( $_GET['finalpdf_tab'] ) : 'finalpdf-customize';
$finalpdf_default_tabs            = $finalpdf_obj->wps_finalpdf_plug_default_sub_tabs();
$finalpdf_settings_display_fields = apply_filters( 'finalpdf_display_settings_array', array() );
?>
<main class="wps-main wps-bg-white wps-r-8">
	<nav class="wps-navbar">
		<ul class="wps-navbar__items">
			<?php
			if ( is_array( $finalpdf_default_tabs ) && ! empty( $finalpdf_default_tabs ) ) {
				foreach ( $finalpdf_default_tabs as $finalpdf_tab_key => $finalpdf_default_tabs ) {
					$finalpdf_tab_classes = 'wps-link ';
					if ( ! empty( $finalpdf_active_tab ) && $finalpdf_active_tab === $finalpdf_tab_key ) {
						$finalpdf_tab_classes .= 'active';
					} elseif ( 'finalpdf-pdf-setting' === $finalpdf_active_tab ) {
						if ( 'finalpdf-pdf-icon-setting' === $finalpdf_tab_key ) {
							$finalpdf_tab_classes .= 'active';
						}
					}
					?>
					<li>
						<a id="<?php echo esc_attr( $finalpdf_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=finalpdf_menu' ) . '&finalpdf_tab=' . esc_attr( $finalpdf_tab_key ) ); ?>" class="<?php echo esc_attr( $finalpdf_tab_classes ); ?>"><?php echo esc_html( $finalpdf_default_tabs['title'] ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>
	<!--  template file for admin settings. -->
<section class="wps-section">
	<form action="" method="POST" class="wps-finalpdf-gen-section-form">
		<div class="finalpdf-section-wrap">
			<?php
			wp_nonce_field( 'nonce_settings_save', 'finalpdf_nonce_field' );
			$finalpdf_obj->wps_finalpdf_plug_generate_html( $finalpdf_settings_display_fields );
			?>
			<div>
				<?php
				/* translators: shortcode name. */
				printf( esc_html__( 'Add %s shortcode anywhere on your page or posts to display PDF generating icon.', 'finalpdf' ), '[WORDPRESS_PDF]' );
				?>
			</div>
		</div>
	</form>
</section>
