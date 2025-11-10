<?php
/**
 * Provide a common view for the plugin
 *
 * This file is used to markup the common aspects of the plugin.
 *
 * @link       https://finaldoc.com/
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/common/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
/**
 * Return download button for poster shortcodes.
 *
 * @param string $poster_image_url url of the poster.
 * @return string
 */
function finalpdf_poster_download_button_for_shortcode( $poster_image_url ) {
	$html = '<div id="pgfw-poster-dowload-url-link">
				<a href="' . esc_url( $poster_image_url ) . '" download title="' . esc_html__( 'Download Poster', 'finalpdf' ) . '"><img src="' . esc_attr( FINALPDF_DIR_URL ) . 'admin/src/images/postericon.png" alt="' . esc_attr__( 'Download Poster', 'finalpdf' ) . '" style="width: 40px !important;; height:50px;"/></a>
			</div>';
	return $html;
}
