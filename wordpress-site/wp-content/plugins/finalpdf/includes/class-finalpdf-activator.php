<?php
/**
 * Fired during plugin activation
 *
 * @link       https://yourwebsite.com/
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    FinalPDF
 * @subpackage FinalPDF/includes
 * @author     Your Company <support@yourwebsite.com>
 */
class FinalPDF_Activator {

	/**
	 * Function will run during plugin activation.
	 *
	 * @param boolean $network_wide either network activated or not.
	 * @since 1.0.0
	 * @return void
	 */
	public static function finalpdf_activate( $network_wide ) {
		global $wpdb;
		if ( is_multisite() && $network_wide ) {
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" ); // phpcs:ignore WordPress
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				self::finalpdf_updating_default_settings_indb();
				restore_current_blog();
			}
		} else {
			self::finalpdf_updating_default_settings_indb();
		}
	}

	/**
	 * Updating default settings in db wile plugin activation.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function finalpdf_updating_default_settings_indb() {
		if ( ! get_option( 'finalpdf_general_settings_save' ) ) {
			$finalpdf_new_settings = array(
				'finalpdf_general_settings_save'       => array(
					'finalpdf_enable_plugin'                => 'yes',
					'finalpdf_general_pdf_show_categories'  => 'yes',
					'finalpdf_general_pdf_show_tags'        => 'yes',
					'finalpdf_general_pdf_show_post_date'   => 'yes',
					'finalpdf_general_pdf_show_author_name' => 'yes',
					'finalpdf_general_pdf_generate_mode'    => 'download_locally',
					'finalpdf_general_pdf_file_name'        => 'post_name',
					'finalpdf_custom_pdf_file_name'         => '',
				),
				'finalpdf_save_admin_display_settings' => array(
					'finalpdf_user_access'                  => 'yes',
					'finalpdf_guest_access'                 => 'yes',
					'finalpdf_guest_download_or_email'      => 'direct_download',
					'finalpdf_user_download_or_email'       => 'direct_download',
					'finalpdf_display_pdf_icon_after'       => 'after_content',
					'finalpdf_display_pdf_icon_alignment'   => 'center',
					'sub_pgfw_pdf_single_download_icon' => '',
					'sub_pgfw_pdf_bulk_download_icon'   => '',
					'finalpdf_pdf_icon_width'               => 25,
					'finalpdf_pdf_icon_height'              => 45,
				),
				'finalpdf_header_setting_submit'       => array(
					'finalpdf_header_use_in_pdf'       => 'yes',
					'sub_pgfw_header_image_upload' => '',
					'finalpdf_header_company_name'     => 'Company Name',
					'finalpdf_header_tagline'          => 'Address | Phone | Link | Email',
					'finalpdf_header_color'            => '#000000',
					'finalpdf_header_width'            => 8,
					'finalpdf_header_font_style'       => 'helvetica',
					'finalpdf_header_font_size'        => 10,
					'finalpdf_header_top'              => -60,
				),
				'finalpdf_footer_setting_submit'       => array(
					'finalpdf_footer_use_in_pdf' => 'yes',
					'finalpdf_footer_tagline'    => 'Footer Tagline',
					'finalpdf_footer_color'      => '#000000',
					'finalpdf_footer_width'      => 12,
					'finalpdf_footer_font_style' => 'helvetica',
					'finalpdf_footer_font_size'  => 10,
					'finalpdf_footer_bottom'     => -140,
				),
				'finalpdf_body_save_settings'          => array(
					'finalpdf_body_title_font_style'  => 'helvetica',
					'finalpdf_body_title_font_size'   => 20,
					'finalpdf_body_title_font_color'  => '#000000',
					'finalpdf_body_page_size'         => 'a4',
					'finalpdf_body_page_orientation'  => 'portrait',
					'finalpdf_body_page_font_style'   => 'helvetica',
					'finalpdf_content_font_size'      => 12,
					'finalpdf_body_font_color'        => '#000000',
					'finalpdf_body_border_size'       => 0,
					'finalpdf_body_border_color'      => '',
					'finalpdf_body_margin_top'        => 70,
					'finalpdf_body_margin_left'       => 35,
					'finalpdf_body_margin_right'      => 10,
					'finalpdf_body_margin_bottom'     => 60,
					'finalpdf_body_rtl_support'       => 'no',
					'finalpdf_body_add_watermark'     => 'yes',
					'finalpdf_body_watermark_text'    => 'default watermark',
					'finalpdf_body_watermark_color'   => '#000000',
					'finalpdf_body_page_template'     => 'template1',
					'finalpdf_body_post_template'     => 'template1',
					'finalpdf_border_position_top'    => -110,
					'finalpdf_border_position_left'   => -34,
					'finalpdf_border_position_right'  => -15,
					'finalpdf_border_position_bottom' => -60,
				),
				'finalpdf_advanced_save_settings'      => array(
					'finalpdf_advanced_show_post_type_icons' => array( 'page', 'post', 'product' ),
				),
				'finalpdf_meta_fields_save_settings'   => array(
					'finalpdf_meta_fields_post_show'    => 'no',
					'finalpdf_meta_fields_product_show' => 'no',
					'finalpdf_meta_fields_page_show'    => 'no',
					'finalpdf_meta_fields_product_list' => '',
					'finalpdf_meta_fields_post_list'    => '',
					'finalpdf_meta_fields_page_list'    => '',
				),
				'finalpdf_pdf_upload_save_settings'    => array(
					'sub_pgfw_poster_image_upload' => '',
					'finalpdf_poster_user_access'      => 'yes',
					'finalpdf_poster_guest_access'     => 'yes',
				),
			);
			foreach ( $finalpdf_new_settings as $key => $val ) {
				update_option( $key, $val );
			}
		}
	}
}
