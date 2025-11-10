<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $finalpdf_obj, $finalpdf_wps_wpg_obj;
$finalpdf_template_pdf_settings = apply_filters( 'finalpdf_template_invoice_settings_array_dummy', array() );
?>
<!--  template file for admin settings. -->
<div class="wpg-section-wrap">
	<form action="" method="post">
		<?php
			wp_nonce_field( 'nonce_settings_save', 'finalpdf_nonce_field' );
			$finalpdf_obj->wps_finalpdf_plug_generate_html( $finalpdf_template_pdf_settings );
		?>
	</form>
	<div>
		<?php do_action( 'mwb_wpg_invoice_settings_after_form' ); ?>
	</div>
</div>
