<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for PDF upload tab.
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
$finalpdf_pdf_upload_settings = apply_filters( 'finalpdf_pdf_upload_fields_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="wps-finalpdf-gen-section-form">
	<div class="finalpdf-section-wrap">
		<div class="finalpdf-upload-poster-notification"><?php esc_html_e( 'Upload posters from here and you will get shortcode which you can use anywhere on your post or page to give access to download these posters.', 'finalpdf' ); ?></div>
		<?php
		wp_nonce_field( 'nonce_settings_save', 'finalpdf_nonce_field' );
		$finalpdf_obj->wps_finalpdf_plug_generate_html( $finalpdf_pdf_upload_settings );
		$finalpdf_pdf_upload_settings = get_option( 'finalpdf_pdf_upload_save_settings', array() );
		$finalpdf_poster_doc          = array_key_exists( 'sub_pgfw_poster_image_upload', $finalpdf_pdf_upload_settings ) ? $finalpdf_pdf_upload_settings['sub_pgfw_poster_image_upload'] : '';
		// poster images names and shortcodes.
		if ( '' !== $finalpdf_poster_doc ) {
			$finalpdf_poster_image = json_decode( $finalpdf_poster_doc, true );
			if ( is_array( $finalpdf_poster_image ) && count( $finalpdf_poster_image ) > 0 ) {
				?>
				<div><?php esc_html_e( 'You can add these shortcodes to download posters anywhere on the page/post.', 'finalpdf' ); ?></div>
				<br/>
				<table id="finalpdf_poster_shortcode_listing_table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'File Name', 'finalpdf' ); ?></th>
							<th><?php esc_html_e( 'ShortCode', 'finalpdf' ); ?></th>
							<th><?php esc_html_e( 'Action', 'finalpdf' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $finalpdf_poster_image as $media_id ) {
							$media_title = get_the_title( $media_id );
							if ( $media_title ) {
								?>
								<tr>
									<td>
										<?php echo esc_html( $media_title ); ?>
									</td>
									<td>
										<?php echo esc_html( '[PGFW_DOWNLOAD_POSTER id=' . $media_id . ']' ); ?>
									</td>
									<td>
										<button data-media-id="<?php echo esc_html( $media_id ); ?>" class="finalpdf-delete-poster-form-table"><?php esc_html_e( 'Delete', 'finalpdf' ); ?></button>
									</td>
								</tr>
								<?php
							}
						}
						?>
					</tbody>
				</table>
				<?php
			}
		}
		?>
	</div>
</form>
