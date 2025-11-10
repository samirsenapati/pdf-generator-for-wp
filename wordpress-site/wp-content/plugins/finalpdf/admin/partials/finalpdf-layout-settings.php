<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://finaldoc.io/
 * @since      3.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}
global $finalpdf_obj, $finalpdf_wps_wpg_obj;
$finalpdf_active_tab        = isset( $_GET['finalpdf_tab'] ) ? sanitize_key( $_GET['finalpdf_tab'] ) : 'finalpdf-layout-settings'; // phpcs:ignore WordPress.Security.NonceVerification
$finalpdf_default_tabs      = $finalpdf_obj->wps_finalpdf_plug_layout_setting_sub_tabs_dummy();
$finalpdf_cover_setting_html = apply_filters( 'finalpdf_plugin_standard_admin_settings_sub_tabs_dummy', array() );
?>
<main class="wps-main wps-bg-white wps-r-8 wps_finalpdf_pro_tag">
	<nav class="wps-navbar">
		<ul class="wps-navbar__items">
			<?php
			if ( is_array( $finalpdf_default_tabs ) && ! empty( $finalpdf_default_tabs ) ) {

				foreach ( $finalpdf_default_tabs as $finalpdf_tab_key => $finalpdf_default_tabs ) {

					$finalpdf_tab_classes = 'wps-link ';
					if ( ! empty( $finalpdf_active_tab ) && $finalpdf_active_tab === $finalpdf_tab_key ) {
						$finalpdf_tab_classes .= 'active';
					} elseif ( 'finalpdf-layout-settings' === $finalpdf_active_tab ) {
						if ( 'finalpdf-cover-page-setting' === $finalpdf_tab_key ) {
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
	<section class="wps-section">
		<div>
			<form action="" method="POST" class="wps-finalpdf-gen-section-form">
				<div class="finalpdf-secion-wrap">
					<?php
					require_once FINALPDF_DIR_PATH . 'admin/partials/finalpdf-layout-table.php';
					wp_nonce_field( 'nonce_settings_save', 'finalpdf_nonce_field' );
					$finalpdf_obj->wps_finalpdf_plug_generate_html( $finalpdf_cover_setting_html );
					?>
				</div>
			</form>
		</div>
	</section>
