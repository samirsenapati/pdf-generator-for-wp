<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://yourwebsite.com/
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/public/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
/**
 * PDF Download button.
 *
 * @param string $url_here url to download PDF.
 * @param int    $id post id to generate PDF for.
 * @return string
 */
function finalpdf_pdf_download_button( $url_here, $id ) {

	$general_settings_data             = get_option( 'finalpdf_general_settings_save', array() );
	$finalpdf_pdf_generate_mode            = array_key_exists( 'finalpdf_general_pdf_generate_mode', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_generate_mode'] : '';
	$mode                              = ( 'open_window' === $finalpdf_pdf_generate_mode ) ? 'target=_blank' : '';
	$finalpdf_display_settings             = get_option( 'finalpdf_save_admin_display_settings', array() );
	$finalpdf_pdf_icon_alignment           = array_key_exists( 'finalpdf_display_pdf_icon_alignment', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_display_pdf_icon_alignment'] : '';
	$sub_pgfw_pdf_single_download_icon = array_key_exists( 'sub_pgfw_pdf_single_download_icon', $finalpdf_display_settings ) ? $finalpdf_display_settings['sub_pgfw_pdf_single_download_icon'] : '';
	$finalpdf_single_pdf_download_icon_src = ( '' !== $sub_pgfw_pdf_single_download_icon ) ? $sub_pgfw_pdf_single_download_icon : FINALPDF_DIR_URL . 'admin/src/images/PDF_Tray.svg';
	$finalpdf_pdf_icon_width               = array_key_exists( 'finalpdf_pdf_icon_width', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_pdf_icon_width'] : '';
	$finalpdf_pdf_icon_height              = array_key_exists( 'finalpdf_pdf_icon_height', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_pdf_icon_height'] : '';
	$finalpdf_body_show_pdf_icon                 = array_key_exists( 'finalpdf_body_show_pdf_icon', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_body_show_pdf_icon'] : '';
	$finalpdf_show_post_type_icons_for_user_role = array_key_exists( 'finalpdf_show_post_type_icons_for_user_role', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_show_post_type_icons_for_user_role'] : array();
	$wps_finalpdf_whatsapp_sharing          = array_key_exists( 'wps_finalpdf_whatsapp_sharing', $finalpdf_display_settings ) ? $finalpdf_display_settings['wps_finalpdf_whatsapp_sharing'] : '';
	$finalpdf_print_enable          = array_key_exists( 'finalpdf_print_enable', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_print_enable'] : '';
	$user = wp_get_current_user();
	$whatsapp_link = generate_whatsapp_pdf_link( $url_here );

	if ( is_plugin_active( 'finalpdf/finalpdf.php' ) ) {
		$wps_finalpdf_single_pdf_icon_name    = array_key_exists( 'wps_finalpdf_single_pdf_icon_name', $finalpdf_display_settings ) ? $finalpdf_display_settings['wps_finalpdf_single_pdf_icon_name'] : '';
		$is_pro_active = true;
	} else {
		$wps_finalpdf_single_pdf_icon_name = '';
		$is_pro_active = false;
	}

	if ( 'yes' == $finalpdf_body_show_pdf_icon ) {

		if ( isset( $finalpdf_show_post_type_icons_for_user_role ) && ! empty( $finalpdf_show_post_type_icons_for_user_role ) && array_intersect( $user->roles, $finalpdf_show_post_type_icons_for_user_role ) ) {

			$html  = '<div style="display:flex; gap:10px;justify-content:' . esc_html( $finalpdf_pdf_icon_alignment ) . '" class="wps-finalpdf-pdf-generate-icon__wrapper-frontend">
			<div> <a href="' . esc_html( $url_here ) . '" class="finalpdf-single-pdf-download-button" ' . esc_html( $mode ) . '><img src="' . esc_url( $finalpdf_single_pdf_download_icon_src ) . '" title="' . esc_html__( 'Generate PDF', 'finalpdf' ) . '" style="width:auto; height:' . esc_html( $finalpdf_pdf_icon_height ) . 'px;"">' . $wps_finalpdf_single_pdf_icon_name . '</a>
			';
			$html  = apply_filters( 'wps_finalpdf_bulk_download_button_filter_hook', $html, $id );
			if ( $is_pro_active && 'yes' === $finalpdf_print_enable ) {

				$html .= '<a href="javascript:void(0)" id="finalpdf_print_button" class="finalpdf-single-pdf-download-button" ><img  src="' . FINALPDF_DIR_URL . 'admin/src/images/print_icon.png" style="display:inline-block;width:auto; height:' . esc_html( $finalpdf_pdf_icon_height ) . 'px;" ></a>';
			}
			if ( $is_pro_active && 'yes' == $wps_finalpdf_whatsapp_sharing ) {
				$html .= '<a class="finalpdf-single-pdf-download-button wps_finalpdf_whatsapp_share_icon" href="' . $whatsapp_link . '"><img src="' . FINALPDF_DIR_URL . '/admin/src/images/whatsapp.png" style="width:' . esc_html( $finalpdf_pdf_icon_width ) . 'px; height:' . esc_html( $finalpdf_pdf_icon_height ) . 'px;" ></a>';
			}

			$html .= '</div>';

			return $html;
		}
	} else {
		$html  = '<div style="display:flex; gap:10px;justify-content:' . esc_html( $finalpdf_pdf_icon_alignment ) . '" class="wps-finalpdf-pdf-generate-icon__wrapper-frontend">
		<a  href="' . esc_html( $url_here ) . '" class="finalpdf-single-pdf-download-button" ' . esc_html( $mode ) . '><img src="' . esc_url( $finalpdf_single_pdf_download_icon_src ) . '" title="' . esc_html__( 'Generate PDF', 'finalpdf' ) . '" style="width:auto; height:' . esc_html( $finalpdf_pdf_icon_height ) . 'px;">' . $wps_finalpdf_single_pdf_icon_name . '</a>
		';
		$html  = apply_filters( 'wps_finalpdf_bulk_download_button_filter_hook', $html, $id );
		if ( $is_pro_active && 'yes' === $finalpdf_print_enable ) {

			$html .= '<a href="javascript:void(0)" id="finalpdf_print_button" class="finalpdf-single-pdf-download-button" onclick="window.print()"><img  src="' . FINALPDF_DIR_URL . 'admin/src/images/print_icon.png" style="padding-left:10px;display:inline-block;width:auto; height:' . esc_html( $finalpdf_pdf_icon_height ) . 'px;" ></a>';
		}
		if ( $is_pro_active && 'yes' == $wps_finalpdf_whatsapp_sharing ) {

			$html .= '<a class="finalpdf-single-pdf-download-button wps_finalpdf_whatsapp_share_icon" href="' . $whatsapp_link . '"><img src="' . FINALPDF_DIR_URL . '/admin/src/images/whatsapp.png" style="width:auto; height:' . esc_html( $finalpdf_pdf_icon_height ) . 'px;" ></a>';
		}

			$html .= '</div>';

		return $html;
	}

}
/**
 * Whatsapp sharing link generator .
 *
 * @param string $file_url file_url .
 */
function generate_whatsapp_pdf_link( $file_url ) {
	$whatsapp_url = 'https://api.whatsapp.com/send?';
	$whatsapp_url .= 'text=' . urlencode( 'Check out this PDF file: ' . $file_url );
	return $whatsapp_url;
}
