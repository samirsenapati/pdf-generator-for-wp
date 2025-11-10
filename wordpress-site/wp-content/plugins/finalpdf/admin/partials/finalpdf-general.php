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
$finalpdf_genaral_settings = apply_filters( 'finalpdf_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="wps-finalpdf-gen-section-form">
	<div class="finalpdf-secion-wrap">
		<?php
		wp_nonce_field( 'nonce_settings_save', 'finalpdf_nonce_field' );
		$finalpdf_obj->wps_finalpdf_plug_generate_html( $finalpdf_genaral_settings );
		?>
	</div>
</form>
