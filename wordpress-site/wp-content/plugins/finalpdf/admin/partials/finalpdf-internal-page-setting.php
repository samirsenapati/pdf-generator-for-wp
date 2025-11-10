<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://yourwebsite.com/
 * @since      3.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $finalpdf_obj, $finalpdf_wps_wpg_obj;
$finalpdf_active_tab           = isset( $_GET['finalpdf_tab'] ) ? sanitize_key( $_GET['finalpdf_tab'] ) : 'finalpdf-layout-settings'; // phpcs:ignore WordPress.Security.NonceVerification
$finalpdf_default_tabs      = $finalpdf_obj->wps_finalpdf_plug_layout_setting_sub_tabs_dummy();

$finalpdf_template_settings_arr = apply_filters( 'finalpdf_tamplates_settings_array', array() );
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
	<div>
		<form action="" method="POST" class="wps-finalpdf-gen-section-form">
			<div class="wpg-admin-notice-custom"></div>
			<div class="finalpdf-secion-wrap">
				<?php
				$custom_template_data             = get_option( 'finalpdf_custom_templates_list', array() );
				$finalpdf_use_template_to_generate_pdf = get_option( 'finalpdf_use_template_to_generate_pdf' );
				$preview_output_href              = add_query_arg(
					array(
						'action'   => 'previewpdf',
						'template' => 'template1',
					),
					get_site_url()
				);
				?>
				<div class="wpg-adding-notice-for-custom-template">
					<?php
					esc_html_e(
						'To add custom template just click on the Create Templates icon, this will add three page each for header, body and footer with default layouts you can edit them by clicking on the edit link, it will redirect you to the editor, you can add snippets from PDF Snippets block, which is provided in the block section of the gutenberg editor and choose accordingly.',
						'finalpdf'
					);
					?>
				</div>
				<div class="wpg-adding-notice-for-custom-template">
					<?php
					esc_html_e(
						'To add post thumbnail on the PDF, just click on the body editing link, it will redirect you to editor, there you can add any image of desired size, that image will be replaced by the post thumbnail of the size you have just selected image.',
						'finalpdf'
					);
					?>
				</div>
				<div class="wpg-adding-notice-for-custom-template">
					<?php
					esc_html_e(
						'To set the content on the page, If you see any Issue with top placement of header, just visit PDF Settings/Header tab, from that setting page change the value of Header top placement, If you see any issue with the footer placement just visit PDF Settings/Footer tab change the value of Footer bottom placement, Also these changes need to be done in synchronisation with the setting at the PDF setting/ Body page for Page Margin, These will set the content on the PDF.',
						'finalpdf'
					);
					?>
				</div>
				<span class="wpg-add-custom-page-insertion"><?php esc_html_e( 'Create Templates', 'finalpdf' ); ?></span>
				<table class="wps-layout-table-bottom">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Name', 'finalpdf' ); ?></th>
							<th><?php esc_html_e( 'For', 'finalpdf' ); ?></th>
							<th><?php esc_html_e( 'Status', 'finalpdf' ); ?></th>
							<th><?php esc_html_e( 'Updated', 'finalpdf' ); ?></th>
							<th><?php esc_html_e( 'Preview', 'finalpdf' ); ?></th>
							<th><?php esc_html_e( 'Template Editing Link', 'finalpdf' ); ?></th>
							<th><?php esc_html_e( 'Delete', 'finalpdf' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php esc_html_e( 'Template 1', 'finalpdf' ); ?></td>
							<td><?php esc_html_e( 'All Posts', 'finalpdf' ); ?></td>
							<td>
								<input type="radio" name="finalpdf_use_template_current_status" class="finalpdf_use_template_current_status" value="template1" <?php checked( $finalpdf_use_template_to_generate_pdf, 'template1' ); ?>>
								<span><?php esc_html_e( 'Activate', 'finalpdf' ); ?></span>
							</td>
							<td>
								<?php echo esc_html( get_option( 'date_format' ) ); ?>
							</td>
							<td>
								<a href="<?php echo esc_attr( $preview_output_href ); ?>" target="_blank"><?php esc_html_e( 'Preview', 'finalpdf' ); ?></a>
							</td>
							<td>
								<span><?php esc_html_e( 'Not Found', 'finalpdf' ); ?><span>
							</td>
							<td>
								<span><?php esc_html_e( 'not allowed', 'finalpdf' ); ?></span>
							</td>
						</tr>
						<?php
						$translated_pdf_parts = array(
							'header' => __( 'Header', 'finalpdf' ),
							'body'   => __( 'Body', 'finalpdf' ),
							'footer' => __( 'Footer', 'finalpdf' ),
						);
						foreach ( $custom_template_data as $template => $template_description ) {
							$preview_output_href = add_query_arg(
								array(
									'action'   => 'previewpdf',
									'template' => $template,
								),
								get_site_url()
							);
							$i                   = 0;
							foreach ( $template_description as $template_for => $template_id ) {
								$availability = get_post( $template_id );
								$edit_link    = get_edit_post_link( $availability );
								?>
								<tr>
									<?php if ( 0 === $i ) { ?>
										<td rowspan="3"><?php echo esc_html( str_replace( 'customtemplate', __( 'Custom Template ', 'finalpdf' ), $template ) ); ?></td>
										<td rowspan="3">
											<span><?php esc_html_e( 'All Posts', 'finalpdf' ); ?></span>
										</td>
										<td rowspan="3">
											<input type="radio" name="finalpdf_use_template_current_status" class="finalpdf_use_template_current_status" value="<?php echo esc_html( $template ); ?>" <?php checked( $finalpdf_use_template_to_generate_pdf, $template ); ?>>
											<span><?php esc_html_e( 'Activate', 'finalpdf' ); ?></span>
										</td>
										<td rowspan="3"><?php echo esc_html( ( $availability ) ? get_the_modified_date( '', $template_id ) : __( 'Not Found', 'finalpdf' ) ); ?></td>
									<?php } ?>
									<?php if ( 0 === $i ) { ?>
										<td rowspan="3">
											<a href="<?php echo esc_attr( $preview_output_href ); ?>" target="_blank"><?php esc_html_e( 'Preview', 'finalpdf' ); ?></a>
										</td>
									<?php } ?>
									<?php if ( $edit_link ) { ?>
										<td class="<?php echo ( 2 !== $i ) ? esc_attr( 'wpg-no-border-table' ) : 'wpg-no-border-top-table'; ?>"><a href="<?php echo esc_attr( $edit_link ); ?>" target="_blank"><?php echo esc_html( isset( $translated_pdf_parts[ $template_for ] ) ? $translated_pdf_parts[ $template_for ] : $template_for ); ?></a></td>
									<?php } else { ?>
										<td>
											<span><?php esc_html_e( 'Not Found', 'finalpdf' ); ?><span>
										</td>
										<?php
									}
									if ( 0 === $i ) {
										?>
										<td rowspan="3">
											<button data-template-name="<?php echo esc_html( $template ); ?>" class="wpg-delete-template"><?php esc_html_e( 'Delete', 'finalpdf' ); ?></button>
										</td>
									<?php } ?>
								</tr>
								<?php
								$i++;
							}
						}
						?>
					</tbody>
				</table>
				<div class="mpg-submit-btn-wrap">
					<button class="wpg-submit-internal-page-setting"><?php esc_html_e( 'Save Setting', 'finalpdf' ); ?></button>
				</div>
			</div>
		</form>
	</div>
</section>
<?php
echo esc_html__( 'Add [QR_CODE] shortcode anywhere on your Custom template to display QR Code on pdf.', 'finalpdf' );
?>
