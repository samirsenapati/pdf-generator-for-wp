<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://finaldoc.io/
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $finalpdf_obj;
$finalpdf_advanced_settings = apply_filters( 'finalpdf_advanced_settings_array', array() );
?>
<!--  advanced file for admin settings. -->
<form action="" method="POST" class="wps-finalpdf-gen-section-form" enctype="multipart/form-data">
	<div class="finalpdf-section-wrap">
		<?php
		wp_nonce_field( 'nonce_settings_save', 'finalpdf_nonce_field' );
		$finalpdf_obj->wps_finalpdf_plug_generate_html( $finalpdf_advanced_settings );
		?>
	</div>
</form>
