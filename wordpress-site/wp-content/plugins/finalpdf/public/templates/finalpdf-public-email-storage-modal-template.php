<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://finaldoc.com/
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/public/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
/**
 * Return template for modal.
 *
 * @param string $url_here url to download PDF.
 * @param int    $id post id.
 * @return string
 */
function finalpdf_modal_for_email_template( $url_here, $id ) {
	$finalpdf_display_settings             = get_option( 'finalpdf_save_admin_display_settings', array() );
	$finalpdf_pdf_icon_alignment           = array_key_exists( 'finalpdf_display_pdf_icon_alignment', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_display_pdf_icon_alignment'] : '';
	$sub_pgfw_pdf_single_download_icon = array_key_exists( 'sub_pgfw_pdf_single_download_icon', $finalpdf_display_settings ) ? $finalpdf_display_settings['sub_pgfw_pdf_single_download_icon'] : '';
	$finalpdf_single_pdf_download_icon_src = ( '' !== $sub_pgfw_pdf_single_download_icon ) ? $sub_pgfw_pdf_single_download_icon : FINALPDF_DIR_URL . 'admin/src/images/PDF_Tray.svg';
	$finalpdf_pdf_icon_width               = array_key_exists( 'finalpdf_pdf_icon_width', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_pdf_icon_width'] : '';
	$finalpdf_pdf_icon_height              = array_key_exists( 'finalpdf_pdf_icon_height', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_pdf_icon_height'] : '';
	if ( is_plugin_active( 'finalpdf/finalpdf.php' ) ) {
		$wps_finalpdf_single_pdf_icon_name    = array_key_exists( 'wps_finalpdf_single_pdf_icon_name', $finalpdf_display_settings ) ? $finalpdf_display_settings['wps_finalpdf_single_pdf_icon_name'] : '';
		$is_pro_active = true;
	} else {
		$wps_finalpdf_single_pdf_icon_name = '';
		$is_pro_active = false;
	}

	$html  = '<div class="pdf-icon-for-the-email" style=" gap:10px;justify-content:' . esc_html( $finalpdf_pdf_icon_alignment ) . '">
				<a href="#" title="' . esc_html__( 'Please Enter Your Email ID', 'finalpdf' ) . '" class="finalpdf-single-pdf-download-a"><img src="' . esc_url( $finalpdf_single_pdf_download_icon_src ) . '" title="' . esc_html__( 'Generate PDF', 'finalpdf' ) . '" style="height:' . esc_html( $finalpdf_pdf_icon_height ) . 'px;">' . $wps_finalpdf_single_pdf_icon_name . '</a>';
	$html  = apply_filters( 'wps_finalpdf_bulk_download_button_filter_hook', $html, $id );

	if ( is_user_logged_in() ) {
		$html .= '</div>
		<div id="single-pdf-download">
			<div class="wps-pdf_email--shadow"></div>
			<div class="wps-pdf_email-content">
				<div class="wps-pdf_email--close">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M0.225302 0.225302C0.525706 -0.0751008 1.01276 -0.0751008 1.31316 0.225302L10 8.91214L18.6868 0.225302C18.9872 -0.0751008 19.4743 -0.0751008 19.7747 0.225302C20.0751 0.525706 20.0751 1.01276 19.7747 1.31316L11.0879 10L19.7747 18.6868C20.0751 18.9872 20.0751 19.4743 19.7747 19.7747C19.4743 20.0751 18.9872 20.0751 18.6868 19.7747L10 11.0879L1.31316 19.7747C1.01276 20.0751 0.525706 20.0751 0.225302 19.7747C-0.0751008 19.4743 -0.0751008 18.9872 0.225302 18.6868L8.91214 10L0.225302 1.31316C-0.0751008 1.01276 -0.0751008 0.525706 0.225302 0.225302Z" fill="#171717"/>
					</svg>
				</div>
				<h2>' . esc_html__( 'Please Enter Your Email ID', 'finalpdf' ) . '</h2>
				<input type="hidden" name="post_id" id="finalpdf_current_post_id" data-post-id="' . esc_html( $id ) . '">
				<div class="wps_finalpdf_email_input">
					<label for="pgfw-user-email-input">
						' . esc_html__( 'Email ID', 'finalpdf' ) . '
					</label>
				<input type="email" id="pgfw-user-email-input" name="pgfw-user-email-input" placeholder="' . esc_html__( 'email', 'finalpdf' ) . '">
			
				
				<label for="pgfw-user-email-from-account">
				<input type="checkbox" id="pgfw-user-email-from-account" name="pgfw-user-email-from-account">
							' . esc_html__( 'Use account Email ID instead.', 'finalpdf' ) . '
						</label>
					</div><div class="wps_finalpdf_email_button">
					<button id="pgfw-submit-email-user">' . esc_html__( 'Submit', 'finalpdf' ) . '</button>
					</div>
					<div id="pgfw-user-email-submittion-message"></div>
				   </div>
			   </div>';
	} else {
		$html .= '</div>
		<div id="single-pdf-download">
			<div class="wps-pdf_email--shadow"></div>
			<div class="wps-pdf_email-content">
				<div class="wps-pdf_email--close">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M0.225302 0.225302C0.525706 -0.0751008 1.01276 -0.0751008 1.31316 0.225302L10 8.91214L18.6868 0.225302C18.9872 -0.0751008 19.4743 -0.0751008 19.7747 0.225302C20.0751 0.525706 20.0751 1.01276 19.7747 1.31316L11.0879 10L19.7747 18.6868C20.0751 18.9872 20.0751 19.4743 19.7747 19.7747C19.4743 20.0751 18.9872 20.0751 18.6868 19.7747L10 11.0879L1.31316 19.7747C1.01276 20.0751 0.525706 20.0751 0.225302 19.7747C-0.0751008 19.4743 -0.0751008 18.9872 0.225302 18.6868L8.91214 10L0.225302 1.31316C-0.0751008 1.01276 -0.0751008 0.525706 0.225302 0.225302Z" fill="#171717"/>
					</svg>
				</div>
				<h2>' . esc_html__( 'Please Enter Your Email ID', 'finalpdf' ) . '</h2>
				<input type="hidden" name="post_id" id="finalpdf_current_post_id" data-post-id="' . esc_html( $id ) . '">
				<div class="wps_finalpdf_email_input">
					<label for="pgfw-user-email-input">
						' . esc_html__( 'Email ID', 'finalpdf' ) . '
					</label>
				<input type="email" id="pgfw-user-email-input" name="pgfw-user-email-input" placeholder="' . esc_html__( 'email', 'finalpdf' ) . '">
			
				</div>';
			$html .= '<div class="wps_finalpdf_email_button">
					<button id="pgfw-submit-email-user">' . esc_html__( 'Submit', 'finalpdf' ) . '</button>
					</div>
					<div id="pgfw-user-email-submittion-message"></div>
				</div>
			</div>';
	}

	return $html;
}
