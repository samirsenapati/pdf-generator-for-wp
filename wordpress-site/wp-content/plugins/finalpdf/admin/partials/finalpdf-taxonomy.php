<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://finaldoc.io/
 * @since      3.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $finalpdf_obj;
$finalpdf_taxonomy_settings_arr = apply_filters( 'finalpdf_taxonomy_settings_array_dummy', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="wps-wpg-gen-section-form">
	<div class="wpg-secion-wrap">
		<?php
				wp_nonce_field( 'nonce_settings_save', 'finalpdf_nonce_field' );
				$finalpdf_obj->wps_finalpdf_plug_generate_html( $finalpdf_taxonomy_settings_arr );
		?>
	</div>
</form>
