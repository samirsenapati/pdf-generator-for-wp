<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://yourwebsite.com/
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

        exit(); // Exit if accessed directly.
}
/**
 * Function contains html for template 1;
 *
 * @param int    $post_id post id.
 * @param string $template_name template name to be used.
 * @since 1.0.0
 *
 * @return string
 */
function return_ob_html( $post_id, $template_name = '' ) {
        do_action( 'wps_finalpdf_load_all_compatible_shortcode_converter' );

        $finalpdf_display_settings                   = get_option( 'finalpdf_save_admin_display_settings', array() );
        // advanced settings.
        $finalpdf_advanced_settings = get_option( 'finalpdf_advanced_save_settings', array() );
        $finalpdf_ttf_font_upload   = array_key_exists( 'finalpdf_ttf_font_upload', $finalpdf_advanced_settings ) ? $finalpdf_advanced_settings['finalpdf_ttf_font_upload'] : '';
        $finalpdf_template_color_option                 = array_key_exists( 'finalpdf_template_color_option', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_template_color_option'] : '';
        // header customisation settings.
        $finalpdf_header_settings   = get_option( 'finalpdf_header_setting_submit', array() );
        $finalpdf_header_use_in_pdf = array_key_exists( 'finalpdf_header_use_in_pdf', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_use_in_pdf'] : '';
        $finalpdf_header_logo       = array_key_exists( 'sub_pgfw_header_image_upload', $finalpdf_header_settings ) ? $finalpdf_header_settings['sub_pgfw_header_image_upload'] : '';
        $finalpdf_header_comp_name  = array_key_exists( 'finalpdf_header_company_name', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_company_name'] : '';
        $finalpdf_header_tagline    = array_key_exists( 'finalpdf_header_tagline', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_tagline'] : '';
        $finalpdf_header_color      = array_key_exists( 'finalpdf_header_color', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_color'] : '';
        $finalpdf_header_width      = array_key_exists( 'finalpdf_header_width', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_width'] : '';
        $finalpdf_header_font_style = array_key_exists( 'finalpdf_header_font_style', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_font_style'] : '';
        $finalpdf_header_font_style = ( 'custom' === $finalpdf_header_font_style ) ? 'My_font' : $finalpdf_header_font_style;
        $finalpdf_header_font_size  = array_key_exists( 'finalpdf_header_font_size', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_font_size'] : '';
        $finalpdf_header_top        = array_key_exists( 'finalpdf_header_top', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_top'] : '';
        // body customisation settings.
        $finalpdf_body_settings              = get_option( 'finalpdf_body_save_settings', array() );
        $finalpdf_body_spcl_char_support      = array_key_exists( 'finalpdf_body_spcl_char_support', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_spcl_char_support'] : '';
        $finalpdf_body_title_font_style      = array_key_exists( 'finalpdf_body_title_font_style', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_title_font_style'] : '';
        $finalpdf_body_title_font_style      = ( 'custom' === $finalpdf_body_title_font_style ) ? 'My_font' : $finalpdf_body_title_font_style;
        $finalpdf_body_title_font_size       = array_key_exists( 'finalpdf_body_title_font_size', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_title_font_size'] : '';
        $finalpdf_body_title_font_color      = array_key_exists( 'finalpdf_body_title_font_color', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_title_font_color'] : '';
        $finalpdf_body_page_font_style       = array_key_exists( 'finalpdf_body_page_font_style', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_page_font_style'] : '';
        $finalpdf_body_page_font_style       = ( 'custom' === $finalpdf_body_page_font_style ) ? 'My_font' : $finalpdf_body_page_font_style;
        $finalpdf_body_page_font_size        = array_key_exists( 'finalpdf_content_font_size', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_content_font_size'] : '';
        $finalpdf_body_page_font_color       = array_key_exists( 'finalpdf_body_font_color', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_font_color'] : '';
        $finalpdf_body_border_size           = array_key_exists( 'finalpdf_body_border_size', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_border_size'] : '';
        $finalpdf_body_border_color          = array_key_exists( 'finalpdf_body_border_color', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_border_color'] : '';
        $finalpdf_body_margin_top            = array_key_exists( 'finalpdf_body_margin_top', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_margin_top'] : '';
        $finalpdf_body_margin_left           = array_key_exists( 'finalpdf_body_margin_left', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_margin_left'] : '';
        $finalpdf_body_margin_right          = array_key_exists( 'finalpdf_body_margin_right', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_margin_right'] : '';
        $finalpdf_body_margin_bottom         = array_key_exists( 'finalpdf_body_margin_bottom', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_margin_bottom'] : '';
        $finalpdf_body_rtl_support           = array_key_exists( 'finalpdf_body_rtl_support', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_rtl_support'] : '';
        $finalpdf_watermark_image_use_in_pdf = array_key_exists( 'finalpdf_watermark_image_use_in_pdf', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_watermark_image_use_in_pdf'] : '';
        $finalpdf_border_position_top        = array_key_exists( 'finalpdf_border_position_top', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_border_position_top'] : '';
        $finalpdf_border_position_bottom     = array_key_exists( 'finalpdf_border_position_bottom', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_border_position_bottom'] : '';
        $finalpdf_border_position_left       = array_key_exists( 'finalpdf_border_position_left', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_border_position_left'] : '';
        $finalpdf_border_position_right      = array_key_exists( 'finalpdf_border_position_right', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_border_position_right'] : '';
        $finalpdf_body_custom_css            = array_key_exists( 'finalpdf_body_custom_css', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_custom_css'] : '';
        $finalpdf_body_watermark_text        = array_key_exists( 'finalpdf_body_watermark_text', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_watermark_text'] : '';
        $finalpdf_body_add_watermark        = array_key_exists( 'finalpdf_body_add_watermark', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_add_watermark'] : '';
        $finalpdf_body_watermark_color        = array_key_exists( 'finalpdf_body_watermark_color', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_watermark_color'] : '';
        $finalpdf_body_customization     = array_key_exists( 'finalpdf_body_customization_for_post_detail', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_customization_for_post_detail'] : '';
        // general settings.
        $general_settings_data     = get_option( 'finalpdf_general_settings_save', array() );
        $finalpdf_show_post_categories = array_key_exists( 'finalpdf_general_pdf_show_categories', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_show_categories'] : '';
        $finalpdf_show_post_tags       = array_key_exists( 'finalpdf_general_pdf_show_tags', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_show_tags'] : '';
        $finalpdf_show_post_taxonomy   = array_key_exists( 'finalpdf_general_pdf_show_taxonomy', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_show_taxonomy'] : '';
        $finalpdf_show_post_date       = array_key_exists( 'finalpdf_general_pdf_show_post_date', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_show_post_date'] : '';
        $finalpdf_show_current_date       = array_key_exists( 'finalpdf_general_pdf_show_current_date', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_show_current_date'] : '';
        $finalpdf_show_post_author     = array_key_exists( 'finalpdf_general_pdf_show_author_name', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_show_author_name'] : '';
        $finalpdf_general_pdf_date_format    = array_key_exists( 'finalpdf_general_pdf_date_format', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_date_format'] : '';

        $finalpdf_template_color               = array_key_exists( 'finalpdf_template_color', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_template_color'] : '#FFFFFF';
        $finalpdf_template_text_color       = array_key_exists( 'finalpdf_template_text_color', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_template_text_color'] : '#000000';
        // meta fields settings.
        $finalpdf_meta_settings = get_option( 'finalpdf_meta_fields_save_settings', array() );
        $finalpdf_meta_fields_show_image_gallery = array_key_exists( 'finalpdf_meta_fields_show_image_gallery', $finalpdf_meta_settings ) ? $finalpdf_meta_settings['finalpdf_meta_fields_show_image_gallery'] : '';
        $finalpdf_gallery_metafield_key = array_key_exists( 'finalpdf_gallery_metafield_key', $finalpdf_meta_settings ) ? $finalpdf_meta_settings['finalpdf_gallery_metafield_key'] : '';
        // footer settings.
        $finalpdf_footer_settings   = get_option( 'finalpdf_footer_setting_submit', array() );
        $finalpdf_footer_use_in_pdf = array_key_exists( 'finalpdf_footer_use_in_pdf', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_use_in_pdf'] : '';
        $finalpdf_footer_tagline    = array_key_exists( 'finalpdf_footer_tagline', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_tagline'] : '';
        $finalpdf_footer_color      = array_key_exists( 'finalpdf_footer_color', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_color'] : '';
        $finalpdf_footer_width      = array_key_exists( 'finalpdf_footer_width', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_width'] : '';
        $finalpdf_footer_font_style = array_key_exists( 'finalpdf_footer_font_style', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_font_style'] : '';
        $finalpdf_footer_font_style = ( 'custom' === $finalpdf_footer_font_style ) ? 'My_font' : $finalpdf_footer_font_style;
        $finalpdf_footer_font_size  = array_key_exists( 'finalpdf_footer_font_size', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_font_size'] : '';
        $finalpdf_footer_bottom     = array_key_exists( 'finalpdf_footer_bottom', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_bottom'] : '';
        $finalpdf_footer_customization     = array_key_exists( 'finalpdf_footer_customization_for_post_detail', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_customization_for_post_detail'] : '';
        $finalpdf_body_meta_field_column     = array_key_exists( 'finalpdf_body_meta_field_column', $finalpdf_body_settings ) ? intval( $finalpdf_body_settings['finalpdf_body_meta_field_column'] ) : '';
        $finalpdf_body_images_row_wise     = array_key_exists( 'finalpdf_body_images_row_wise', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_images_row_wise'] : '';

        $status  = get_option( 'status' );

        if ( '' == $finalpdf_footer_customization ) {
                $finalpdf_footer_customization = array();
        }
        if ( '' == $status ) {
                $post = get_post( $post_id );
                $author_id = get_post_field( 'post_author', $post_id );
                $display_name = get_the_author_meta( 'display_name', $author_id );
                $post_date = get_the_date( $finalpdf_general_pdf_date_format, $post_id );
                $post_title = get_the_title( $post );
        }
                $display_author_name = in_array( 'author', $finalpdf_footer_customization ) ? $display_name : '';
                $display_post_date = in_array( 'post_date', $finalpdf_footer_customization ) ? $post_date : '';
                $display_post_title = in_array( 'post_title', $finalpdf_footer_customization ) ? $post_title : '';
        if ( 'yes' === $finalpdf_body_rtl_support ) {
                $finalpdf_header_font_style     = 'DejaVu Sans, sans-serif';
                $finalpdf_body_page_font_style  = 'DejaVu Sans, sans-serif';
                $finalpdf_body_title_font_style = 'DejaVu Sans, sans-serif';
                $finalpdf_footer_font_style     = 'DejaVu Sans, sans-serif';
        }

        $html = '';
        if ( '' !== $finalpdf_ttf_font_upload && ( $finalpdf_ttf_font_upload ) ) {
                $upload_dir     = wp_upload_dir();
                $upload_baseurl = $upload_dir['baseurl'] . '/finalpdf_ttf_font/';
                $upload_basedir = $upload_dir['basedir'] . '/finalpdf_ttf_font/';
                $font_url       = $upload_baseurl . $finalpdf_ttf_font_upload;
                $font_path      = $upload_basedir . $finalpdf_ttf_font_upload;
                if ( file_exists( $font_path ) ) {
                        $html = '<style>
                                        @font-face{
                                                font-family : My_font;
                                                src         : url("' . $font_url . '")  format("truetype");
                                                font-weight : bold;
                                                font-style  : normal;
                                        }
                                        @font-face{
                                                font-family : My_font;
                                                src         : url("' . $font_url . '")  format("truetype");
                                                font-weight : normal;
                                                font-style  : normal;
                                        }
                                </style>';
                }
        }
        if ( 'yes' != $finalpdf_body_spcl_char_support ) {
                $html .= '<style>
                <meta charset="UTF-8">
                <style>
                body {
                        font-family : "DejaVu Sans, sans-serif";
                }</style>';
        }

        $html .= '<style>
                        @page {
                                margin-top    : ' . $finalpdf_body_margin_top . ';
                                margin-left   : ' . $finalpdf_body_margin_left . ';
                                margin-right  : ' . $finalpdf_body_margin_right . ';
                                margin-bottom : ' . $finalpdf_body_margin_bottom . ';
                        }
                </style>';
        if ( $finalpdf_body_border_size > 0 ) {
                $html .= '<style>
                        .finalpdf-border-page {
                                position      : fixed;
                                margin-bottom : ' . $finalpdf_border_position_bottom . ';
                                margin-left   : ' . $finalpdf_border_position_left . ';
                                margin-top    : ' . $finalpdf_border_position_top . ';
                                margin-right  : ' . $finalpdf_border_position_right . ';
                                border        : ' . $finalpdf_body_border_size . 'px solid ' . $finalpdf_body_border_color . ';
                        }
                </style>
                <div class="finalpdf-border-page" ></div>';
        }
        if ( 'yes' == $finalpdf_body_add_watermark ) {
                $watermark = $finalpdf_body_watermark_text;
        } else {
                $watermark = '';
        }
        // Header for pdf.
        if ( 'yes' === $finalpdf_header_use_in_pdf ) {
                $finalpdf_header_logo_size  = array_key_exists( 'finalpdf_header_logo_size', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_logo_size'] : '30';
                $html .= '<style>
                                        .finalpdf-pdf-header-each-page{
                                                position : fixed;
                                                left     : 0px;
                                                right :0;
                                                height   : 100px;
                                                top      : ' . $finalpdf_header_top . ';
                                        }
                                        .finalpdf-pdf-header{
                                                border-bottom  : 2px solid gray;
                                                padding        : ' . $finalpdf_header_width . 'px;
                                                font-family    : ' . $finalpdf_header_font_style . ';
                                                font-size      : ' . $finalpdf_header_font_size . ';
                                                padding-bottom : 25px;
                                        }
                                        .finalpdf-header-logo{
                                                width : ' . $finalpdf_header_logo_size . 'px;
                                                float  : left;
                                        }
                                        .finalpdf-header-tagline{
                                                text-align : right;
                                                color      : ' . $finalpdf_header_color . ';
                                        }
                                        #watermark {
                                                position: fixed;
                                                top: 35%;
                                                left:-30px;
                                                width: 100%;
                                                text-align: center;
                                                opacity: .3;
                                                transform: rotate(15deg);
                                                transform-origin: 50% 50%;
                                                z-index: 1000;
                                                font-size: 40px;
                                                font-weight:600;
                                                color : ' . $finalpdf_body_watermark_color . ';
                                          }
                                </style>
                                <div class="finalpdf-pdf-header-each-page">
                                <div id="watermark">
                                ' . $watermark . '
                                </div>
                                        <div class="finalpdf-pdf-header">
                                        
                                                <img src="' . esc_url( $finalpdf_header_logo ) . '" alt="' . esc_html__( 'No image found', 'finalpdf' ) . '" class="finalpdf-header-logo">
                                                <div class="finalpdf-header-tagline" >
                                                        <span><b>' . esc_html( strtoupper( $finalpdf_header_comp_name ) ) . '</b></span><br/>
                                                        <span>' . esc_html( $finalpdf_header_tagline ) . '</span>
                                                </div>
                                        </div>
                                </div>';
        }

                // footer for pdf.
        if ( 'yes' === $finalpdf_footer_use_in_pdf ) {
                $html .= '<style>
                        .finalpdf-pdf-footer{
                                position    : fixed;
                                left        : 0px;
                                right :0;
                                bottom      : ' . $finalpdf_footer_bottom . ';
                                height      : 150px;
                                border-top  : 2px solid gray;
                                padding     : ' . $finalpdf_footer_width . 'px;
                                font-family : ' . $finalpdf_footer_font_style . ';
                                font-size   : ' . $finalpdf_footer_font_size . ';
                        }
                        .finalpdf-footer-tagline{
                                color      : ' . $finalpdf_footer_color . ';
                                text-align : center;
                                overflow   : hidden;
                        }
                        .finalpdf-footer-pageno:after {
                                content : "Page " counter(page);
                        }
                </style>';
                $html .= '<div class="finalpdf-pdf-footer">
                                        <span class="finalpdf-footer-pageno"></span>
                                        <div style="text-align:right; margin-top:-15px;">
                                                <div> ' . esc_html( $display_author_name ) . '</div>
                                        </div>
                                        <div class="finalpdf-footer-tagline" >
                                                <span>' . esc_html( $finalpdf_footer_tagline ) . '</span>
                                        </div>
                                        <div style="text-align:right; margin-top:-15px;">
                                        <div>' . esc_html( $display_post_date ) . '</div>
                                        <div > ' . esc_html( $display_post_title ) . '</div>
                                        </div>
                                </div>';
        }
        // body for pdf.
        if ( 'yes' === $finalpdf_watermark_image_use_in_pdf ) {
                $html = apply_filters( 'finalpdf_customize_body_watermark_image_pdf', $html );
        }
        if ( '' !== $finalpdf_body_custom_css ) {
                $html .= '<style>
                                        ' . $finalpdf_body_custom_css . '
                                </style>';
        }
        $post = get_post( $post_id );
        if ( is_object( $post ) ) {
                $html .= '<style>
                
                                        .finalpdf-pdf-body-title{
                                                font-family : ' . $finalpdf_body_title_font_style . ';
                                                font-size   : ' . $finalpdf_body_title_font_size . 'px;
                                                color       : ' . $finalpdf_body_title_font_color . ';
                                                padding     : 10px 0px;
                                        }
                                        .finalpdf-pdf-body-title-image{
                                                margin-top : 10px;
                                        }
                                        .finalpdf-pdf-body-title-image img{
                                                width  : 200px;
                                                height : 200px;
                                        }
                                        .finalpdf-pdf-body-content{
                                                font-family : ' . $finalpdf_body_page_font_style . ';
                                                font-size   : ' . $finalpdf_body_page_font_size . ';
                                                color       : ' . $finalpdf_body_page_font_color . ';
                                                
                                        }
                                        
                                        
                                        ';

                if ( 'yes' == $finalpdf_template_color_option ) {
                        $html .= '
                        .finalpdf-pdf-body {
                          position: fixed;
                          inset: -1in;
                          background-color: ' . $finalpdf_template_color . ' ;
                          z-index: -1000;
                          margin : -100px !important;
                          padding:100px !important;
                          
                        }
                        .finalpdf-pdf-body *, .finalpdf-pdf-footer *, .finalpdf-pdf-header * {
                          color:' . $finalpdf_template_text_color . ';
                        }
                   
                        ';
                }

                if ( 'yes' == $finalpdf_body_images_row_wise ) {

                        $html .= '      .finalpdf-pdf-body-content .wp-block-columns:after {
                                                content: "";
                                                display: block;
                                                clear: both;
                                        }
                                                .finalpdf-pdf-body-content .wp-block-column {
                                                                        width: 33.333%;
                                                                float: left;                                    }
                                                                .wp-block-column img {
                                                                        width: 100%;
                                                                        height: auto;
                                                                }
                                                                .finalpdf-pdf-body-content img {
                                                                        max-height : 680px;
                                                                        max-width  : 680px;
                                                                        width :100%
                                                                        height :100%;
                                                                
                                                                }
                                                                .finalpdf-pdf-body-content .wp-block-gallery:after {
                                                                        content: "";
                                                                        display: block;
                                                                        clear: both;
                                                                }
                                                                .finalpdf-pdf-body-content .wp-block-gallery .wp-block-image {
                                                                        margin-top: 30px !important;
                                                                        width: 31.333%;
                                                                        margin: 0 ;
                                                                        padding:3px;
                                                                        text-align: center;
                                                                        display: inline-block;
                                                                        vertical-align: middle;
                                                                        
                                                                }
                                                                .wp-block-gallery .wp-block-image img {
                                                                        width: auto;
                                                                        max-width: 100%;
                                                                        height: auto;
                                                                        
                                                                }';
                }

                $html   .= '</style>
                <div class="finalpdf-pdf-body" >';
                if ( ! empty( $finalpdf_body_customization ) && in_array( 'post_thumb', $finalpdf_body_customization ) ) {
                        $html .= '';
                } else {
                        $html .= '<div class="finalpdf-pdf-body-title-image">
                                        <img src="' . get_the_post_thumbnail_url( $post ) . '">
                                </div>';
                }
                if ( ! empty( $finalpdf_body_customization ) && in_array( 'title', $finalpdf_body_customization ) ) {
                        $html .= '';
                } else {
                        $html .= '<div class="finalpdf-pdf-body-title">
                                        ' . do_shortcode( str_replace( '[WORDPRESS_PDF]', '', apply_filters( 'the_title', $post->post_title, $post->ID ) ) ) . '
                                        </div>';
                }

                $html .= '      <div class="finalpdf-pdf-body-content">';
                
                // Generate post content with all filters applied
                $post_content_html = do_shortcode( str_replace( '[WORDPRESS_PDF]', '', apply_filters( 'the_content', apply_filters( 'wps_finalpdf_customize_template_post_content', $post->post_content, $post ) ) ) );
                
                // Generate Table of Contents
                $toc_settings = finalpdf_get_toc_settings();
                $toc_args = array(
                        'enabled'    => ( 'yes' === $toc_settings['enable_toc'] ),
                        'max_depth'  => (int) $toc_settings['toc_depth'],
                        'min_depth'  => 1,
                        'title'      => $toc_settings['toc_title'],
                );
                
                $toc_result = finalpdf_generate_toc( $post_content_html, $toc_args );
                
                // Add TOC after title
                if ( ! empty( $toc_result['toc_html'] ) ) {
                        $html .= $toc_result['toc_html'];
                }
                
                // Add description header if not hidden
                if ( ! empty( $finalpdf_body_customization ) && in_array( 'description', $finalpdf_body_customization ) ) {
                        $html .= '';
                } else {
                        $html .= '      <h3>' . esc_html__( 'Description', 'finalpdf' ) . '</h3>';
                }
                
                // Add content with anchor IDs
                $html .= '<div>' . $toc_result['content_html'] . '</div>';
                // taxonomies for posts.
                $html1 = '';
                if ( 'yes' === $finalpdf_show_post_taxonomy ) {
                        $taxonomies = get_object_taxonomies( $post );
                        if ( is_array( $taxonomies ) ) {
                                foreach ( $taxonomies as $taxonomy ) {
                                        $prod_cat = get_the_terms( $post, $taxonomy );
                                        if ( is_array( $prod_cat ) ) {
                                                $html1 .= '<div><b>' . strtoupper( str_replace( '_', ' ', $taxonomy ) ) . '</b></div>';
                                                $html1 .= '<ol>';
                                                foreach ( $prod_cat as $category ) {
                                                        $html1 .= '<li>' . $category->name . '</li>';
                                                }
                                                $html1 .= '</ol>';
                                        }
                                }
                        }
                }
                $html .= apply_filters( 'wps_finalpdf_product_taxonomy_in_pdf_filter_hook', $html1, $post );
                // category for posts.

                if ( 'yes' === $finalpdf_show_post_categories ) {
                        $categories = get_the_category( $post->ID );
                        if ( is_array( $categories ) && ! empty( $categories ) ) {
                                $html .= '<div><b>' . esc_html__( 'Category', 'finalpdf' ) . '</b></div>';
                                $html .= '<ol>';
                                foreach ( $categories as $category ) {
                                        $html .= '<li>' . $category->name . '</li>';
                                }
                                $html .= '</ol>';
                        }
                }
                // tags for posts.
                if ( 'yes' === $finalpdf_show_post_tags ) {
                        $tags = get_the_tags( $post );
                        if ( is_array( $tags ) ) {
                                $html .= '<div><b>' . __( 'Tags', 'finalpdf' ) . '</b></div>';
                                $html .= '<ol>';
                                foreach ( $tags as $tag ) {
                                        $html .= '<li>' . $tag->name . '</li> ';
                                }
                                $html .= '</ol>';
                        }
                }
                // post Dowloading date.
                if ( 'yes' === $finalpdf_show_current_date ) {
                        $current_date = gmdate( $finalpdf_general_pdf_date_format );
                        $html        .= '<div><b>' . __( 'Date', 'finalpdf' ) . '</b></div>';
                        $html        .= '<div>' . $current_date . '</div>';
                }
                // post created date.
                if ( 'yes' === $finalpdf_show_post_date ) {
                        $created_date = get_the_date( $finalpdf_general_pdf_date_format, $post );
                        $html        .= '<div><b>' . __( 'Date Created', 'finalpdf' ) . '</b></div>';
                        $html        .= '<div>' . $created_date . '</div>';
                }
                // post author.
                if ( 'yes' === $finalpdf_show_post_author ) {
                        $author_id   = $post->post_author;
                        $author_name = get_the_author_meta( 'user_nicename', $author_id );
                        $html       .= '<div><b>' . __( 'Author', 'finalpdf' ) . '</b></div>';
                        $html       .= '<div>' . $author_name . '</div>';
                }
                // meta fields.
                $post_type               = $post->post_type;
                $finalpdf_show_type_meta_val = array_key_exists( 'finalpdf_meta_fields_' . $post_type . '_show', $finalpdf_meta_settings ) ? $finalpdf_meta_settings[ 'finalpdf_meta_fields_' . $post_type . '_show' ] : '';
                $finalpdf_show_type_meta_arr = array_key_exists( 'finalpdf_meta_fields_' . $post_type . '_list', $finalpdf_meta_settings ) ? $finalpdf_meta_settings[ 'finalpdf_meta_fields_' . $post_type . '_list' ] : array();
                $finalpdf_body_metafields_row_wise     = array_key_exists( 'finalpdf_body_metafields_row_wise', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_metafields_row_wise'] : '';
                $html2                   = '';
                if ( 'yes' === $finalpdf_show_type_meta_val ) {
                        if ( is_array( $finalpdf_show_type_meta_arr ) ) {
                                $html2 .= '<div><b>' . __( 'Meta Fields', 'finalpdf' ) . '</b></div>';
                                $html2 .= '<table><tr>';
                                foreach ( $finalpdf_show_type_meta_arr as $meta_key ) {
                                        $meta_val          = get_post_meta( $post->ID, $meta_key, true );
                                        $finalpdf_meta_key_name = ucwords( str_replace( '_', ' ', $meta_key ) );

                                        if ( $meta_val ) {
                                                if ( ( '_product_image_gallery' == $meta_key ) || ( 'yes' == ( $finalpdf_meta_fields_show_image_gallery ) && ! empty( $finalpdf_gallery_metafield_key ) && ( $finalpdf_gallery_metafield_key == $meta_key ) ) ) {
                                                        $meta_val1 = explode( ',', $meta_val );
                                                        foreach ( $meta_val1 as $key => $val ) {

                                                                $thumbnail_url = get_the_guid( $val );
                                                                $thumbnail = '<img  src=' . $thumbnail_url . ' alt="post thumbnail" style="height:100px; width: 100px; margin:17px;" height=50 weight=50/>';
                                                                $html2 .= $thumbnail;
                                                        }
                                                        $html2 .= '<div><b> ' . $finalpdf_meta_key_name . '</b> </div>';

                                                } else {
                                                        if ( 'yes' == $finalpdf_body_metafields_row_wise ) {
                                                                $i++;
                                                                $html2 .= '<td><b>' . $finalpdf_meta_key_name . ' :</b></td>';
                                                                $html2 .= '<td> ' . $meta_val . ' </td>';
                                                                if ( 0 == $i % $finalpdf_body_meta_field_column ) {
                                                                        $html2 .= '</tr><tr>';
                                                                }
                                                        } else {
                                                                        $html2 .= '<div><b>' . $finalpdf_meta_key_name . ' : </b> ' . $meta_val . '</div>';
                                                        }
                                                }
                                        }
                                }

                                $html2 .= '</tr></table>';
                        }
                }
                $html .= apply_filters( 'wps_finalpdf_product_post_meta_in_pdf_filter_hook', $html2, $post );
                $html .= '</div></div><span id = "wps_page_break_point" style="page-break-after: always;overflow:hidden;"></span>';
        }

        return $html;
}

