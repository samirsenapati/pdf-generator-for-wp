<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://yourwebsite.com/
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $finalpdf_obj;
$finalpdf_meta_settings = apply_filters( 'finalpdf_meta_fields_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="wps-finalpdf-gen-section-form">
	<div class="finalpdf-section-wrap">
		<?php
		wp_nonce_field( 'nonce_settings_save', 'finalpdf_nonce_field' );
		$finalpdf_obj->wps_finalpdf_plug_generate_html( $finalpdf_meta_settings );
		do_action( 'wps_finalpdf_below_meta_fields_setting_form' );
		?>
	</div>
</form>
