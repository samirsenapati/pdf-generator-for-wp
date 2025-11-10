<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://yourwebsite.com/
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/admin
 * @author     Your Company <support@yourwebsite.com>
 */
class FinalPDF_Admin {


        /**
         * The ID of this plugin.
         *
         * @since    1.0.0
         * @var      string    $plugin_name    The ID of this plugin.
         */
        private $plugin_name;

        /**
         * The version of this plugin.
         *
         * @since    1.0.0
         * @var      string    $version    The current version of this plugin.
         */
        private $version;

        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         * @param      string $plugin_name       The name of this plugin.
         * @param      string $version    The version of this plugin.
         */
        public function __construct( $plugin_name, $version ) {

                $this->plugin_name = $plugin_name;
                $this->version     = $version;
        }

        /**
         * Register the stylesheets for the admin area.
         *
         * @since    1.0.0
         * @param    string $hook      The plugin page slug.
         */
        public function finalpdf_admin_enqueue_styles( $hook ) {
                $screen = get_current_screen();
                if (isset($screen->id) && ('wp-swings_page_finalpdf_menu' == $screen->id || 'wp-swings_page_home' == $screen->id)) { // phpcs:ignore

                        wp_enqueue_style( 'wps-finalpdf-select2-css', FINALPDF_DIR_URL . 'package/lib/select-2/finalpdf-select2.css', array(), time(), 'all' );

                        wp_enqueue_style( 'wps-finalpdf-meterial-css', FINALPDF_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all' );
                        wp_enqueue_style( 'wps-finalpdf-meterial-css2', FINALPDF_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all' );
                        wp_enqueue_style( 'wps-finalpdf-meterial-lite', FINALPDF_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all' );

                        wp_enqueue_style( 'wps-finalpdf-meterial-icons-css', FINALPDF_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all' );

                        wp_enqueue_style( $this->plugin_name . '-admin-global', FINALPDF_DIR_URL . 'admin/src/css/finalpdf-admin-global.css', array( 'wps-finalpdf-meterial-icons-css' ), time(), 'all' );

                        wp_enqueue_style( 'wp-color-picker' );
                        wp_enqueue_style( 'pgfw-admin-commomn-css', FINALPDF_DIR_URL . 'admin/src/css/finalpdf-admin-common.css', array(), $this->version, 'all' );
                        wp_enqueue_style( 'pgfw-datatable-css', FINALPDF_DIR_URL . 'package/lib/datatable/datatables.min.css', array(), $this->version, 'all' );
                        wp_enqueue_style( 'pgfw-overview-form-css', FINALPDF_DIR_URL . 'admin/src/css/wps-admin.css', array(), $this->version, 'all' );
                        wp_enqueue_style( 'wps--admin--min-css', FINALPDF_DIR_URL . 'admin/src/css/pdf-admin-home.min.css', array(), $this->version, 'all' );
                }
                wp_enqueue_style( 'pgfw-admin-custom-css', FINALPDF_DIR_URL . 'admin/src/css/finalpdf-admin-custom.css', array(), $this->version, 'all' );
        }

        /**
         * Register the JavaScript for the admin area.
         *
         * @since    1.0.0
         * @param    string $hook      The plugin page slug.
         */
        public function finalpdf_admin_enqueue_scripts( $hook ) {

                $screen = get_current_screen();
                $wps_finalpdf_notice = array(
                        'ajaxurl'       => admin_url( 'admin-ajax.php' ),
                        'wps_finalpdf_nonce' => wp_create_nonce( 'wps-finalpdf-verify-notice-nonce' ),
                );
                wp_register_script( $this->plugin_name . 'admin-notice', plugin_dir_url( __FILE__ ) . 'src/js/finalpdf-notices.js', array( 'jquery' ), $this->version, false );
                wp_localize_script( $this->plugin_name . 'admin-notice', 'wps_finalpdf_notice', $wps_finalpdf_notice );
                wp_enqueue_script( $this->plugin_name . 'admin-notice' );
                if (isset($screen->id) && ('wp-swings_page_finalpdf_menu' == $screen->id || 'wp-swings_page_home' == $screen->id)) { // phpcs:ignore
                        wp_enqueue_script( 'wps-finalpdf-select2', FINALPDF_DIR_URL . 'package/lib/select-2/finalpdf-select2.js', array( 'jquery' ), time(), false );

                        wp_enqueue_script( 'wps-finalpdf-metarial-js', FINALPDF_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false );
                        wp_enqueue_script( 'wps-finalpdf-metarial-js2', FINALPDF_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false );
                        wp_enqueue_script( 'wps-finalpdf-metarial-lite', FINALPDF_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false );

                        wp_register_script( $this->plugin_name . 'admin-js', FINALPDF_DIR_URL . 'admin/src/js/finalpdf-admin.js', array( 'jquery', 'wps-finalpdf-select2', 'wps-finalpdf-metarial-js', 'wps-finalpdf-metarial-js2', 'wps-finalpdf-metarial-lite' ), $this->version, false );
                        $wps_finalpdf_plugin_list = get_option( 'active_plugins' );
                        $wps_finalpdf_is_pro_active = false;
                        $wps_finalpdf_plugin = 'finalpdf/finalpdf.php';
                        if ( in_array( $wps_finalpdf_plugin, $wps_finalpdf_plugin_list ) ) {
                                $wps_finalpdf_is_pro_active = true;
                        }
                        $license_check = get_option( 'wps_finalpdf_license_check', 0 );

                        wp_localize_script(
                                $this->plugin_name . 'admin-js',
                                'finalpdf_admin_param',
                                array(
                                        'ajaxurl'             => admin_url( 'admin-ajax.php' ),
                                        'reloadurl'           => admin_url( 'admin.php?page=finalpdf_menu' ),
                                        'finalpdf_gen_tab_enable' => get_option( 'finalpdf_radio_switch_demo' ),
                                        'is_pro_active' => $wps_finalpdf_is_pro_active,
                                        'license_check' => $license_check,
                                        'nonce'         => wp_create_nonce( 'wps_finalpdf_embed_ajax_nonce' ),
                                )
                        );

                        wp_enqueue_script( $this->plugin_name . 'admin-js' );
                        wp_enqueue_media();
                        wp_enqueue_script( 'pgfw-datatable-js', FINALPDF_DIR_URL . 'package/lib/datatable/datatables.min.js', array( 'jquery' ), $this->version, true );
                        wp_enqueue_script( 'wps-finalpdf-admin-custom-setting-js', FINALPDF_DIR_URL . 'admin/src/js/finalpdf-admin-custom.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );
                        wp_localize_script(
                                'wps-finalpdf-admin-custom-setting-js',
                                'finalpdf_admin_custom_param',
                                array(
                                        'ajaxurl'            => admin_url( 'admin-ajax.php' ),
                                        'delete_loader'      => esc_html__( 'Deleting....', 'finalpdf' ),
                                        'nonce'              => wp_create_nonce( 'finalpdf_delete_media_by_id' ),
                                        'finalpdf_doc_dummy_img' => FINALPDF_DIR_URL . 'admin/src/images/document-management-big.png',
                                        'upload_doc'         => esc_html__( 'Upload Doc', 'finalpdf' ),
                                        'use_doc'            => esc_html__( 'Use Doc', 'finalpdf' ),
                                        'upload_image'       => esc_html__( 'Upload Image', 'finalpdf' ),
                                        'upload_invoice_image' => esc_html__( 'Upload Invoice Image', 'finalpdf' ),
                                        'use_image'          => esc_html__( 'Use Image', 'finalpdf' ),
                                        'confirm_text'       => esc_html__( 'Are you sure you want to delete Doc ?', 'finalpdf' ),
                                        'reset_confirm'      => esc_html__( 'Are you sure you want to reset all the settings to default ?', 'finalpdf' ),
                                        'reset_loader'       => FINALPDF_DIR_URL . 'admin/src/images/loader.gif',
                                        'reset_success'      => FINALPDF_DIR_URL . 'admin/src/images/checked.png',
                                        'reset_error'        => FINALPDF_DIR_URL . 'admin/src/images/cross.png',
                                )
                        );
                        $migration_success = get_option( 'wps_code_migratded' );
                        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'src/js/wpg-addon-admin.js', array( 'jquery' ), $this->version, false );
                        wp_enqueue_script( $this->plugin_name . '-swal', plugin_dir_url( __FILE__ ) . 'src/js/wpg-swal.js', array( 'jquery' ), $this->version, false );
                        wp_enqueue_script( $this->plugin_name . '-wps-swal', plugin_dir_url( __FILE__ ) . 'src/js/wps-wpg-swal.js', array( 'jquery' ), $this->version, false );
                        wp_localize_script(
                                $this->plugin_name,
                                'localised',
                                array(
                                        'ajaxurl'       => admin_url( 'admin-ajax.php' ),
                                        'nonce'         => wp_create_nonce( 'wps_finalpdf_migrated_nonce' ),
                                        'callback'      => 'finalpdf_ajax_callbacks',
                                        'pending_settings' => $this->wps_finalpdf_get_count( 'settings', 'count' ),
                                        'hide_import'   => $migration_success,
                                )
                        );
                }
        }

        /**
         * Adding settings menu for FinalPDF For WordPress.
         *
         * @since 1.0.0
         */
        public function finalpdf_options_page() {
                global $submenu;
                if ( empty( $GLOBALS['admin_page_hooks']['wps-plugins'] ) ) {
                        add_menu_page( 'Your Company', 'Your Company', 'manage_options', 'wps-plugins', array( $this, 'wps_plugins_listing_page' ), FINALPDF_DIR_URL . 'admin/src/images/finalpdf_logo.png', 15 );

                        add_submenu_page( 'wps-plugins', 'Home', 'Home', 'manage_options', 'home', array( $this, 'wps_finalpdf_welcome_callback_function' ), 1 );
                        $finalpdf_menus = apply_filters( 'wps_add_plugins_menus_array', array() );
                        if ( is_array( $finalpdf_menus ) && ! empty( $finalpdf_menus ) ) {
                                foreach ( $finalpdf_menus as $finalpdf_key => $finalpdf_value ) {
                                        add_submenu_page( 'wps-plugins', $finalpdf_value['name'], $finalpdf_value['name'], 'manage_options', $finalpdf_value['menu_link'], array( $finalpdf_value['instance'], $finalpdf_value['function'] ) );
                                }
                        }
                }
        }

        /**
         * Removing default submenu of parent menu in backend dashboard
         *
         * @since   1.0.0
         */
        public function wps_finalpdf_remove_default_submenu() {
                 global $submenu;
                if ( is_array( $submenu ) && array_key_exists( 'wps-plugins', $submenu ) ) {
                        if ( isset( $submenu['wps-plugins'][0] ) ) {
                                unset( $submenu['wps-plugins'][0] );
                        }
                }
        }
        /**
         * FinalPDF For WordPress finalpdf_admin_submenu_page.
         *
         * @since 1.0.0
         * @param array $menus Marketplace menus.
         */
        public function finalpdf_admin_submenu_page( $menus = array() ) {
                $menus[] = array(
                        'name'      => __( 'FinalPDF', 'finalpdf' ),
                        'slug'      => 'finalpdf_menu',
                        'menu_link' => 'finalpdf_menu',
                        'instance'  => $this,
                        'function'  => 'finalpdf_options_menu_html',
                );
                return $menus;
        }


        /**
         * FinalPDF For WordPress wps_plugins_listing_page.
         *
         * @since 1.0.0
         */
        public function wps_plugins_listing_page() {
                $active_marketplaces = apply_filters( 'wps_add_plugins_menus_array', array() );
                if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
                        require FINALPDF_DIR_PATH . 'admin/partials/welcome.php';
                }
        }

        /**
         * FinalPDF For WordPress admin menu page.
         *
         * @since    1.0.0
         */
        public function finalpdf_options_menu_html() {
                include_once FINALPDF_DIR_PATH . 'admin/partials/finalpdf-admin-dashboard.php';
        }
        /**
         * FinalPDF For WordPress admin menu page.
         *
         * @since    1.0.0
         * @param array $finalpdf_settings_general_html_arr Settings fields.
         * @return array
         */
        public function finalpdf_admin_general_settings_page( $finalpdf_settings_general_html_arr ) {
                $general_settings_data     = get_option( 'finalpdf_general_settings_save', array() );
                $finalpdf_enable_plugin        = array_key_exists( 'finalpdf_enable_plugin', $general_settings_data ) ? $general_settings_data['finalpdf_enable_plugin'] : '';
                $finalpdf_show_post_categories = array_key_exists( 'finalpdf_general_pdf_show_categories', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_show_categories'] : '';
                $finalpdf_show_post_tags       = array_key_exists( 'finalpdf_general_pdf_show_tags', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_show_tags'] : '';
                $finalpdf_show_post_taxonomy   = array_key_exists( 'finalpdf_general_pdf_show_taxonomy', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_show_taxonomy'] : '';
                $finalpdf_show_post_date       = array_key_exists( 'finalpdf_general_pdf_show_post_date', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_show_post_date'] : '';
                $finalpdf_show_post_author     = array_key_exists( 'finalpdf_general_pdf_show_author_name', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_show_author_name'] : '';
                $finalpdf_pdf_generate_mode    = array_key_exists( 'finalpdf_general_pdf_generate_mode', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_generate_mode'] : '';
                $finalpdf_pdf_file_name        = array_key_exists( 'finalpdf_general_pdf_file_name', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_file_name'] : '';
                $finalpdf_pdf_file_name_custom = array_key_exists( 'finalpdf_custom_pdf_file_name', $general_settings_data ) ? $general_settings_data['finalpdf_custom_pdf_file_name'] : '';
                $finalpdf_general_pdf_date_format    = array_key_exists( 'finalpdf_general_pdf_date_format', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_date_format'] : '';
                $finalpdf_show_current_date       = array_key_exists( 'finalpdf_general_pdf_show_current_date', $general_settings_data ) ? $general_settings_data['finalpdf_general_pdf_show_current_date'] : '';
                $finalpdf_settings_general_html_arr   = array(
                        array(
                                'title'       => __( 'Enable Plugin', 'finalpdf' ),
                                'type'        => 'radio-switch',
                                'description' => __( 'Enable plugin to start the functionality.', 'finalpdf' ),
                                'id'          => 'finalpdf_enable_plugin',
                                'value'       => $finalpdf_enable_plugin,
                                'class'       => 'finalpdf_enable_plugin',
                                'name'        => 'finalpdf_enable_plugin',
                                'options'     => array(
                                        'yes' => __( 'YES', 'finalpdf' ),
                                        'no'  => __( 'NO', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'        => __( 'Include Categories', 'finalpdf' ),
                                'type'         => 'checkbox',
                                'description'  => __( 'Categories will be shown on PDF( for post ).', 'finalpdf' ),
                                'id'           => 'finalpdf_general_pdf_show_categories',
                                'value'        => $finalpdf_show_post_categories,
                                'class'        => 'finalpdf_general_pdf_show_categories',
                                'name'         => 'finalpdf_general_pdf_show_categories',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                        ),
                        array(
                                'title'       => __( 'Include Tag', 'finalpdf' ),
                                'type'        => 'checkbox',
                                'description' => __( 'Tags will be shown on PDF( for post ).', 'finalpdf' ),
                                'id'          => 'finalpdf_general_pdf_show_tags',
                                'value'       => $finalpdf_show_post_tags,
                                'class'       => 'finalpdf_general_pdf_show_tags',
                                'name'        => 'finalpdf_general_pdf_show_tags',
                        ),
                        array(
                                'title'       => __( 'Include Taxonomy', 'finalpdf' ),
                                'type'        => 'checkbox',
                                'description' => __( 'Taxonomy will be shown on PDF( works for all post types ) this also includes category and tags for posts.', 'finalpdf' ),
                                'id'          => 'finalpdf_general_pdf_show_taxonomy',
                                'value'       => $finalpdf_show_post_taxonomy,
                                'class'       => 'finalpdf_general_pdf_show_taxonomy',
                                'name'        => 'finalpdf_general_pdf_show_taxonomy',
                        ),
                        array(
                                'title'       => __( 'Display Post Date', 'finalpdf' ),
                                'type'        => 'checkbox',
                                'description' => __( 'Post date will be shown on PDF.', 'finalpdf' ),
                                'id'          => 'finalpdf_general_pdf_show_post_date',
                                'value'       => $finalpdf_show_post_date,
                                'class'       => 'finalpdf_general_pdf_show_post_date',
                                'name'        => 'finalpdf_general_pdf_show_post_date',
                        ),
                        array(
                                'title'       => __( 'Display Current Date', 'finalpdf' ),
                                'type'        => 'checkbox',
                                'description' => __( 'Current date will be shown on PDF.', 'finalpdf' ),
                                'id'          => 'finalpdf_general_pdf_show_current_date',
                                'value'       => $finalpdf_show_current_date,
                                'class'       => 'finalpdf_general_pdf_show_current_date',
                                'name'        => 'finalpdf_general_pdf_show_current_date',
                        ),
                        array(
                                'title'       => __( 'Display Author Name', 'finalpdf' ),
                                'type'        => 'checkbox',
                                'description' => __( 'Author name will be shown on PDF.', 'finalpdf' ),
                                'id'          => 'finalpdf_general_pdf_show_author_name',
                                'value'       => $finalpdf_show_post_author,
                                'class'       => 'finalpdf_general_pdf_show_author_name',
                                'name'        => 'finalpdf_general_pdf_show_author_name',
                        ),
                        array(
                                'title'        => __( 'PDF Download Option', 'finalpdf' ),
                                'type'         => 'select',
                                'description'  => __( 'Please choose either to download or open window.', 'finalpdf' ),
                                'id'           => 'finalpdf_general_pdf_generate_mode',
                                'value'        => $finalpdf_pdf_generate_mode,
                                'class'        => 'finalpdf_general_pdf_generate_mode',
                                'name'         => 'finalpdf_general_pdf_generate_mode',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                                'options'      => array(
                                        ''                 => __( 'Select option', 'finalpdf' ),
                                        'download_locally' => __( 'Download Locally', 'finalpdf' ),
                                        'open_window'      => __( 'Open Window', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'        => __( 'Date Format', 'finalpdf' ),
                                'type'         => 'select',
                                'description'  => __( 'Select date format for your dates on PDF template.', 'finalpdf' ),
                                'id'           => 'finalpdf_general_pdf_date_format',
                                'value'        => $finalpdf_general_pdf_date_format,
                                'class'        => 'finalpdf_general_pdf_date_format',
                                'name'         => 'finalpdf_general_pdf_date_format',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                                'options'      => array(
                                        'Y/m/d' => 'yyyy/mm/dd',
                                        'm/d/Y' => 'mm/dd/yyyy',
                                        'd M Y' => 'd MM yyyy',
                                        'l, d M Y' => 'DD, d MM yyyy',
                                        'Y-m-d' => 'yyyy-mm-dd',
                                        'd/m/Y' => 'dd/mm/yyyy',
                                        'd.m.Y' => 'd.m.yyyy',
                                ),
                        ),
                        array(
                                'title'       => __( 'Default File Name', 'finalpdf' ),
                                'type'        => 'select',
                                'description' => __( 'File name will be used as the name of the PDF generated.', 'finalpdf' ),
                                'id'          => 'finalpdf_general_pdf_file_name',
                                'value'       => $finalpdf_pdf_file_name,
                                'class'       => 'finalpdf_general_pdf_file_name',
                                'name'        => 'finalpdf_general_pdf_file_name',
                                'options'     => array(
                                        ''                   => __( 'Select option', 'finalpdf' ),
                                        'post_name'          => __( 'Post Name', 'finalpdf' ),
                                        'document_productid' => __( 'Document_ProductID', 'finalpdf' ),
                                        'custom'             => __( 'Custom', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Please Enter the Custom File Name', 'finalpdf' ),
                                'type'        => 'text',
                                'description' => __( 'For custom file name, product/page/post id will be used as suffix.', 'finalpdf' ),
                                'id'          => 'finalpdf_custom_pdf_file_name',
                                'class'       => 'finalpdf_custom_pdf_file_name',
                                'name'        => 'finalpdf_custom_pdf_file_name',
                                'value'       => $finalpdf_pdf_file_name_custom,
                                'style'       => ( 'custom' !== $finalpdf_pdf_file_name ) ? 'display:none;' : '',
                                'placeholder' => __( 'File Name', 'finalpdf' ),
                        ),
                );
                $finalpdf_settings_general_html_arr   = apply_filters( 'finalpdf_settings_general_html_arr_filter_hook', $finalpdf_settings_general_html_arr );
                $finalpdf_settings_general_html_arr[] = array(
                        'type'        => 'button',
                        'id'          => 'finalpdf_general_settings_save',
                        'button_text' => __( 'Save Settings', 'finalpdf' ),
                        'class'       => 'finalpdf_general_settings_save',
                        'name'        => 'finalpdf_general_settings_save',
                );
                return $finalpdf_settings_general_html_arr;
        }
        /**
         * FinalPDF For WordPress save tab settings.
         *
         * @since 1.0.0
         * @return void
         */
        public function finalpdf_admin_save_tab_settings() {
                global $finalpdf_obj, $wps_finalpdf_gen_flag, $finalpdf_save_check_flag;
                $settings_general_arr = array();
                $finalpdf_save_check_flag = false;
                if ( wp_doing_ajax() ) {
                        return;
                }
                if ( isset( $_POST['finalpdf_tracking_save_button'] ) ) {

                        $enable_tracking = ! empty( $_POST['finalpdf_enable_tracking'] ) ? sanitize_text_field( wp_unslash( $_POST['finalpdf_enable_tracking'] ) ) : '';
                        update_option( 'finalpdf_enable_tracking', $enable_tracking );
                }
                if ( isset( $_POST['finalpdf_nonce_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['finalpdf_nonce_field'] ) ), 'nonce_settings_save' ) ) {
                        if ( isset( $_POST['finalpdf_general_settings_save'] ) ) {
                                $finalpdf_genaral_settings = apply_filters( 'finalpdf_general_settings_array', array() );
                                $key                   = 'finalpdf_general_settings_save';
                                $finalpdf_save_check_flag  = true;
                        } elseif ( isset( $_POST['finalpdf_save_admin_display_settings'] ) ) {
                                $finalpdf_genaral_settings = apply_filters( 'finalpdf_display_settings_array', array() );
                                $key                   = 'finalpdf_save_admin_display_settings';
                                $finalpdf_save_check_flag  = true;
                        } elseif ( isset( $_POST['finalpdf_header_setting_submit'] ) ) {
                                $finalpdf_genaral_settings = apply_filters( 'finalpdf_header_settings_array', array() );
                                $key                   = 'finalpdf_header_setting_submit';
                                $finalpdf_save_check_flag  = true;
                        } elseif ( isset( $_POST['finalpdf_footer_setting_submit'] ) ) {
                                $finalpdf_genaral_settings = apply_filters( 'finalpdf_footer_settings_array', array() );
                                $key                   = 'finalpdf_footer_setting_submit';
                                $finalpdf_save_check_flag  = true;
                        } elseif ( isset( $_POST['finalpdf_body_save_settings'] ) ) {
                                $finalpdf_genaral_settings = apply_filters( 'finalpdf_body_settings_array', array() );
                                $key                   = 'finalpdf_body_save_settings';
                                $finalpdf_save_check_flag  = true;
                        } elseif ( isset( $_POST['finalpdf_advanced_save_settings'] ) ) {
                                $finalpdf_genaral_settings = apply_filters( 'finalpdf_advanced_settings_array', array() );
                                $key                   = 'finalpdf_advanced_save_settings';
                                $finalpdf_save_check_flag  = true;
                        } elseif ( isset( $_POST['finalpdf_meta_fields_save_settings'] ) ) {
                                $finalpdf_genaral_settings = apply_filters( 'finalpdf_meta_fields_settings_array', array() );
                                $key                   = 'finalpdf_meta_fields_save_settings';
                                $finalpdf_save_check_flag  = true;
                        } elseif ( isset( $_POST['finalpdf_pdf_upload_save_settings'] ) ) {
                                $finalpdf_genaral_settings = apply_filters( 'finalpdf_pdf_upload_fields_settings_array', array() );
                                $key                   = 'finalpdf_pdf_upload_save_settings';
                                $finalpdf_save_check_flag  = true;
                        }

                        if ( $finalpdf_save_check_flag ) {
                                $wps_finalpdf_gen_flag = false;
                                $finalpdf_button_index = array_search( 'submit', array_column( $finalpdf_genaral_settings, 'type' ), true );
                                if (isset($finalpdf_button_index) && (null == $finalpdf_button_index || '' === $finalpdf_button_index)) { // phpcs:ignore
                                        $finalpdf_button_index = array_search( 'button', array_column( $finalpdf_genaral_settings, 'type' ), true );
                                }
                                if ( isset( $finalpdf_button_index ) && '' !== $finalpdf_button_index ) {
                                        unset( $finalpdf_genaral_settings[ $finalpdf_button_index ] );
                                        if ( is_array( $finalpdf_genaral_settings ) && ! empty( $finalpdf_genaral_settings ) ) {
                                                foreach ( $finalpdf_genaral_settings as $finalpdf_genaral_setting ) {
                                                        if ( isset( $finalpdf_genaral_setting['id'] ) && '' !== $finalpdf_genaral_setting['id'] ) {
                                                                if ( 'multi' === $finalpdf_genaral_setting['type'] ) {
                                                                        $finalpdf_general_settings_sub_arr = $finalpdf_genaral_setting['value'];
                                                                        foreach ( $finalpdf_general_settings_sub_arr as $finalpdf_genaral_setting ) {
                                                                                if ( isset( $_POST[ $finalpdf_genaral_setting['id'] ] ) ) {
                                                                                        $settings_general_arr[ $finalpdf_genaral_setting['id'] ] = is_array( $_POST[ $finalpdf_genaral_setting['id'] ] ) ? map_deep( wp_unslash( $_POST[ $finalpdf_genaral_setting['id'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $finalpdf_genaral_setting['id'] ] ) );
                                                                                } else {
                                                                                        $settings_general_arr[ $finalpdf_genaral_setting['id'] ] = '';
                                                                                }
                                                                        }
                                                                } elseif ( 'multiwithcheck' === $finalpdf_genaral_setting['type'] ) {
                                                                        $finalpdf_general_settings_sub_arr = $finalpdf_genaral_setting['value'];
                                                                        foreach ( $finalpdf_general_settings_sub_arr as $finalpdf_genaral_setting ) {
                                                                                if ( isset( $_POST[ $finalpdf_genaral_setting['name'] ] ) ) {
                                                                                        $settings_general_arr[ $finalpdf_genaral_setting['name'] ] = is_array( $_POST[ $finalpdf_genaral_setting['name'] ] ) ? map_deep( wp_unslash( $_POST[ $finalpdf_genaral_setting['name'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $finalpdf_genaral_setting['name'] ] ) );
                                                                                } else {
                                                                                        $settings_general_arr[ $finalpdf_genaral_setting['name'] ] = '';
                                                                                }
                                                                                if ( isset( $_POST[ $finalpdf_genaral_setting['checkbox_id'] ] ) ) {
                                                                                        $settings_general_arr[ $finalpdf_genaral_setting['checkbox_id'] ] = is_array( $_POST[ $finalpdf_genaral_setting['checkbox_id'] ] ) ? map_deep( wp_unslash( $_POST[ $finalpdf_genaral_setting['checkbox_id'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $finalpdf_genaral_setting['checkbox_id'] ] ) );
                                                                                } else {
                                                                                        $settings_general_arr[ $finalpdf_genaral_setting['checkbox_id'] ] = '';
                                                                                }
                                                                        }
                                                                } elseif ( 'file' === $finalpdf_genaral_setting['type'] ) {
                                                                        if ( isset( $_FILES[ $finalpdf_genaral_setting['id'] ]['name'] ) && isset( $_FILES[ $finalpdf_genaral_setting['id'] ]['tmp_name'] ) ) {
                                                                                $file_name_to_upload = sanitize_text_field( wp_unslash( $_FILES[ $finalpdf_genaral_setting['id'] ]['name'] ) );
                                                                                $file_to_upload      = sanitize_text_field( wp_unslash( $_FILES[ $finalpdf_genaral_setting['id'] ]['tmp_name'] ) );
                                                                                $upload_dir          = wp_upload_dir();
                                                                                $upload_basedir      = $upload_dir['basedir'] . '/finalpdf_ttf_font/';
                                                                                if ( ! file_exists( $upload_basedir ) ) {
                                                                                        wp_mkdir_p( $upload_basedir );
                                                                                }
                                                                                $target_file = $upload_basedir . basename( $file_name_to_upload );
                                                                                $file_type   = strtolower( pathinfo( $target_file, PATHINFO_EXTENSION ) );
                                                                                if ( 'ttf' === $file_type ) {
                                                                                        if ( ! file_exists( $target_file ) ) {
                                                                                                copy( $file_to_upload, $target_file );
                                                                                        }
                                                                                        $settings_general_arr[ $finalpdf_genaral_setting['id'] ] = $file_name_to_upload;
                                                                                } else {
                                                                                        $settings_general_arr[ $finalpdf_genaral_setting['id'] ] = $finalpdf_genaral_setting['value'];
                                                                                }
                                                                        } else {
                                                                                $settings_general_arr[ $finalpdf_genaral_setting['id'] ] = '';
                                                                        }
                                                                } else {
                                                                        if ( isset( $_POST[ $finalpdf_genaral_setting['id'] ] ) ) {
                                                                                $settings_general_arr[ $finalpdf_genaral_setting['id'] ] = is_array( $_POST[ $finalpdf_genaral_setting['id'] ] ) ? map_deep( wp_unslash( $_POST[ $finalpdf_genaral_setting['id'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $finalpdf_genaral_setting['id'] ] ) );
                                                                        } else {
                                                                                $settings_general_arr[ $finalpdf_genaral_setting['id'] ] = '';
                                                                        }
                                                                }
                                                        } else {
                                                                $wps_finalpdf_gen_flag = true;
                                                        }
                                                }
                                        }
                                        if ( ! $wps_finalpdf_gen_flag ) {
                                                update_option( $key, $settings_general_arr );
                                        }
                                }
                        }
                }
        }
        /**
         * Html fields for display setting.
         *
         * @since 1.0.0
         * @param array $finalpdf_settings_display_fields_html_arr array containing html fields.
         * @return array
         */
        public function finalpdf_admin_display_settings_page( $finalpdf_settings_display_fields_html_arr ) {
                $finalpdf_display_settings                   = get_option( 'finalpdf_save_admin_display_settings', array() );
                $finalpdf_template_color               = array_key_exists( 'finalpdf_template_color', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_template_color'] : '#FFFFFF';
                $finalpdf_template_text_color       = array_key_exists( 'finalpdf_template_text_color', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_template_text_color'] : '#000000';
                $finalpdf_user_access                        = array_key_exists( 'finalpdf_user_access', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_user_access'] : '';
                $finalpdf_guest_access                       = array_key_exists( 'finalpdf_guest_access', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_guest_access'] : '';
                $finalpdf_guest_download_or_email            = array_key_exists( 'finalpdf_guest_download_or_email', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_guest_download_or_email'] : '';
                $finalpdf_user_download_or_email             = array_key_exists( 'finalpdf_user_download_or_email', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_user_download_or_email'] : '';
                $finalpdf_pdf_icon_after                     = array_key_exists( 'finalpdf_display_pdf_icon_after', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_display_pdf_icon_after'] : '';
                $finalpdf_pdf_icon_alignment                 = array_key_exists( 'finalpdf_display_pdf_icon_alignment', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_display_pdf_icon_alignment'] : '';
                $sub_pgfw_pdf_single_download_icon       = array_key_exists( 'sub_pgfw_pdf_single_download_icon', $finalpdf_display_settings ) ? $finalpdf_display_settings['sub_pgfw_pdf_single_download_icon'] : '';
                $finalpdf_pdf_icon_width                     = array_key_exists( 'finalpdf_pdf_icon_width', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_pdf_icon_width'] : '';
                $finalpdf_pdf_icon_height                    = array_key_exists( 'finalpdf_pdf_icon_height', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_pdf_icon_height'] : '';
                $finalpdf_body_show_pdf_icon                 = array_key_exists( 'finalpdf_body_show_pdf_icon', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_body_show_pdf_icon'] : '';
                $finalpdf_show_post_type_icons_for_user_role = array_key_exists( 'finalpdf_show_post_type_icons_for_user_role', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_show_post_type_icons_for_user_role'] : '';

                $finalpdf_template_color_option                 = array_key_exists( 'finalpdf_template_color_option', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_template_color_option'] : '';

                global $wp_roles;
                $all_roles = $wp_roles->roles;
                $roles_array = array();

                foreach ( $all_roles as $role => $value ) {

                        $roles_array[ $role ] = $role;
                }
                $finalpdf_pdf_icon_places              = array(
                        ''               => __( 'Select option', 'finalpdf' ),
                        'before_content' => __( 'Before Content', 'finalpdf' ),
                        'after_content'  => __( 'After Content', 'finalpdf' ),
                );
                if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
                        $woocommerce_hook_arr = array(
                                'woocommerce_before_add_to_cart_form'      => __( 'Before Add to Cart Form', 'finalpdf' ),
                                'woocommerce_product_meta_start'           => __( 'Before Product Meta Start', 'finalpdf' ),
                                'woocommerce_product_meta_end'             => __( 'After Add to Cart Form', 'finalpdf' ),
                                'woocommerce_after_single_product_summary' => __( 'After Single Product Summary', 'finalpdf' ),
                                'woocommerce_before_single_product_summary' => __( 'Before Single Product Summary', 'finalpdf' ),
                                'woocommerce_after_single_product'         => __( 'After Single Product', 'finalpdf' ),
                                'woocommerce_before_single_product'        => __( 'Before Single Product', 'finalpdf' ),
                                'woocommerce_share'                        => __( 'After Share Button', 'finalpdf' ),
                        );
                        foreach ( $woocommerce_hook_arr as $hooks => $name ) {
                                $finalpdf_pdf_icon_places[ $hooks ] = $name;
                        }
                }

                $finalpdf_settings_display_fields_html_arr   = array(
                        array(
                                'title'       => __( 'Logged in Users', 'finalpdf' ),
                                'type'        => 'radio-switch',
                                'description' => __( 'Enable this to give access to logged in users to download PDF.', 'finalpdf' ),
                                'id'          => 'finalpdf_user_access',
                                'value'       => $finalpdf_user_access,
                                'class'       => 'finalpdf_user_access',
                                'name'        => 'finalpdf_user_access',
                                'options'     => array(
                                        'yes' => __( 'YES', 'finalpdf' ),
                                        'no'  => __( 'NO', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Guest', 'finalpdf' ),
                                'type'        => 'radio-switch',
                                'description' => __( 'Enable this to give access to guest users to download PDF.', 'finalpdf' ),
                                'id'          => 'finalpdf_guest_access',
                                'value'       => $finalpdf_guest_access,
                                'class'       => 'finalpdf_guest_access',
                                'name'        => 'finalpdf_guest_access',
                                'options'     => array(
                                        'yes' => __( 'YES', 'finalpdf' ),
                                        'no'  => __( 'NO', 'finalpdf' ),
                                ),
                        ),

                        array(
                                'title'       => __( 'Enable Bulk Download', 'finalpdf' ),
                                'type'        => 'radio-switch',
                                'description' => __( 'Enable this to give access to bulk download PDF', 'finalpdf' ),
                                'id'          => 'finalpdf_bulk_download_enable',
                                'value'       => '$finalpdf_bulk_download_enable',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'options'     => array(
                                        'yes' => __( 'YES', 'finalpdf' ),
                                        'no'  => __( 'NO', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Enable Print Option', 'finalpdf' ),
                                'type'        => 'radio-switch',
                                'description' => __( 'Enable this to print current window screen', 'finalpdf' ),
                                'id'          => 'finalpdf_print_enable_org_tag',
                                'value'       => 'finalpdf_print_enable_org_tag',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'options'     => array(
                                        'yes' => __( 'YES', 'finalpdf' ),
                                        'no'  => __( 'NO', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Enable WhatsApp Sharing Icon', 'finalpdf' ),
                                'type'        => 'radio-switch',
                                'description' => __( 'Enable this to share PDF over WhatsApp', 'finalpdf' ),
                                'id'          => 'finalpdf_whatsapp_sharing',
                                'value'       => '',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_whatsapp_sharing',
                                'options'     => array(
                                        'yes' => __( 'YES', 'finalpdf' ),
                                        'no'  => __( 'NO', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'        => __( 'Download Invoice icon change option', 'finalpdf' ),
                                'type'         => 'upload-button',
                                'button_text'  => __( 'Upload Icon', 'finalpdf' ),
                                'class'        => 'wps_finalpdf_pro_tag',
                                'id'           => 'sub_pgfw_pdf_invoice_single_download_icon',
                                'value'        => '',
                                'sub_id'       => 'finalpdf_pdf_invoice_single_download_icon',
                                'sub_class'    => 'finalpdf_pdf_invoice_single_download_icon',
                                'sub_name'     => 'finalpdf_pdf_invoice_single_download_icon',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                                'description'  => __( 'If no icon is chosen default icon will be used.', 'finalpdf' ),
                                'img-tag'      => array(
                                        'img-class' => 'finalpdf_single_pdf_icon_image_invoice',
                                        'img-id'    => 'finalpdf_single_pdf_icon_image_invoice',
                                        'img-style' => ( '' ) ? 'margin:10px;height:45px;width:45px;' : 'display:none;margin:10px;height:45px;width:45px;',
                                        'img-src'   => '',
                                ),
                                'img-remove'   => array(
                                        'btn-class' => '',
                                        'btn-id'    => 'finalpdf_single_pdf_invoice_icon_image_remove',
                                        'btn-text'  => __( 'Remove Icon', 'finalpdf' ),
                                        'btn-title' => __( 'Remove Icon', 'finalpdf' ),
                                        'btn-name'  => 'e',
                                        'btn-style' => '',
                                ),
                        ),
                        array(
                                'title'        => __( 'Direct Download or Email User', 'finalpdf' ),
                                'type'         => 'select',
                                'description'  => __( 'Please choose either to direct download or to email user.', 'finalpdf' ),
                                'id'           => 'finalpdf_user_download_or_email',
                                'value'        => $finalpdf_user_download_or_email,
                                'class'        => 'finalpdf_user_download_or_email',
                                'name'         => 'finalpdf_user_download_or_email',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                                'options'      => array(
                                        ''                => __( 'Select option', 'finalpdf' ),
                                        'direct_download' => __( 'Direct Download', 'finalpdf' ),
                                        'email'           => __( 'Email', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'        => __( 'Direct Download or Email Guest', 'finalpdf' ),
                                'type'         => 'select',
                                'description'  => __( 'Please choose either to direct download or to email guest.', 'finalpdf' ),
                                'id'           => 'finalpdf_guest_download_or_email',
                                'value'        => $finalpdf_guest_download_or_email,
                                'class'        => 'finalpdf_guest_download_or_email',
                                'name'         => 'finalpdf_guest_download_or_email',
                                'parent-class' => '',
                                'options'      => array(
                                        ''                => __( 'Select option', 'finalpdf' ),
                                        'direct_download' => __( 'Direct Download', 'finalpdf' ),
                                        'email'           => __( 'Email', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'        => __( 'Show PDF Icon', 'finalpdf' ),
                                'type'         => 'select',
                                'description'  => __( 'PDF Icon will be shown after selected space.', 'finalpdf' ),
                                'id'           => 'finalpdf_display_pdf_icon_after',
                                'value'        => $finalpdf_pdf_icon_after,
                                'class'        => 'finalpdf_display_pdf_icon_after',
                                'name'         => 'finalpdf_display_pdf_icon_after',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                                'options'      => $finalpdf_pdf_icon_places,
                        ),
                        array(
                                'title'       => __( 'PDF Icon Alignment', 'finalpdf' ),
                                'type'        => 'select',
                                'description' => __( 'PDF Icon will be aligned according to the selected value.', 'finalpdf' ),
                                'id'          => 'finalpdf_display_pdf_icon_alignment',
                                'value'       => $finalpdf_pdf_icon_alignment,
                                'class'       => 'finalpdf_display_pdf_icon_alignment',
                                'name'        => 'finalpdf_display_pdf_icon_alignment',
                                'options'     => array(
                                        ''       => __( 'Please Choose', 'finalpdf' ),
                                        'flex-start'   => __( 'Left', 'finalpdf' ),
                                        'center' => __( 'Center', 'finalpdf' ),
                                        'flex-end'  => __( 'Right', 'finalpdf' ),
                                ),
                        ),

                        array(
                                'title'       => __( 'Choose Bulk Download PDF Icon', 'finalpdf' ),
                                'type'        => 'upload-button',
                                'button_text' => __( 'Upload Icon', 'finalpdf' ),
                                'class'       => 'wps_finalpdf_pro_tag',
                                'id'          => 'wps_finalpdf_pdf_bulk_download_icon',
                                'value'       => '',
                                'sub_id'      => 'wps_finalpdf_pdf_bulk_download_icon',
                                'sub_class'   => 'wps_finalpdf_pdf_bulk_download_icon',
                                'sub_name'    => 'wps_finalpdf_pdf_bulk_download_icon',
                                'name'        => 'wps_finalpdf_pdf_bulk_download_icon',
                                'description' => __( 'If no icon is chosen default icon will be used', 'finalpdf' ),
                                'img-tag'     => array(
                                        'img-class' => 'wps_bulk_pdf_icon_image',
                                        'img-id'    => 'wps_bulk_pdf_icon_image',
                                        'img-style' => ( '' ) ? 'margin:10px;height:45px;width:45px;' : 'display:none;margin:10px;height:45px;width:45px;',
                                        'img-src'   => '',
                                ),
                                'img-remove'  => array(
                                        'btn-class' => 'wps_bulk_pdf_icon_image_remove',
                                        'btn-id'    => 'wps_bulk_pdf_icon_image_remove',
                                        'btn-text'  => __( 'Remove Icon', 'finalpdf' ),
                                        'btn-title' => __( 'Remove Icon', 'finalpdf' ),
                                        'btn-name'  => 'wps_bulk_pdf_icon_image_remove',
                                        'btn-style' => ! ( '' ) ? 'display:none' : '',
                                ),
                        ),

                        array(
                                'title'       => __( 'Bulk Download PDF Icon Name', 'finalpdf' ),
                                'type'        => 'text',
                                'id'          => 'bulk_pdf_icon_name',
                                'value'       => 'bulk download name',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'bulk_pdf_icon_name',
                                'placeholder' => __( 'Icon Name', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Single Download PDF Icon Name ', 'finalpdf' ),
                                'type'        => 'text',
                                'id'          => 'single_pdf_icon_name',
                                'value'       => 'single pdf name',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'single_pdf_icon_name',
                                'placeholder' => __( 'Icon Name', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Icon Size', 'finalpdf' ),
                                'type'        => 'multi',
                                'id'          => 'finalpdf_pdf_icons_sizes',
                                'description' => __( 'Enter icon width and height in pixels.', 'finalpdf' ),
                                'value'       => array(
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_pdf_icon_width',
                                                'class'       => 'finalpdf_pdf_icon_width',
                                                'name'        => 'finalpdf_pdf_icon_width',
                                                'placeholder' => __( 'width', 'finalpdf' ),
                                                'value'       => $finalpdf_pdf_icon_width,
                                                'min'         => 0,
                                                'max'         => 50,
                                        ),
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_pdf_icon_height',
                                                'class'       => 'finalpdf_pdf_icon_height',
                                                'name'        => 'finalpdf_pdf_icon_height',
                                                'placeholder' => __( 'height', 'finalpdf' ),
                                                'value'       => $finalpdf_pdf_icon_height,
                                                'min'         => 0,
                                                'max'         => 50,
                                        ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Show Pdf Icon According To User Roles', 'finalpdf' ),
                                'type'        => 'radio-switch',
                                'description' => __( 'Check this if you want to show download PDF icon according to the user roles .', 'finalpdf' ),
                                'id'          => 'finalpdf_body_show_pdf_icon',
                                'value'       => $finalpdf_body_show_pdf_icon,
                                'class'       => 'finalpdf_body_show_pdf_icon',
                                'name'        => 'finalpdf_body_show_pdf_icon',
                        ),
                        array(
                                'title'       => __( 'Select User Role For Which You Want To Show The PDF Icon', 'finalpdf' ),
                                'type'        => 'multiselect',
                                'description' => __( 'Select all user roles for which you want to show the PDF download icon  ', 'finalpdf' ),
                                'id'          => 'finalpdf_show_post_type_icons_for_user_role',
                                'value'       => $finalpdf_show_post_type_icons_for_user_role,
                                'class'       => 'pgfw-multiselect-class wps-defaut-multiselect finalpdf_show_post_type_icons_for_user_role',
                                'name'        => 'finalpdf_show_post_type_icons_for_user_role',
                                'options'     => $roles_array,
                        ),
                        array(
                                'title'        => __( 'Choose Single Download PDF Icon', 'finalpdf' ),
                                'type'         => 'upload-button',
                                'button_text'  => __( 'Upload Icon', 'finalpdf' ),
                                'class'        => 'sub_pgfw_pdf_single_download_icon',
                                'id'           => 'sub_pgfw_pdf_single_download_icon',
                                'value'        => $sub_pgfw_pdf_single_download_icon,
                                'sub_id'       => 'finalpdf_pdf_single_download_icon',
                                'sub_class'    => 'finalpdf_pdf_single_download_icon',
                                'sub_name'     => 'finalpdf_pdf_single_download_icon',
                                'name'         => 'sub_pgfw_pdf_single_download_icon',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                                'description'  => __( 'If no icon is chosen default icon will be used.', 'finalpdf' ),
                                'img-tag'      => array(
                                        'img-class' => 'finalpdf_single_pdf_icon_image',
                                        'img-id'    => 'finalpdf_single_pdf_icon_image',
                                        'img-style' => ( $sub_pgfw_pdf_single_download_icon ) ? 'margin:10px;height:45px;width:45px;' : 'display:none;margin:10px;height:45px;width:45px;',
                                        'img-src'   => $sub_pgfw_pdf_single_download_icon,
                                ),
                                'img-remove'   => array(
                                        'btn-class' => 'finalpdf_single_pdf_icon_image_remove',
                                        'btn-id'    => 'finalpdf_single_pdf_icon_image_remove',
                                        'btn-text'  => __( 'Remove Icon', 'finalpdf' ),
                                        'btn-title' => __( 'Remove Icon', 'finalpdf' ),
                                        'btn-name'  => 'finalpdf_single_pdf_icon_image_remove',
                                        'btn-style' => ! ( $sub_pgfw_pdf_single_download_icon ) ? 'display:none' : '',
                                ),
                        ),
                        array(
                                'title'       => __( 'PDF Template Color ', 'finalpdf' ),
                                'type'        => 'radio-switch',
                                'description' => __( 'Enable this radio button if you want to change PDF template and text color.', 'finalpdf' ),
                                'id'          => 'finalpdf_template_color_option',
                                'value'       => $finalpdf_template_color_option,
                                'class'       => 'finalpdf_template_color_option',
                                'name'        => 'finalpdf_template_color_option',
                        ),
                        array(
                                'title'        => __( 'Choose PDF Template Colour', 'finalpdf' ),
                                'type'         => 'color',
                                'description'  => __( 'Choose color to display PDF Template.', 'finalpdf' ),
                                'id'           => 'finalpdf_template_color',
                                'value'        => $finalpdf_template_color,
                                'class'        => 'finalpdf_color_picker finalpdf_body_font_color',
                                'name'         => 'finalpdf_template_color',
                                'placeholder'  => __( 'color', 'finalpdf' ),
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                        ),
                        array(
                                'title'        => __( 'Choose PDF Template Text Colour', 'finalpdf' ),
                                'type'         => 'color',
                                'description'  => __( 'Choose color to display PDF Template Text.', 'finalpdf' ),
                                'id'           => 'finalpdf_template_text_color',
                                'value'        => $finalpdf_template_text_color,
                                'class'        => 'finalpdf_color_picker finalpdf_body_font_color',
                                'name'         => 'finalpdf_template_text_color',
                                'placeholder'  => __( 'color', 'finalpdf' ),
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                        ),

                );
                $finalpdf_settings_display_fields_html_arr   = apply_filters( 'finalpdf_settings_display_fields_html_arr_filter_hook', $finalpdf_settings_display_fields_html_arr );
                $finalpdf_settings_display_fields_html_arr[] = array(
                        'type'        => 'button',
                        'id'          => 'finalpdf_save_admin_display_settings',
                        'button_text' => __( 'Save Settings', 'finalpdf' ),
                        'class'       => 'finalpdf_save_admin_display_settings',
                        'name'        => 'finalpdf_save_admin_display_settings',
                );
                return $finalpdf_settings_display_fields_html_arr;
        }
        /**
         * Html fields for header custmization.
         *
         * @since 1.0.0
         *
         * @param array $finalpdf_settings_header_fields_html_arr array of fields containing html.
         * @return array
         */
        public function finalpdf_admin_header_settings_page( $finalpdf_settings_header_fields_html_arr ) {
                $finalpdf_header_settings   = get_option( 'finalpdf_header_setting_submit', array() );
                $finalpdf_header_use_in_pdf = array_key_exists( 'finalpdf_header_use_in_pdf', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_use_in_pdf'] : '';
                $finalpdf_header_logo       = array_key_exists( 'sub_pgfw_header_image_upload', $finalpdf_header_settings ) ? $finalpdf_header_settings['sub_pgfw_header_image_upload'] : '';
                $finalpdf_header_comp_name  = array_key_exists( 'finalpdf_header_company_name', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_company_name'] : '';
                $finalpdf_header_tagline    = array_key_exists( 'finalpdf_header_tagline', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_tagline'] : '';
                $finalpdf_header_color      = array_key_exists( 'finalpdf_header_color', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_color'] : '';
                $finalpdf_header_width      = array_key_exists( 'finalpdf_header_width', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_width'] : '';
                $finalpdf_header_font_style = array_key_exists( 'finalpdf_header_font_style', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_font_style'] : '';
                $finalpdf_header_font_size  = array_key_exists( 'finalpdf_header_font_size', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_font_size'] : '';
                $finalpdf_header_top        = array_key_exists( 'finalpdf_header_top', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_top'] : '';
                $finalpdf_header_logo_size  = array_key_exists( 'finalpdf_header_logo_size', $finalpdf_header_settings ) ? $finalpdf_header_settings['finalpdf_header_logo_size'] : '30';
                $wps_finalpdf_font_styles   = array(
                        ''            => __( 'Select option', 'finalpdf' ),
                        'helvetica'   => __( 'Helvetica', 'finalpdf' ),
                        'courier'     => __( 'Courier', 'finalpdf' ),
                        'sans-serif'  => __( 'Sans Serif', 'finalpdf' ),
                        'DejaVu Sans' => __( 'DejaVu Sans', 'finalpdf' ),
                        'times-roman' => __( 'Times-Roman', 'finalpdf' ),
                        'symbol'      => __( 'Symbol', 'finalpdf' ),
                        'zapfdinbats' => __( 'Zapfdinbats', 'finalpdf' ),
                );
                $wps_finalpdf_font_styles   = apply_filters( 'wps_finalpdf_font_styles_filter_hook', $wps_finalpdf_font_styles );

                $finalpdf_settings_header_fields_html_arr   = array(
                        array(
                                'title'       => __( 'Include Header', 'finalpdf' ),
                                'type'        => 'checkbox',
                                'description' => __( 'Select this to include header on the page.', 'finalpdf' ),
                                'id'          => 'finalpdf_header_use_in_pdf',
                                'value'       => $finalpdf_header_use_in_pdf,
                                'class'       => 'finalpdf_header_use_in_pdf',
                                'name'        => 'finalpdf_header_use_in_pdf',
                        ),
                        array(
                                'title'       => __( 'Choose Logo', 'finalpdf' ),
                                'type'        => 'upload-button',
                                'button_text' => __( 'Upload Image', 'finalpdf' ),
                                'sub_class'   => 'finalpdf_header_image_upload',
                                'sub_id'      => 'finalpdf_header_image_upload',
                                'id'          => 'sub_pgfw_header_image_upload',
                                'name'        => 'sub_pgfw_header_image_upload',
                                'class'       => 'sub_pgfw_header_image_upload',
                                'value'       => $finalpdf_header_logo,
                                'sub_name'    => 'finalpdf_header_image_upload',
                                'img-tag'     => array(
                                        'img-class' => 'finalpdf_header_image',
                                        'img-id'    => 'finalpdf_header_image',
                                        'img-style' => ( $finalpdf_header_logo ) ? 'margin-right:10px;width:100px;height:100px;' : 'display:none;margin:10px;width:100px;height:100px;',
                                        'img-src'   => $finalpdf_header_logo,
                                ),
                                'img-remove'  => array(
                                        'btn-class' => 'finalpdf_header_image_remove',
                                        'btn-id'    => 'finalpdf_header_image_remove',
                                        'btn-text'  => __( 'Remove image', 'finalpdf' ),
                                        'btn-title' => __( 'Remove image', 'finalpdf' ),
                                        'btn-name'  => 'finalpdf_header_image_remove',
                                        'btn-style' => ! ( $finalpdf_header_logo ) ? 'display:none' : '',
                                ),
                        ),
                        array(
                                'title'       => __( 'Logo Size', 'finalpdf' ),
                                'type'        => 'number',
                                'description' => __( 'Enter header logo width size in (px) . ', 'finalpdf' ),
                                'id'          => 'finalpdf_header_logo_size',
                                'value'       => $finalpdf_header_logo_size,
                                'class'       => 'finalpdf_header_logo_size',
                                'name'        => 'finalpdf_header_logo_size',
                                'placeholder' => __( 'width', 'finalpdf' ),
                                'min'         => 5,
                                'max'         => 150,
                        ),
                        array(
                                'title'       => __( 'Company Name', 'finalpdf' ),
                                'type'        => 'text',
                                'description' => __( 'Company name will be displayed on the right side of the header', 'finalpdf' ),
                                'id'          => 'finalpdf_header_company_name',
                                'value'       => $finalpdf_header_comp_name,
                                'class'       => 'finalpdf_header_company_name',
                                'name'        => 'finalpdf_header_company_name',
                                'placeholder' => __( 'company name', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Tagline or Address', 'finalpdf' ),
                                'type'        => 'textarea',
                                'class'       => 'finalpdf_header_tagline',
                                'id'          => 'finalpdf_header_tagline',
                                'name'        => 'finalpdf_header_tagline',
                                'description' => __( 'Enter the tagline or address to show in header', 'finalpdf' ),
                                'placeholder' => __( 'tagline or address', 'finalpdf' ),
                                'value'       => $finalpdf_header_tagline,
                        ),
                        array(
                                'title'       => __( 'Choose Color', 'finalpdf' ),
                                'type'        => 'color',
                                'description' => __( 'Please choose text color to display in the header', 'finalpdf' ),
                                'id'          => 'finalpdf_header_color',
                                'value'       => $finalpdf_header_color,
                                'class'       => 'finalpdf_color_picker finalpdf_header_color',
                                'name'        => 'finalpdf_header_color',
                                'placeholder' => __( 'color', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Header Width', 'finalpdf' ),
                                'type'        => 'number',
                                'description' => __( 'Please enter width to display in the header accepted values are in px, please enter number only', 'finalpdf' ),
                                'id'          => 'finalpdf_header_width',
                                'value'       => $finalpdf_header_width,
                                'class'       => 'finalpdf_header_width',
                                'name'        => 'finalpdf_header_width',
                                'placeholder' => __( 'width', 'finalpdf' ),
                                'min'         => 5,
                                'max'         => 30,
                        ),
                        array(
                                'title'       => __( 'Choose Font Style', 'finalpdf' ),
                                'type'        => 'select',
                                'description' => __( 'Please choose font style to display in the header', 'finalpdf' ),
                                'id'          => 'finalpdf_header_font_style',
                                'value'       => $finalpdf_header_font_style,
                                'class'       => 'finalpdf_header_font_style',
                                'name'        => 'finalpdf_header_font_style',
                                'placeholder' => __( 'font style', 'finalpdf' ),
                                'options'     => $wps_finalpdf_font_styles,
                        ),
                        array(
                                'title'       => __( 'Choose Font Size', 'finalpdf' ),
                                'type'        => 'number',
                                'description' => __( 'Please choose font size to display in the header', 'finalpdf' ),
                                'id'          => 'finalpdf_header_font_size',
                                'value'       => $finalpdf_header_font_size,
                                'class'       => 'finalpdf_header_font_size',
                                'name'        => 'finalpdf_header_font_size',
                                'placeholder' => __( 'font size', 'finalpdf' ),
                                'min'         => 5,
                                'max'         => 30,
                        ),
                        array(
                                'title'        => __( 'Header Top Placement', 'finalpdf' ),
                                'type'         => 'number',
                                'description'  => __( 'The greater the value in the Header Top, more will be the header length down from the top. Accepted values are positive and negative. If there exists an issue with the header placement, the header top value should be changed.', 'finalpdf' ),
                                'id'           => 'finalpdf_header_top',
                                'value'        => $finalpdf_header_top,
                                'class'        => 'finalpdf_header_top',
                                'name'         => 'finalpdf_header_top',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                                'placeholder'  => __( 'top', 'finalpdf' ),
                                'min'          => -500,
                                'max'          => 500,
                        ),
                );
                $finalpdf_settings_header_fields_html_arr   = apply_filters( 'finalpdf_settings_header_fields_html_arr_filter_hook', $finalpdf_settings_header_fields_html_arr );
                $finalpdf_settings_header_fields_html_arr[] = array(
                        'type'        => 'button',
                        'id'          => 'finalpdf_header_setting_submit',
                        'button_text' => __( 'Save Settings', 'finalpdf' ),
                        'class'       => 'finalpdf_header_setting_submit',
                        'name'        => 'finalpdf_header_setting_submit',
                );
                return $finalpdf_settings_header_fields_html_arr;
        }

        /**
         * Html fields for footer custmization.
         *
         * @since 1.0.0
         *
         * @param array $finalpdf_settings_footer_fields_html_arr array of fields containing html.
         * @return array
         */
        public function finalpdf_admin_footer_settings_page( $finalpdf_settings_footer_fields_html_arr ) {
                $finalpdf_footer_settings   = get_option( 'finalpdf_footer_setting_submit', array() );
                $finalpdf_footer_use_in_pdf = array_key_exists( 'finalpdf_footer_use_in_pdf', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_use_in_pdf'] : '';
                $finalpdf_footer_tagline    = array_key_exists( 'finalpdf_footer_tagline', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_tagline'] : '';
                $finalpdf_footer_color      = array_key_exists( 'finalpdf_footer_color', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_color'] : '';
                $finalpdf_footer_width      = array_key_exists( 'finalpdf_footer_width', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_width'] : '';
                $finalpdf_footer_font_style = array_key_exists( 'finalpdf_footer_font_style', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_font_style'] : '';
                $finalpdf_footer_font_size  = array_key_exists( 'finalpdf_footer_font_size', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_font_size'] : '';
                $finalpdf_footer_bottom     = array_key_exists( 'finalpdf_footer_bottom', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_bottom'] : '';
                $finalpdf_footer_customization     = array_key_exists( 'finalpdf_footer_customization_for_post_detail', $finalpdf_footer_settings ) ? $finalpdf_footer_settings['finalpdf_footer_customization_for_post_detail'] : array();
                $wps_finalpdf_font_styles   = array(
                        ''            => __( 'Select option', 'finalpdf' ),
                        'helvetica'   => __( 'Helvetica', 'finalpdf' ),
                        'courier'     => __( 'Courier', 'finalpdf' ),
                        'sans-serif'  => __( 'Sans Serif', 'finalpdf' ),
                        'DejaVu Sans' => __( 'DejaVu Sans', 'finalpdf' ),
                        'times-roman' => __( 'Times-Roman', 'finalpdf' ),
                        'symbol'      => __( 'Symbol', 'finalpdf' ),
                        'zapfdinbats' => __( 'Zapfdinbats', 'finalpdf' ),
                );
                $wps_finalpdf_font_styles   = apply_filters( 'wps_finalpdf_font_styles_filter_hook', $wps_finalpdf_font_styles );

                $finalpdf_settings_footer_fields_html_arr   = array(
                        array(
                                'title'       => __( 'Include Footer', 'finalpdf' ),
                                'type'        => 'checkbox',
                                'description' => __( 'Select this include footer on the page.', 'finalpdf' ),
                                'id'          => 'finalpdf_footer_use_in_pdf',
                                'value'       => $finalpdf_footer_use_in_pdf,
                                'class'       => 'finalpdf_footer_use_in_pdf',
                                'name'        => 'finalpdf_footer_use_in_pdf',
                        ),
                        array(
                                'title'       => __( 'Tagline', 'finalpdf' ),
                                'type'        => 'textarea',
                                'class'       => 'finalpdf_footer_tagline',
                                'id'          => 'finalpdf_footer_tagline',
                                'name'        => 'finalpdf_footer_tagline',
                                'description' => __( 'Enter the tagline to show in footer', 'finalpdf' ),
                                'placeholder' => __( 'tagline', 'finalpdf' ),
                                'value'       => $finalpdf_footer_tagline,
                        ),
                        array(
                                'title'       => __( 'Choose Color', 'finalpdf' ),
                                'type'        => 'color',
                                'description' => __( 'Please choose color to display in the footer text.', 'finalpdf' ),
                                'id'          => 'finalpdf_footer_color',
                                'value'       => $finalpdf_footer_color,
                                'class'       => 'finalpdf_color_picker finalpdf_footer_color',
                                'name'        => 'finalpdf_footer_color',
                                'placeholder' => __( 'color', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Choose Width', 'finalpdf' ),
                                'type'        => 'number',
                                'description' => __( 'Please choose width to display in the footer accepted values are in px, please enter number only.', 'finalpdf' ),
                                'id'          => 'finalpdf_footer_width',
                                'value'       => $finalpdf_footer_width,
                                'class'       => 'finalpdf_footer_width',
                                'name'        => 'finalpdf_footer_width',
                                'placeholder' => __( 'width', 'finalpdf' ),
                                'min'         => 0,
                                'max'         => 300,
                        ),
                        array(
                                'title'       => __( 'Choose Font Style', 'finalpdf' ),
                                'type'        => 'select',
                                'description' => __( 'Please choose font style to display in the footer.', 'finalpdf' ),
                                'id'          => 'finalpdf_footer_font_style',
                                'value'       => $finalpdf_footer_font_style,
                                'class'       => 'finalpdf_footer_font_style',
                                'name'        => 'finalpdf_footer_font_style',
                                'placeholder' => __( 'font style', 'finalpdf' ),
                                'options'     => $wps_finalpdf_font_styles,
                        ),
                        array(
                                'title'       => __( 'Choose Font Size', 'finalpdf' ),
                                'type'        => 'number',
                                'description' => __( 'Please choose font size to display in the footer.', 'finalpdf' ),
                                'id'          => 'finalpdf_footer_font_size',
                                'value'       => $finalpdf_footer_font_size,
                                'class'       => 'finalpdf_footer_font_size',
                                'name'        => 'finalpdf_footer_font_size',
                                'placeholder' => __( 'font size', 'finalpdf' ),
                        ),
                        array(
                                'title'        => __( 'Footer Bottom Placement', 'finalpdf' ),
                                'type'         => 'number',
                                'description'  => __( 'The greater the value in the Footer Bottom, more will be the footer length up from the bottom. Accepted values are positive and negative. If there exists an issue with the footer placement, the footer bottom value should be changed.', 'finalpdf' ),
                                'id'           => 'finalpdf_footer_bottom',
                                'value'        => $finalpdf_footer_bottom,
                                'class'        => 'finalpdf_footer_bottom',
                                'name'         => 'finalpdf_footer_bottom',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                                'placeholder'  => __( 'bottom', 'finalpdf' ),
                                'min'          => -500,
                                'max'          => 500,
                        ),
                        array(
                                'title'       => __( 'Add Author, Post name and Date in Footer.', 'finalpdf' ),
                                'type'        => 'multiselect',
                                'description' => __( 'You have the option to customize the footer to include the authors name, the post title, and the publication date.', 'finalpdf' ),
                                'id'          => 'finalpdf_footer_customization_for_post_detail',
                                'value'       => $finalpdf_footer_customization,
                                'class'       => 'pgfw-multiselect-class wps-defaut-multiselect finalpdf_advanced_show_post_type_icons',
                                'name'        => 'finalpdf_footer_customization_for_post_detail',
                                'options'     => array(
                                        'author' => __( 'author name', 'finalpdf' ),
                                        'post_title'      => __( 'post title', 'finalpdf' ),
                                        'post_date'      => __( 'publish date', 'finalpdf' ),
                                ),
                        ),
                );

                $finalpdf_settings_footer_fields_html_arr[] = array(
                        'title'        => __( 'Change Page No Format', 'finalpdf' ),
                        'type'         => 'checkbox',
                        'description'  => __( 'Check this if you want to show page no with total page count example ( 1 / 20 ).', 'finalpdf' ),
                        'id'           => 'finalpdf_general_pdf_show_pageno',
                        'value'        => '',
                        'class'        => 'wps_finalpdf_pro_tag',
                        'name'         => 'finalpdf_general_pdf_show_pageno',
                        'parent-class' => 'wps_finalpdf_setting_separate_border',
                );
                $finalpdf_settings_footer_fields_html_arr[] = array(
                        'title'       => __( 'Page number position', 'finalpdf' ),
                        'type'        => 'multi',
                        'class'       => 'wps_finalpdf_pro_tag',
                        'id'          => 'finalpdf_wartermark_position',
                        'description' => __( 'Choose page number position left, top in px.', 'finalpdf' ),
                        'value'       => array(
                                array(
                                        'type'        => 'number',
                                        'id'          => 'finalpdf_pageno_position_left',
                                        'class'       => 'wps_finalpdf_pro_tag',
                                        'name'        => 'finalpdf_pageno_position_left',
                                        'placeholder' => __( 'left', 'finalpdf' ),
                                        'value'       => 100,
                                        'min'         => -5000,
                                        'max'         => 5000,
                                ),
                                array(
                                        'type'        => 'number',
                                        'id'          => 'finalpdf_pageno_position_top',
                                        'class'       => 'wps_finalpdf_pro_tag',
                                        'name'        => 'finalpdf_pageno_position_top',
                                        'placeholder' => __( 'top', 'finalpdf' ),
                                        'value'       => 100,
                                        'min'         => -5000,
                                        'max'         => 5000,
                                ),
                        ),
                );

                $finalpdf_settings_footer_fields_html_arr   = apply_filters( 'finalpdf_settings_footer_fields_html_arr_filter_hook', $finalpdf_settings_footer_fields_html_arr );
                $finalpdf_settings_footer_fields_html_arr[] = array(
                        'type'        => 'button',
                        'id'          => 'finalpdf_footer_setting_submit',
                        'button_text' => __( 'Save Settings', 'finalpdf' ),
                        'class'       => 'finalpdf_footer_setting_submit',
                        'name'        => 'finalpdf_footer_setting_submit',
                );

                return $finalpdf_settings_footer_fields_html_arr;
        }
        /**
         * Html fields for body customizations.
         *
         * @since 1.0.0
         * @param array $finalpdf_body_html_arr array containing fields for body customizations.
         * @return array
         */
        public function finalpdf_admin_body_settings_page( $finalpdf_body_html_arr ) {
                $finalpdf_body_settings          = get_option( 'finalpdf_body_save_settings', array() );
                $finalpdf_body_title_font_style  = array_key_exists( 'finalpdf_body_title_font_style', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_title_font_style'] : '';
                $finalpdf_body_title_font_size   = array_key_exists( 'finalpdf_body_title_font_size', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_title_font_size'] : '';
                $finalpdf_body_title_font_color  = array_key_exists( 'finalpdf_body_title_font_color', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_title_font_color'] : '';
                $finalpdf_body_page_size         = array_key_exists( 'finalpdf_body_page_size', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_page_size'] : '';
                $finalpdf_body_page_orientation  = array_key_exists( 'finalpdf_body_page_orientation', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_page_orientation'] : '';
                $finalpdf_body_page_font_style   = array_key_exists( 'finalpdf_body_page_font_style', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_page_font_style'] : '';
                $finalpdf_body_page_font_size    = array_key_exists( 'finalpdf_content_font_size', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_content_font_size'] : '';
                $finalpdf_body_page_font_color   = array_key_exists( 'finalpdf_body_font_color', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_font_color'] : '';
                $finalpdf_body_border_size       = array_key_exists( 'finalpdf_body_border_size', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_border_size'] : '';
                $finalpdf_body_border_color      = array_key_exists( 'finalpdf_body_border_color', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_border_color'] : '';
                $finalpdf_body_margin_top        = array_key_exists( 'finalpdf_body_margin_top', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_margin_top'] : '';
                $finalpdf_body_margin_left       = array_key_exists( 'finalpdf_body_margin_left', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_margin_left'] : '';
                $finalpdf_body_margin_right      = array_key_exists( 'finalpdf_body_margin_right', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_margin_right'] : '';
                $finalpdf_body_margin_bottom     = array_key_exists( 'finalpdf_body_margin_bottom', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_margin_bottom'] : '';
                $finalpdf_body_rtl_support       = array_key_exists( 'finalpdf_body_rtl_support', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_rtl_support'] : '';
                $finalpdf_body_add_watermark     = array_key_exists( 'finalpdf_body_add_watermark', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_add_watermark'] : '';
                $finalpdf_body_metafields_row_wise     = array_key_exists( 'finalpdf_body_metafields_row_wise', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_metafields_row_wise'] : '';
                $finalpdf_body_images_row_wise     = array_key_exists( 'finalpdf_body_images_row_wise', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_images_row_wise'] : '';
                $finalpdf_body_watermark_text    = array_key_exists( 'finalpdf_body_watermark_text', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_watermark_text'] : '';
                $finalpdf_body_watermark_color   = array_key_exists( 'finalpdf_body_watermark_color', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_watermark_color'] : '';
                $finalpdf_body_page_template     = array_key_exists( 'finalpdf_body_page_template', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_page_template'] : '';
                $finalpdf_body_post_template     = array_key_exists( 'finalpdf_body_post_template', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_post_template'] : '';
                $finalpdf_body_meta_field_column     = array_key_exists( 'finalpdf_body_meta_field_column', $finalpdf_body_settings ) ? intval( $finalpdf_body_settings['finalpdf_body_meta_field_column'] ) : '';
                $finalpdf_border_position_top    = array_key_exists( 'finalpdf_border_position_top', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_border_position_top'] : '';
                $finalpdf_border_position_bottom = array_key_exists( 'finalpdf_border_position_bottom', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_border_position_bottom'] : '';
                $finalpdf_border_position_left   = array_key_exists( 'finalpdf_border_position_left', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_border_position_left'] : '';
                $finalpdf_border_position_right  = array_key_exists( 'finalpdf_border_position_right', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_border_position_right'] : '';
                $finalpdf_body_custom_css        = array_key_exists( 'finalpdf_body_custom_css', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_custom_css'] : '';
                $finalpdf_body_custom_page_size_height        = array_key_exists( 'finalpdf_body_custom_page_size_height', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_custom_page_size_height'] : 150;
                $finalpdf_body_custom_page_size_width        = array_key_exists( 'finalpdf_body_custom_page_size_width', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_custom_page_size_width'] : 150;
                $finalpdf_body_customization                 = array_key_exists( 'finalpdf_body_customization_for_post_detail', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_customization_for_post_detail'] : array();
                $finalpdf_body_spcl_char_support       = array_key_exists( 'finalpdf_body_spcl_char_support', $finalpdf_body_settings ) ? $finalpdf_body_settings['finalpdf_body_spcl_char_support'] : '';

                $wps_finalpdf_font_styles = array(
                        ''            => __( 'Select option', 'finalpdf' ),
                        'helvetica'   => __( 'Helvetica', 'finalpdf' ),
                        'courier'     => __( 'Courier', 'finalpdf' ),
                        'sans-serif'  => __( 'Sans Serif', 'finalpdf' ),
                        'DejaVu Sans' => __( 'DejaVu Sans', 'finalpdf' ),
                        'times-roman' => __( 'Times-Roman', 'finalpdf' ),
                        'symbol'      => __( 'Symbol', 'finalpdf' ),
                        'zapfdinbats' => __( 'Zapfdinbats', 'finalpdf' ),
                );

                $wps_finalpdf_font_styles = apply_filters( 'wps_finalpdf_font_styles_filter_hook', $wps_finalpdf_font_styles );
                $wps_finalpdf_custom_page_size = array(
                        ''                         => __( 'Select option', 'finalpdf' ),
                        '4a0'                      => __( '4A0', 'finalpdf' ),
                        '2a0'                      => __( '2A0', 'finalpdf' ),
                        'a0'                       => __( 'A0', 'finalpdf' ),
                        'a1'                       => __( 'A1', 'finalpdf' ),
                        'a2'                       => __( 'A2', 'finalpdf' ),
                        'a3'                       => __( 'A3', 'finalpdf' ),
                        'a4'                       => __( 'A4', 'finalpdf' ),
                        'a5'                       => __( 'A5', 'finalpdf' ),
                        'a6'                       => __( 'A6', 'finalpdf' ),
                        'b0'                       => __( 'B0', 'finalpdf' ),
                        'b1'                       => __( 'B1', 'finalpdf' ),
                        'b2'                       => __( 'B2', 'finalpdf' ),
                        'b3'                       => __( 'B3', 'finalpdf' ),
                        'b4'                       => __( 'B4', 'finalpdf' ),
                        'b5'                       => __( 'B5', 'finalpdf' ),
                        'b6'                       => __( 'B6', 'finalpdf' ),
                        'c0'                       => __( 'C0', 'finalpdf' ),
                        'c1'                       => __( 'C1', 'finalpdf' ),
                        'c2'                       => __( 'C2', 'finalpdf' ),
                        'c3'                       => __( 'C3', 'finalpdf' ),
                        'c4'                       => __( 'C4', 'finalpdf' ),
                        'c5'                       => __( 'C5', 'finalpdf' ),
                        'c6'                       => __( 'C6', 'finalpdf' ),
                        'ra0'                      => __( 'RA0', 'finalpdf' ),
                        'ra1'                      => __( 'RA1', 'finalpdf' ),
                        'ra2'                      => __( 'RA2', 'finalpdf' ),
                        'ra3'                      => __( 'RA3', 'finalpdf' ),
                        'ra4'                      => __( 'RA4', 'finalpdf' ),
                        'sra0'                     => __( 'SRA0', 'finalpdf' ),
                        'sra1'                     => __( 'SRA1', 'finalpdf' ),
                        'sra2'                     => __( 'SRA2', 'finalpdf' ),
                        'sra3'                     => __( 'SRA3', 'finalpdf' ),
                        'sra4'                     => __( 'SRA4', 'finalpdf' ),
                        'letter'                   => __( 'Letter', 'finalpdf' ),
                        'legal'                    => __( 'Legal', 'finalpdf' ),
                        'executive'                => __( 'Executive', 'finalpdf' ),
                        'ledger'                   => __( 'Ledger', 'finalpdf' ),
                        'tabloid'                  => __( 'Tabloid', 'finalpdf' ),
                        'folio'                    => __( 'Folio', 'finalpdf' ),
                        'commercial #10 envelope'  => __( 'Commercial Envelope', 'finalpdf' ),
                        'catalog #10 1/2 envelope' => __( 'Catalog Envelope', 'finalpdf' ),
                        '8.5x11'                   => '8.5x11',
                        '8.5x14'                   => '8.5x14',
                        '11x17'                    => '11x17',
                );
                $wps_finalpdf_custom_page_size = apply_filters( 'wps_finalpdf_custom_page_size_filter_hook', $wps_finalpdf_custom_page_size );

                $finalpdf_body_html_arr   = array(
                        array(
                                'title'       => __( 'Title Font Style', 'finalpdf' ),
                                'type'        => 'select',
                                'description' => __( 'Please choose title font style.', 'finalpdf' ),
                                'id'          => 'finalpdf_body_title_font_style',
                                'value'       => $finalpdf_body_title_font_style,
                                'class'       => 'finalpdf_body_title_font_style',
                                'name'        => 'finalpdf_body_title_font_style',
                                'placeholder' => __( 'title font_style', 'finalpdf' ),
                                'options'     => $wps_finalpdf_font_styles,
                        ),
                        array(
                                'title'       => __( 'Title Font Size.', 'finalpdf' ),
                                'type'        => 'number',
                                'description' => __( 'This will be the font size of the title.', 'finalpdf' ),
                                'id'          => 'finalpdf_body_title_font_size',
                                'value'       => $finalpdf_body_title_font_size,
                                'class'       => 'finalpdf_body_title_font_size',
                                'name'        => 'finalpdf_body_title_font_size',
                                'placeholder' => __( 'title font size', 'finalpdf' ),
                                'min'         => 5,
                                'max'         => 50,
                        ),
                        array(
                                'title'       => __( 'Choose Title Color', 'finalpdf' ),
                                'type'        => 'color',
                                'description' => __( 'Please choose color to display the title text.', 'finalpdf' ),
                                'id'          => 'finalpdf_body_title_font_color',
                                'value'       => $finalpdf_body_title_font_color,
                                'class'       => 'finalpdf_color_picker finalpdf_body_title_font_color',
                                'name'        => 'finalpdf_body_title_font_color',
                                'placeholder' => __( 'color', 'finalpdf' ),
                        ),
                        array(
                                'title'        => __( 'Page Size', 'finalpdf' ),
                                'type'         => 'select',
                                'description'  => __( 'Please choose page size to generate PDF.', 'finalpdf' ),
                                'id'           => 'finalpdf_body_page_size',
                                'value'        => $finalpdf_body_page_size,
                                'class'        => 'finalpdf_body_page_size',
                                'name'         => 'finalpdf_body_page_size',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                                'placeholder'  => __( 'page size', 'finalpdf' ),
                                'options'      => $wps_finalpdf_custom_page_size,
                        ),
                        array(
                                'title'       => __( 'Height of the page ( in mm )', 'finalpdf' ),
                                'type'        => 'number',
                                'id'          => 'finalpdf_body_custom_page_size_height',
                                'class'       => 'finalpdf_body_custom_page_size_height',
                                'name'        => 'finalpdf_body_custom_page_size_height',
                                'value'       => $finalpdf_body_custom_page_size_height,
                                'min'         => 150,
                                'max'         => 1500,
                                'style'       => ( 'custom_page' !== $finalpdf_body_page_size ) ? 'display:none;' : '',
                                'placeholder' => 'Height ( in mm )',
                        ),
                        array(
                                'title'       => __( 'Width of the page ( in mm )', 'finalpdf' ),
                                'type'        => 'number',
                                'id'          => 'finalpdf_body_custom_page_size_width',
                                'class'       => 'finalpdf_body_custom_page_size_width',
                                'name'        => 'finalpdf_body_custom_page_size_width',
                                'value'       => $finalpdf_body_custom_page_size_width,
                                'min'         => 150,
                                'max'         => 1500,
                                'style'       => ( 'custom_page' !== $finalpdf_body_page_size ) ? 'display:none;' : '',
                                'placeholder' => 'Width ( in mm )',
                        ),
                        array(
                                'title'       => __( 'Page Orientation', 'finalpdf' ),
                                'type'        => 'select',
                                'description' => __( 'Choose page orientation to generate PDF.', 'finalpdf' ),
                                'id'          => 'finalpdf_body_page_orientation',
                                'value'       => $finalpdf_body_page_orientation,
                                'class'       => 'finalpdf_body_page_orientation',
                                'name'        => 'finalpdf_body_page_orientation',
                                'placeholder' => __( 'page orientation', 'finalpdf' ),
                                'options'     => array(
                                        ''          => __( 'Select option', 'finalpdf' ),
                                        'landscape' => __( 'Landscape', 'finalpdf' ),
                                        'portrait'  => __( 'Portrait', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'        => __( 'Content Font Style', 'finalpdf' ),
                                'type'         => 'select',
                                'description'  => __( 'Choose page font to generate PDF.', 'finalpdf' ),
                                'id'           => 'finalpdf_body_page_font_style',
                                'value'        => $finalpdf_body_page_font_style,
                                'class'        => 'finalpdf_body_page_font_style',
                                'name'         => 'finalpdf_body_page_font_style',
                                'placeholder'  => __( 'page font', 'finalpdf' ),
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                                'options'      => $wps_finalpdf_font_styles,
                        ),
                        array(
                                'title'       => __( 'Content Font Size', 'finalpdf' ),
                                'type'        => 'number',
                                'description' => __( 'Choose content font size to generate PDF.', 'finalpdf' ),
                                'id'          => 'finalpdf_content_font_size',
                                'value'       => $finalpdf_body_page_font_size,
                                'class'       => 'finalpdf_content_font_size',
                                'placeholder' => '',
                        ),
                        array(
                                'title'        => __( 'Choose Body Text Color', 'finalpdf' ),
                                'type'         => 'color',
                                'description'  => __( 'Choose color to display body text.', 'finalpdf' ),
                                'id'           => 'finalpdf_body_font_color',
                                'value'        => $finalpdf_body_page_font_color,
                                'class'        => 'finalpdf_color_picker finalpdf_body_font_color',
                                'name'         => 'finalpdf_body_font_color',
                                'placeholder'  => __( 'color', 'finalpdf' ),
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                        ),
                        array(
                                'title'       => __( 'Border', 'finalpdf' ),
                                'type'        => 'multi',
                                'id'          => 'finalpdf_body_border',
                                'description' => __( 'Choose border: size in px and color.', 'finalpdf' ),
                                'value'       => array(
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_body_border_size',
                                                'class'       => 'finalpdf_body_border_size',
                                                'name'        => 'finalpdf_body_border_size',
                                                'placeholder' => __( 'border size', 'finalpdf' ),
                                                'value'       => $finalpdf_body_border_size,
                                                'min'         => 0,
                                                'max'         => 50,
                                        ),
                                        array(
                                                'type'        => 'color',
                                                'id'          => 'finalpdf_body_border_color',
                                                'class'       => 'finalpdf_color_picker finalpdf_body_border_color',
                                                'name'        => 'finalpdf_body_border_color',
                                                'placeholder' => __( 'border color', 'finalpdf' ),
                                                'value'       => $finalpdf_body_border_color,
                                        ),
                                ),
                        ),
                        array(
                                'title'       => __( 'PDF Border Position', 'finalpdf' ),
                                'id'          => 'finalpdf_border_position',
                                'type'        => 'multi',
                                'description' => __( 'Enter Border margin : top, left, right, bottom, accepted values are positive and negative, this will decide the position of border on the page.', 'finalpdf' ),
                                'value'       => array(
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_border_position_top',
                                                'class'       => 'finalpdf_border_position_top',
                                                'name'        => 'finalpdf_border_position_top',
                                                'placeholder' => __( 'Top', 'finalpdf' ),
                                                'value'       => $finalpdf_border_position_top,
                                                'min'         => -500,
                                                'max'         => 500,
                                        ),
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_border_position_left',
                                                'class'       => 'finalpdf_border_position_left',
                                                'name'        => 'finalpdf_border_position_left',
                                                'placeholder' => __( 'Left', 'finalpdf' ),
                                                'value'       => $finalpdf_border_position_left,
                                                'min'         => -500,
                                                'max'         => 500,
                                        ),
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_border_position_right',
                                                'class'       => 'finalpdf_border_position_right',
                                                'name'        => 'finalpdf_border_position_right',
                                                'placeholder' => __( 'Right', 'finalpdf' ),
                                                'value'       => $finalpdf_border_position_right,
                                                'min'         => -500,
                                                'max'         => 500,
                                        ),
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_border_position_bottom',
                                                'class'       => 'finalpdf_border_position_bottom',
                                                'name'        => 'finalpdf_border_position_bottom',
                                                'placeholder' => __( 'Bottom', 'finalpdf' ),
                                                'value'       => $finalpdf_border_position_bottom,
                                                'min'         => -500,
                                                'max'         => 500,
                                        ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Page Margin', 'finalpdf' ),
                                'id'          => 'finalpdf_body_margin',
                                'type'        => 'multi',
                                'description' => __( 'Enter page margin : top, left, right, bottom, set top and bottom values if any issue with content placement, while changing the header and footer width, margin top and margin bottom must be set from here to display correctly on the page.', 'finalpdf' ),
                                'value'       => array(
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_body_margin_top',
                                                'class'       => 'finalpdf_body_margin_top',
                                                'name'        => 'finalpdf_body_margin_top',
                                                'placeholder' => __( 'Top', 'finalpdf' ),
                                                'value'       => $finalpdf_body_margin_top,
                                                'min'         => -500,
                                                'max'         => 500,
                                        ),
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_body_margin_left',
                                                'class'       => 'finalpdf_body_margin_left',
                                                'name'        => 'finalpdf_body_margin_left',
                                                'placeholder' => __( 'Left', 'finalpdf' ),
                                                'value'       => $finalpdf_body_margin_left,
                                                'min'         => -500,
                                                'max'         => 500,
                                        ),
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_body_margin_right',
                                                'class'       => 'finalpdf_body_margin_right',
                                                'name'        => 'finalpdf_body_margin_right',
                                                'placeholder' => __( 'Right', 'finalpdf' ),
                                                'value'       => $finalpdf_body_margin_right,
                                                'min'         => -500,
                                                'max'         => 500,
                                        ),
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_body_margin_bottom',
                                                'class'       => 'finalpdf_body_margin_bottom',
                                                'name'        => 'finalpdf_body_margin_bottom',
                                                'placeholder' => __( 'Bottom', 'finalpdf' ),
                                                'value'       => $finalpdf_body_margin_bottom,
                                                'min'         => -500,
                                                'max'         => 500,
                                        ),
                                ),
                        ),
                        array(
                                'title'        => __( 'Special Character Support', 'finalpdf' ),
                                'type'         => 'checkbox',
                                'description'  => __( 'Select this to enable special character support ( enabling this will enable, font-style : DejaVu Sans, sans-serif globally ) and will support special character.', 'finalpdf' ),
                                'id'           => 'finalpdf_body_spcl_char_support',
                                'value'        => $finalpdf_body_spcl_char_support,
                                'class'        => 'finalpdf_body_spcl_char_support',
                                'name'         => 'finalpdf_body_spcl_char_support',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                        ),
                        array(
                                'title'        => __( 'RTL Support', 'finalpdf' ),
                                'type'         => 'checkbox',
                                'description'  => __( 'Select this to enable RTL support ( enabling this will enable, font-style : DejaVu Sans, sans-serif globally ) and will support right to left text alignment.', 'finalpdf' ),
                                'id'           => 'finalpdf_body_rtl_support',
                                'value'        => $finalpdf_body_rtl_support,
                                'class'        => 'finalpdf_body_rtl_support',
                                'name'         => 'finalpdf_body_rtl_support',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                        ),
                        array(
                                'title'       => __( 'Add Watermark Text', 'finalpdf' ),
                                'type'        => 'checkbox',
                                'description' => __( 'Select this to add watermark text on the created PDF.', 'finalpdf' ),
                                'id'          => 'finalpdf_body_add_watermark',
                                'value'       => $finalpdf_body_add_watermark,
                                'class'       => 'finalpdf_body_add_watermark',
                                'name'        => 'finalpdf_body_add_watermark',
                        ),

                        array(
                                'title'        => __( 'Add Watermark Image', 'finalpdf' ),
                                'type'         => 'checkbox',
                                'description'  => __( 'Select this to include watermark image in PDF.', 'finalpdf' ),
                                'id'           => 'finalpdf_watermark_image_use_in_pdf_dummy',
                                'value'        => 'no',
                                'class'        => 'wps_finalpdf_pro_tag',
                                'name'         => 'finalpdf_watermark_image_use_in_pdf',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                        ),
                        array(
                                'title'       => __( 'Watermark Angle', 'finalpdf' ),
                                'type'        => 'number',
                                'description' => __( 'Please Choose Watermark Angle.', 'finalpdf' ),
                                'id'          => 'finalpdf_watermark_angle_dummy',
                                'value'       => -45,
                                'class'       => 'wps_finalpdf_pro_tag',
                                'placeholder' => '',
                                'min'         => -90,
                                'max'         => 180,
                        ),
                        array(
                                'title'       => __( 'Watermark Position', 'finalpdf' ),
                                'type'        => 'multi',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'id'          => 'finalpdf_wartermark_position_dummy',
                                'description' => __( 'Choose watermark position left, top in px.', 'finalpdf' ),
                                'value'       => array(
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_watermark_position_left',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'name'        => 'finalpdf_watermark_position_left',
                                                'placeholder' => __( 'left', 'finalpdf' ),
                                                'value'       => 120,
                                                'min'         => -5000,
                                                'max'         => 5000,
                                        ),
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_watermark_position_top',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'name'        => 'finalpdf_watermark_position_top',
                                                'placeholder' => __( 'top', 'finalpdf' ),
                                                'value'       => 80,
                                                'min'         => -5000,
                                                'max'         => 5000,
                                        ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Watermark Opacity', 'finalpdf' ),
                                'type'        => 'number',
                                'id'          => 'finalpdf_watermark_opacity_dummy',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_watermark_opacity',
                                'placeholder' => __( 'opacity', 'finalpdf' ),
                                'value'       => 0.3,
                                'min'         => 0,
                                'max'         => 1,
                                'step'        => .1,
                                'description' => __( 'Choose this to add transparency to the image used as watermark, value should be greater than 0 and less than 1, accepted decimal values.', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Choose Watermark Image', 'finalpdf' ),
                                'type'        => 'upload-button',
                                'button_text' => __( 'Upload Image', 'finalpdf' ),
                                'sub_class'   => 'finalpdf_watermark_image_upload1',
                                'sub_id'      => 'finalpdf_watermark_image_upload1',
                                'id'          => 'sub_pgfw_watermark_image_upload_dummy',
                                'name'        => 'sub_pgfw_watermark_image_upload1',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'value'       => '',
                                'sub_name'    => 'finalpdf_watermark_image_upload',
                                'img-tag'     => array(
                                        'img-class' => 'finalpdf_watermark_image',
                                        'img-id'    => 'finalpdf_watermark_image',
                                        'img-style' => ( '' ) ? 'margin-right:10px;width:100px;height:100px;' : 'display:none;margin-right:10px;width:100px;height:100px;',
                                        'img-src'   => '',
                                ),
                                'img-remove'  => array(
                                        'btn-class' => 'finalpdf_watermark_image_remove',
                                        'btn-id'    => 'finalpdf_watermark_image_remove',
                                        'btn-text'  => __( 'Remove image', 'finalpdf' ),
                                        'btn-title' => __( 'Remove image', 'finalpdf' ),
                                        'btn-name'  => 'finalpdf_watermark_image_remove',
                                        'btn-style' => ! ( '' ) ? 'display:none' : '',
                                ),
                        ),
                        array(
                                'title'       => __( 'Watermark Image Size', 'finalpdf' ),
                                'type'        => 'multi',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'id'          => 'finalpdf_wartermark_size_dummy',
                                'description' => __( 'Choose watermark image width, height in px.', 'finalpdf' ),
                                'value'       => array(
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_watermark_image_width',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'name'        => 'finalpdf_watermark_image_width',
                                                'placeholder' => __( 'width', 'finalpdf' ),
                                                'value'       => 100,
                                                'min'         => -5000,
                                                'max'         => 5000,
                                        ),
                                        array(
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_watermark_image_height',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'name'        => 'finalpdf_watermark_image_height',
                                                'placeholder' => __( 'height', 'finalpdf' ),
                                                'value'       => 100,
                                                'min'         => -5000,
                                                'max'         => 5000,
                                        ),
                                ),
                        ),

                        array(
                                'title'       => __( 'Watermark Text', 'finalpdf' ),
                                'type'        => 'textarea',
                                'description' => __( 'Enter text to be used as watermark.', 'finalpdf' ),
                                'id'          => 'finalpdf_body_watermark_text',
                                'value'       => $finalpdf_body_watermark_text,
                                'class'       => 'finalpdf_body_watermark_text ',
                                'name'        => 'finalpdf_body_watermark_text',
                                'placeholder' => __( 'Watermark text', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Choose Watermark Text Color', 'finalpdf' ),
                                'type'        => 'color',
                                'description' => __( 'Please choose color to display the text of watermark.', 'finalpdf' ),
                                'id'          => 'finalpdf_body_watermark_color',
                                'value'       => $finalpdf_body_watermark_color,
                                'class'       => 'finalpdf_color_picker finalpdf_body_watermark_color',
                                'name'        => 'finalpdf_body_watermark_color',
                                'placeholder' => __( 'color', 'finalpdf' ),
                        ),
                        array(
                                'title'        => __( 'Page Template', 'finalpdf' ),
                                'type'         => 'select',
                                'description'  => __( 'This will be used as the page template.', 'finalpdf' ),
                                'id'           => 'finalpdf_body_page_template',
                                'value'        => $finalpdf_body_page_template,
                                'class'        => 'finalpdf_body_page_template',
                                'name'         => 'finalpdf_body_page_template',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                                'options'      => array(

                                        'template1' => __( 'Template1', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Post Template', 'finalpdf' ),
                                'type'        => 'select',
                                'description' => __( 'This will be used as the post template.', 'finalpdf' ),
                                'id'          => 'finalpdf_body_post_template',
                                'value'       => $finalpdf_body_post_template,
                                'class'       => 'finalpdf_body_post_template',
                                'name'        => 'finalpdf_body_post_template',
                                'options'     => array(

                                        'template1' => __( 'Template1', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Custom CSS', 'finalpdf' ),
                                'type'        => 'textarea',
                                'description' => __( 'Add custom CSS for any HTML element this will be applied to the elements in the content.', 'finalpdf' ),
                                'id'          => 'finalpdf_body_custom_css',
                                'value'       => $finalpdf_body_custom_css,
                                'class'       => 'finalpdf_body_custom_css',
                                'name'        => 'finalpdf_body_custom_css',
                                'placeholder' => __( 'Custom CSS', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Show images row wise   ( Template1 )', 'finalpdf' ),
                                'type'        => 'checkbox',
                                'description' => __( 'Select this to display images in row wise .', 'finalpdf' ),
                                'id'          => 'finalpdf_body_images_row_wise',
                                'value'       => $finalpdf_body_images_row_wise,
                                'class'       => 'finalpdf_body_images_row_wise',
                                'name'        => 'finalpdf_body_images_row_wise',
                        ),
                        array(
                                'title'       => __( 'Show Meta fields row wise', 'finalpdf' ),
                                'type'        => 'checkbox',
                                'description' => __( 'Select this to display meta fields in row .', 'finalpdf' ),
                                'id'          => 'finalpdf_body_metafields_row_wise',
                                'value'       => $finalpdf_body_metafields_row_wise,
                                'class'       => 'finalpdf_body_metafields_row_wise',
                                'name'        => 'finalpdf_body_metafields_row_wise',
                        ),
                        array(
                                'title'       => __( 'Select Number of columns ', 'finalpdf' ),
                                'type'        => 'select',
                                'description' => __( 'You can choose number of columns needed in a row for your meta fields.', 'finalpdf' ),
                                'id'          => 'finalpdf_body_meta_field_column',
                                'value'       => $finalpdf_body_meta_field_column,
                                'class'       => 'finalpdf_body_meta_field_column',
                                'name'        => 'finalpdf_body_meta_field_column',
                                'options'     => array(
                                        '1'          => __( '1', 'finalpdf' ),
                                        '2'          => __( '2', 'finalpdf' ),
                                        '3'          => __( '3', 'finalpdf' ),
                                        '4'          => __( '4', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Hide featured image, title and Description(word)', 'finalpdf' ),
                                'type'        => 'multiselect',
                                'description' => __( 'You have the flexibility to customize the default template by editing static text strings and thumbnails to better suit your needs.', 'finalpdf' ),
                                'id'          => 'finalpdf_body_customization_for_post_detail',
                                'value'       => $finalpdf_body_customization,
                                'class'       => 'pgfw-multiselect-class wps-defaut-multiselect finalpdf_advanced_show_post_type_icons',
                                'name'        => 'finalpdf_body_customization_for_post_detail',
                                'options'     => array(
                                        'title' => __( 'Post title', 'finalpdf' ),
                                        'post_thumb'      => __( 'Post thumbnail', 'finalpdf' ),
                                        'description'      => __( 'Post description', 'finalpdf' ),
                                ),
                        ),
                );

                $finalpdf_body_html_arr   = apply_filters( 'finalpdf_settings_body_fields_html_arr_filter_hook', $finalpdf_body_html_arr );
                $finalpdf_body_html_arr[] = array(
                        'type'        => 'button',
                        'id'          => 'finalpdf_body_save_settings',
                        'button_text' => __( 'Save Settings', 'finalpdf' ),
                        'class'       => 'finalpdf_body_save_settings',
                        'name'        => 'finalpdf_body_save_settings',
                );
                return $finalpdf_body_html_arr;
        }
        /**
         * Html fields for advanced setting fields.
         *
         * @since 1.0.0
         * @param array $finalpdf_advanced_settings_html_arr array containing fields for advanced settings.
         * @return array
         */
        public function finalpdf_admin_advanced_settings_page( $finalpdf_advanced_settings_html_arr ) {
                $finalpdf_advanced_settings  = get_option( 'finalpdf_advanced_save_settings', array() );
                $finalpdf_advanced_icon_show = array_key_exists( 'finalpdf_advanced_show_post_type_icons', $finalpdf_advanced_settings ) ? $finalpdf_advanced_settings['finalpdf_advanced_show_post_type_icons'] : '';

                $post_types              = get_post_types( array( 'public' => true ) );
                unset( $post_types['attachment'] );

                $finalpdf_advanced_settings_html_arr   = array();
                $finalpdf_advanced_settings_html_arr[] = array(
                        'title'       => __( 'Show Icons for Post Type', 'finalpdf' ),
                        'type'        => 'multiselect',
                        'description' => __( 'PDF generate icons will be visible to selected post type.', 'finalpdf' ),
                        'id'          => 'finalpdf_advanced_show_post_type_icons',
                        'value'       => $finalpdf_advanced_icon_show,
                        'class'       => 'pgfw-multiselect-class wps-defaut-multiselect finalpdf_advanced_show_post_type_icons',
                        'name'        => 'finalpdf_advanced_show_post_type_icons',
                        'options'     => $post_types,
                );
                $finalpdf_advanced_settings_html_arr[] = array(
                        'title'       => __( 'Select Post Type', 'finalpdf' ),
                        'type'        => 'multiselect',
                        'description' => __( 'Select all post types that you want save as a PDF on server with weekly update.', 'finalpdf' ),
                        'id'          => 'finalpdf_advanced_post_on_server',
                        'value'       => 'posts',
                        'class'       => 'pgfw-multiselect-class wps-defaut-multiselect  wps_finalpdf_pro_tag',
                        'name'        => 'finalpdf_advanced_post_on_server',
                        'options'     => '',
                );
                $finalpdf_advanced_settings_html_arr[] = array(
                        'title'       => __( 'Upload Custom Font File', 'finalpdf' ),
                        'type'        => 'file',
                        'id'          => 'font_upload',
                        'value'       => '',
                        'class'       => 'wps_finalpdf_pro_tag',
                        'name'        => 'font_upload',
                        'placeholder' => __( 'ttf file', 'finalpdf' ),
                        'description' => __( 'Choose .ttf file to add custom font, once uploaded all dropdowns of font will have this option to choose from.', 'finalpdf' ),
                );
                $finalpdf_advanced_settings_html_arr   = apply_filters( 'finalpdf_settings_advance_html_arr_filter_hook', $finalpdf_advanced_settings_html_arr );
                $finalpdf_advanced_settings_html_arr[] = array(
                        'title'       => __( 'Reset Settings', 'finalpdf' ),
                        'description' => __( 'This will reset all the settings to default.', 'finalpdf' ),
                        'type'        => 'reset-button',
                        'id'          => 'finalpdf_advanced_reset_settings',
                        'button_text' => __( 'Reset settings', 'finalpdf' ),
                        'class'       => 'finalpdf_advanced_reset_settings',
                        'name'        => 'finalpdf_advanced_reset_settings',
                        'loader-id'   => 'finalpdf_reset_setting_loader',
                );

                $finalpdf_advanced_settings_html_arr[] = array(
                        'type'        => 'button',
                        'id'          => 'finalpdf_advanced_save_settings',
                        'button_text' => __( 'Save Settings', 'finalpdf' ),
                        'class'       => 'finalpdf_advanced_save_settings',
                        'name'        => 'finalpdf_advanced_save_settings',
                );

                return $finalpdf_advanced_settings_html_arr;
        }
        /**
         * Html for meta fields settings.
         *
         * @since 1.0.0
         * @param array $finalpdf_meta_settings_html_arr array containing html fields for meta fields settings.
         * @return array
         */
        public function finalpdf_admin_meta_fields_settings_page( $finalpdf_meta_settings_html_arr ) {
                $finalpdf_meta_settings = get_option( 'finalpdf_meta_fields_save_settings', array() );

                $finalpdf_meta_settings_html_arr = array();
                $post_types                  = get_post_types( array( 'public' => true ) );
                unset( $post_types['attachment'] );
                $i = 0;
                foreach ( $post_types as $post_type ) {
                        $meta_keys = array();
                        $posts     = get_posts(
                                array(
                                        'post_type' => $post_type,
                                        'limit'     => -1,
                                )
                        );
                        foreach ( $posts as $_post ) {
                                $post_meta_keys = get_post_custom_keys( $_post->ID );
                                if ( $post_meta_keys ) {
                                        $meta_keys = array_merge( $meta_keys, $post_meta_keys );
                                }
                        }
                        $post_meta_fields = array_values( array_unique( $meta_keys ) );
                        $post_meta_field  = array();
                        foreach ( $post_meta_fields as $key => $val ) {
                                $post_meta_field[ $val ] = $val;
                        }
                        $finalpdf_show_type_meta_val = array_key_exists( 'finalpdf_meta_fields_' . $post_type . '_show', $finalpdf_meta_settings ) ? $finalpdf_meta_settings[ 'finalpdf_meta_fields_' . $post_type . '_show' ] : '';
                        $finalpdf_show_type_meta_arr = array_key_exists( 'finalpdf_meta_fields_' . $post_type . '_list', $finalpdf_meta_settings ) ? $finalpdf_meta_settings[ 'finalpdf_meta_fields_' . $post_type . '_list' ] : array();
                        $finalpdf_meta_fields_show_image_gallery = array_key_exists( 'finalpdf_meta_fields_show_image_gallery', $finalpdf_meta_settings ) ? $finalpdf_meta_settings['finalpdf_meta_fields_show_image_gallery'] : '';
                        $finalpdf_meta_fields_show_unknown_image_format = array_key_exists( 'finalpdf_meta_fields_show_unknown_image_format', $finalpdf_meta_settings ) ? $finalpdf_meta_settings['finalpdf_meta_fields_show_unknown_image_format'] : '';
                        $finalpdf_gallery_metafield_key = array_key_exists( 'finalpdf_gallery_metafield_key', $finalpdf_meta_settings ) ? $finalpdf_meta_settings['finalpdf_gallery_metafield_key'] : '';
                        $finalpdf_meta_settings_html_arr[] =
                                array(
                                        'title'        => __( 'Show Meta Fields For ', 'finalpdf' ) . $post_type,
                                        'type'         => 'checkbox',
                                        'description'  => __( 'selecting this will show the meta fields on PDF.', 'finalpdf' ),
                                        'id'           => 'finalpdf_meta_fields_' . $post_type . '_show',
                                        'value'        => $finalpdf_show_type_meta_val,
                                        'class'        => 'finalpdf_meta_fields_' . $post_type . '_show',
                                        'name'         => 'finalpdf_meta_fields_' . $post_type . '_show',
                                        'parent-class' => ( 0 === $i ) ? '' : 'wps_finalpdf_setting_separate_border',
                                );
                        $finalpdf_meta_settings_html_arr[] = array(
                                'title'       => __( 'Meta Fields in ', 'finalpdf' ) . $post_type,
                                'type'        => 'multiselect',
                                'description' => __( 'These meta fields will be shown on PDF.', 'finalpdf' ),
                                'id'          => 'finalpdf_meta_fields_' . $post_type . '_list',
                                'name'        => 'finalpdf_meta_fields_' . $post_type . '_list',
                                'value'       => $finalpdf_show_type_meta_arr,
                                'class'       => 'pgfw-multiselect-class wps-defaut-multiselect finalpdf_meta_fields_' . $post_type . '_list',
                                'placeholder' => '',
                                'options'     => $post_meta_field,
                        );
                        $finalpdf_meta_settings_html_arr   = apply_filters( 'finalpdf_settings_meta_fields_html_arr_filter_hook', $finalpdf_meta_settings_html_arr, $post_meta_field, $post_type );
                        $i++;
                }
                $finalpdf_meta_settings_html_arr[] =
                        array(
                                'title'        => __( 'Show Product Gallery Image  ', 'finalpdf' ),
                                'type'         => 'checkbox',
                                'description'  => __( 'If your gallery image meta field is different from "_product_image_gallery" then please write the name in below box and enable this setting and uncheck this in metafield section.', 'finalpdf' ),
                                'id'           => 'finalpdf_meta_fields_show_image_gallery',
                                'value'        => $finalpdf_meta_fields_show_image_gallery,
                                'class'        => 'finalpdf_meta_fields_show_image_gallery',
                                'name'         => 'finalpdf_meta_fields_show_image_gallery',

                        );
                $finalpdf_meta_settings_html_arr[] =
                        array(
                                'title'        => __( 'Unknown Image Format Handler ', 'finalpdf' ),
                                'type'         => 'checkbox',
                                'description'  => __( 'If your image type is unknown or not rendering in the PDF, please enable this setting.', 'finalpdf' ),
                                'id'           => 'finalpdf_meta_fields_show_unknown_image_format',
                                'value'        => $finalpdf_meta_fields_show_unknown_image_format,
                                'class'        => 'finalpdf_meta_fields_show_unknown_image_format',
                                'name'         => 'finalpdf_meta_fields_show_unknown_image_format',

                        );
                $finalpdf_meta_settings_html_arr[] = array(
                        'title'       => __( 'Gallery Image Meta Field Name.', 'finalpdf' ),
                        'type'        => 'text',
                        'description' => __( 'Enter your image gallery key .', 'finalpdf' ),
                        'id'          => 'finalpdf_gallery_metafield_key',
                        'value'       => $finalpdf_gallery_metafield_key,
                        'class'       => 'finalpdf_gallery_metafield_key wps_proper_align',
                        'name'        => 'finalpdf_gallery_metafield_key',
                        'placeholder' => __( 'Metafield key Name', 'finalpdf' ),
                );
                $finalpdf_meta_settings_html_arr[] = array(
                        'type'        => 'button',
                        'id'          => 'finalpdf_meta_fields_save_settings',
                        'button_text' => __( 'Save Settings', 'finalpdf' ),
                        'class'       => 'finalpdf_meta_fields_save_settings',
                        'name'        => 'finalpdf_meta_fields_save_settings',
                );
                return $finalpdf_meta_settings_html_arr;
        }
        /**
         * Html pdf for upload settings.
         *
         * @since 1.0.0
         * @param array $finalpdf_pdf_upload_settings_html_arr array containing fields for upload settings page.
         * @return array
         */
        public function finalpdf_admin_pdf_upload_settings_page( $finalpdf_pdf_upload_settings_html_arr ) {
                $finalpdf_pdf_upload_settings = get_option( 'finalpdf_pdf_upload_save_settings', array() );
                $finalpdf_poster_doc          = array_key_exists( 'sub_pgfw_poster_image_upload', $finalpdf_pdf_upload_settings ) ? json_decode( $finalpdf_pdf_upload_settings['sub_pgfw_poster_image_upload'], true ) : '';
                $finalpdf_poster_user_access  = array_key_exists( 'finalpdf_poster_user_access', $finalpdf_pdf_upload_settings ) ? $finalpdf_pdf_upload_settings['finalpdf_poster_user_access'] : '';
                $finalpdf_poster_guest_access = array_key_exists( 'finalpdf_poster_guest_access', $finalpdf_pdf_upload_settings ) ? $finalpdf_pdf_upload_settings['finalpdf_poster_guest_access'] : '';
                $finalpdf_poster_doc          = ( is_array( $finalpdf_poster_doc ) && count( $finalpdf_poster_doc ) <= 0 ) ? false : $finalpdf_poster_doc;

                $finalpdf_pdf_upload_settings_html_arr = array(
                        array(
                                'title'       => __( 'Access to Users', 'finalpdf' ),
                                'type'        => 'radio-switch',
                                'description' => __( 'Enable this to give access to Logged in users to download Posters.', 'finalpdf' ),
                                'id'          => 'finalpdf_poster_user_access',
                                'value'       => $finalpdf_poster_user_access,
                                'class'       => 'finalpdf_poster_user_access',
                                'name'        => 'finalpdf_poster_user_access',
                                'options'     => array(
                                        'yes' => __( 'YES', 'finalpdf' ),
                                        'no'  => __( 'NO', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Access to Guests', 'finalpdf' ),
                                'type'        => 'radio-switch',
                                'description' => __( 'Enable this to give access to Guests to Download Posters.', 'finalpdf' ),
                                'id'          => 'finalpdf_poster_guest_access',
                                'value'       => $finalpdf_poster_guest_access,
                                'class'       => 'finalpdf_poster_guest_access',
                                'name'        => 'finalpdf_poster_guest_access',
                                'options'     => array(
                                        'yes' => __( 'YES', 'finalpdf' ),
                                        'no'  => __( 'NO', 'finalpdf' ),
                                ),
                        ),
                        array(
                                'title'        => __( 'Choose Poster(s)', 'finalpdf' ),
                                'type'         => 'upload-button',
                                'button_text'  => __( 'Upload Doc', 'finalpdf' ),
                                'class'        => 'sub_pgfw_poster_image_upload',
                                'id'           => 'sub_pgfw_poster_image_upload',
                                'value'        => is_array( $finalpdf_poster_doc ) ? wp_json_encode( $finalpdf_poster_doc ) : $finalpdf_poster_doc,
                                'sub_id'       => 'finalpdf_poster_image_upload',
                                'sub_class'    => 'finalpdf_poster_image_upload',
                                'sub_name'     => 'finalpdf_poster_image_upload',
                                'name'         => 'sub_pgfw_poster_image_upload',
                                'parent-class' => 'wps_finalpdf_setting_separate_border',
                                'img-tag'      => array(
                                        'img-class' => 'finalpdf_poster_image',
                                        'img-id'    => 'finalpdf_poster_image',
                                        'img-style' => ( $finalpdf_poster_doc ) ? 'margin:10px;height:35px;width:35px;' : 'display:none;margin:10px;height:35px;width:35px;',
                                        'img-src'   => FINALPDF_DIR_URL . 'admin/src/images/document-management-big.png',
                                ),
                                'img-remove'   => array(
                                        'btn-class' => 'finalpdf_poster_image_remove',
                                        'btn-id'    => 'finalpdf_poster_image_remove',
                                        'btn-text'  => __( 'Remove Doc', 'finalpdf' ),
                                        'btn-title' => __( 'Remove Doc', 'finalpdf' ),
                                        'btn-name'  => 'finalpdf_poster_image_remove',
                                        'btn-style' => ! ( $finalpdf_poster_doc ) ? 'display:none' : '',
                                ),
                        ),
                        array(
                                'type'        => 'button',
                                'id'          => 'finalpdf_pdf_upload_save_settings',
                                'button_text' => __( 'Save Settings', 'finalpdf' ),
                                'class'       => 'finalpdf_pdf_upload_save_settings',
                                'name'        => 'finalpdf_pdf_upload_save_settings',
                        ),
                );
                return $finalpdf_pdf_upload_settings_html_arr;
        }
        /**
         * Ajax request handling for deleting media from uploaded posters.
         *
         * @since 1.0.0
         * @return void
         */
        public function wps_finalpdf_delete_poster_by_media_id_from_table() {
                check_ajax_referer( 'finalpdf_delete_media_by_id', 'nonce' );
                $media_id                 = array_key_exists( 'media_id', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['media_id'] ) ) : '';
                $finalpdf_pdf_upload_settings = get_option( 'finalpdf_pdf_upload_save_settings', array() );
                $finalpdf_poster_doc          = array_key_exists( 'sub_pgfw_poster_image_upload', $finalpdf_pdf_upload_settings ) ? $finalpdf_pdf_upload_settings['sub_pgfw_poster_image_upload'] : '';
                if ( '' !== $media_id && '' !== $finalpdf_poster_doc ) {
                        $poster_doc_arr = json_decode( $finalpdf_poster_doc, true );
                        $key            = is_array( $poster_doc_arr ) ? array_search( (int) $media_id, $poster_doc_arr, true ) : '';
                        if ( false !== $key ) {
                                unset( $poster_doc_arr[ $key ] );
                                $finalpdf_pdf_upload_settings['sub_pgfw_poster_image_upload'] = wp_json_encode( $poster_doc_arr );
                                update_option( 'finalpdf_pdf_upload_save_settings', $finalpdf_pdf_upload_settings );
                        }
                }
                echo esc_html( is_array( $poster_doc_arr ) ? count( $poster_doc_arr ) : 0 );
                wp_die();
        }
        /**
         * Deleting PDF from server schedular.
         *
         * @since 1.0.0
         * @return void
         */
        public function finalpdf_delete_pdf_form_server_scheduler() {
                if ( ! wp_next_scheduled( 'finalpdf_cron_delete_pdf_from_server' ) ) {
                        wp_schedule_event( time(), 'weekly', 'finalpdf_cron_delete_pdf_from_server' );
                }
        }
        /**
         * Deleting PDF from server.
         *
         * @since 1.0.0
         * @return void
         */
        public function finalpdf_delete_pdf_from_server() {
                 $upload_dir   = wp_upload_dir();
                $finalpdf_pdf_dir = $upload_dir['basedir'] . '/post_to_pdf/';
                if ( is_dir( $finalpdf_pdf_dir ) ) {
                        $files = glob( $finalpdf_pdf_dir . '*' );
                        foreach ( $files as $file ) {
                                if ( is_file( $file ) ) {
                                        @unlink($file); // phpcs:ignore
                                }
                        }
                }
        }
        /**
         * Reset Default settings.
         *
         * @since 1.0.0
         * @return void
         */
        public function finalpdf_reset_default_settings() {
                 check_ajax_referer( 'finalpdf_delete_media_by_id', 'nonce' );
                $this->finalpdf_default_settings_update();
                wp_die();
        }
        /**
         * Update deafult settings in options table.
         *
         * @since 1.0.0
         * @return void
         */
        public function finalpdf_default_settings_update() {
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
                do_action( 'wps_finalpdf_save_default_pro_settings' );
        }

        /**
         * This function is used to count pending post.
         *
         * @param string $type type.
         * @param string $action action.
         * @return int $result result.
         */
        public function wps_finalpdf_get_count( $type = 'all', $action = 'count' ) {
                global $wpdb;
                $option_result = wp_load_alloptions();
                $result        = array();
                foreach ( $option_result as $option_key => $option_value ) {

                        if ( ( similar_text( 'mwb_pgfw_onboarding_data_skipped', $option_key ) == 32 ) || ( similar_text( 'mwb_all_plugins_active', $option_key ) == 22 ) || ( similar_text( 'mwb_pgfw_onboarding_data_sent', $option_key ) == 29 )
                                || ( similar_text( 'mwb_wpg_check_license_daily', $option_key ) == 27 )
                                || ( similar_text( 'mwb_wpg_activated_timestamp', $option_key ) == 27 ) || ( similar_text( 'mwb_wpg_plugin_update', $option_key ) == 21 )
                                || ( similar_text( 'mwb_wpg_license_key', $option_key ) == 19 ) || ( similar_text( 'mwb_wpg_license_check', $option_key ) == 21 )
                                || ( similar_text( 'mwb_wpg_meta_fields_in_page', $option_key ) == 27 ) || ( similar_text( 'mwb_wpg_meta_fields_in_post', $option_key ) == 27 )
                                || ( similar_text( 'mwb_wpg_meta_fields_in_product', $option_key ) == 30 )
                        ) {

                                $array_val = array(
                                        'option_name'  => $option_key,
                                        'option_value' => $option_value,
                                );
                                $result[]  = $array_val;
                        }
                }
                if ( empty( $result ) ) {
                        return 0;
                }

                if ( 'count' === $action ) {
                        $result = ! empty( $result ) ? count( $result ) : 0;
                }

                return $result;
        }

        /**
         * This is a ajax callback function for migration.
         */
        public function wps_finalpdf_ajax_callbacks() {
                check_ajax_referer( 'wps_finalpdf_migrated_nonce', 'nonce' );
                $event = ! empty( $_POST['event'] ) ? sanitize_text_field( wp_unslash( $_POST['event'] ) ) : '';
                if ( method_exists( $this, $event ) ) {
                        $data = $this->$event( $_POST );
                } else {
                        $data = esc_html__( 'method not found', 'finalpdf' );
                }
                echo wp_json_encode( $data );
                wp_die();
        }

        /**
         * Upgrade_wp_options. (use period)
         *
         * Upgrade_wp_options.
         *
         * @since    1.0.0
         */
        public function finalpdf_import_options_table() {
                $wp_options = array(
                        'mwb_pgfw_onboarding_data_skipped' => '',
                        'mwb_all_plugins_active'           => '',
                        'mwb_pgfw_onboarding_data_sent'    => '',
                        'mwb_wpg_check_license_daily' => '',
                        'mwb_wpg_activated_timestamp' => '',
                        'mwb_wpg_plugin_update'       => '',
                        'mwb_wpg_license_key'        => '',
                        'mwb_wpg_license_check'       => '',
                        'mwb_wpg_meta_fields_in_page' => '',
                        'mwb_wpg_meta_fields_in_post' => '',
                        'mwb_wpg_meta_fields_in_product' => '',
                );

                foreach ( $wp_options as $key => $value ) {

                        $new_key = str_replace( 'mwb_', 'wps_', $key );
                        $new_value = get_option( $key, $value );

                        $arr_val = array();
                        if ( is_array( $new_value ) ) {
                                foreach ( $new_value as $keys => $values ) {
                                        $new_key1 = str_replace( 'mwb_', 'wps_', $keys );
                                        $new_key2 = str_replace( 'mwb-', 'wps-', $new_key1 );

                                        $value_1 = str_replace( 'mwb-', 'wps-', $values );
                                        $value_2 = str_replace( 'mwb_', 'wps_', $value_1 );
                                        $arr_val[ $new_key2 ] = $value_2;
                                }

                                update_option( $new_key, $arr_val );
                                update_option( 'copy_' . $new_key, $new_value );
                                delete_option( $key );
                        } else {
                                update_option( $new_key, $new_value );
                                update_option( 'copy_' . $new_key, $new_value );
                                delete_option( $key );
                        }
                }
        }

        /**
         * Get Previous log data with wps keys.
         */
        public function finalpdf_import_pdflog() {
                global $wpdb;
                $table_name      = $wpdb->prefix . 'wps_pdflog';
                $charset_collate = $wpdb->get_charset_collate();
                $sql             = "CREATE TABLE $table_name (
                        id mediumint(9) NOT NULL AUTO_INCREMENT,
                        postid text,
                        username varchar(500),
                        email varchar(320),
                        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                        PRIMARY KEY  (id)
                ) $charset_collate;";
                require_once ABSPATH . 'wp-admin/includes/upgrade.php';
                dbDelta( $sql );
                $sql = $wpdb->get_results( $wpdb->prepare( 'INSERT INTO  ' . $wpdb->prefix . 'wps_pdflog select * from ' . $wpdb->prefix . 'mwb_pdflog' ) );
        }

        /**
         *
         * Adding the default menu into the WordPress menu.
         *
         * @name wpswings_callback_function
         * @since 1.0.0
         */
        public function wps_finalpdf_welcome_callback_function() {
                include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/finalpdf-welcome.php';
        }

        // PRO TAG /////////////////////////////////.
        /**
         * Taxonomy fields.
         *
         * @param string $finalpdf_taxonomy_settings_arr finalpdf_taxonomy_settings_arr.
         */
        public function finalpdf_setting_fields_for_customising_taxonomy_dummy( $finalpdf_taxonomy_settings_arr ) {
                $finalpdf_taxonomy_settings = get_option( 'finalpdf_taxonomy_fields_save_settings', array() );

                $finalpdf_taxonomy_settings_arr = array();
                $post_types                = get_post_types( array( 'public' => true ) );
                unset( $post_types['attachment'] );
                $i = 0;
                foreach ( $post_types as $post_type ) {
                        $post_taxonomy_fields = get_object_taxonomies( $post_type );
                        $post_taxonomy_field  = array();
                        foreach ( $post_taxonomy_fields as $val ) {
                                $post_taxonomy_field[ $val ] = $val;
                        }
                        $finalpdf_show_type_taxonomy_val = array_key_exists( 'finalpdf_taxonomy_fields_' . $post_type . '_show', $finalpdf_taxonomy_settings ) ? $finalpdf_taxonomy_settings[ 'finalpdf_taxonomy_fields_' . $post_type . '_show' ] : '';

                        $finalpdf_taxonomy_settings_arr[] =
                                array(
                                        'title'        => __( 'Show Taxonomy Fields for ', 'finalpdf' ) . $post_type,
                                        'type'         => 'checkbox',
                                        'description'  => __( 'Selecting this will show the taxonomy fields on PDF.', 'finalpdf' ),
                                        'id'           => 'finalpdf_taxonomy_fields_' . $post_type . '_show',
                                        'value'        => $finalpdf_show_type_taxonomy_val,
                                        'class'        => 'wps_finalpdf_pro_tag finalpdf_taxonomy_fields_' . $post_type . '_show',
                                        'name'         => 'finalpdf_taxonomy_fields_' . $post_type . '_show',
                                        'parent-class' => ( 0 === $i ) ? '' : 'wps_finalpdf_setting_separate_border',
                                );
                        if ( is_array( $post_taxonomy_field ) && count( $post_taxonomy_field ) > 0 ) {
                                $finalpdf_taxonomy_settings_sub_arr = array();
                                foreach ( $post_taxonomy_field as $taxonomy_key ) {
                                        $finalpdf_taxonomy_key_name           = array_key_exists( $taxonomy_key, $finalpdf_taxonomy_settings ) ? $finalpdf_taxonomy_settings[ $taxonomy_key ] : '';
                                        $finalpdf_taxonomy_checkbox_value     = array_key_exists( $taxonomy_key . '_checkbox', $finalpdf_taxonomy_settings ) ? $finalpdf_taxonomy_settings[ $taxonomy_key . '_checkbox' ] : '';
                                        $finalpdf_taxonomy_settings_sub_arr[] = array(
                                                'title'          => $taxonomy_key,
                                                'type'           => 'text',
                                                'id'             => 'wps_finalpdf_' . $taxonomy_key,
                                                'value'          => $finalpdf_taxonomy_key_name,
                                                'class'          => 'wps_finalpdf_pro_tag wps_finalpdf_' . $taxonomy_key,
                                                'name'           => $taxonomy_key,
                                                'placeholder'    => $taxonomy_key,
                                                'checkbox_name'  => $taxonomy_key . '_checkbox',
                                                'checkbox_id'    => $taxonomy_key . '_checkbox',
                                                'checkbox_value' => $finalpdf_taxonomy_checkbox_value,
                                        );
                                }
                                $finalpdf_taxonomy_settings_arr[] = array(
                                        'title' => __( 'Rename Taxonomy Fields', 'finalpdf' ),
                                        'type'  => 'multiwithcheck',
                                        'id'    => 'finalpdf_meta_fields_detail',
                                        'value' => $finalpdf_taxonomy_settings_sub_arr,
                                );
                        }
                        $i++;
                }
                $finalpdf_taxonomy_settings_arr[] = array(
                        'type'        => 'button',
                        'id'          => 'finalpdf_taxonomy_fields_save_settings',
                        'button_text' => __( 'Save Settings', 'finalpdf' ),
                        'class'       => 'finalpdf_taxonomy_fields_save_settings',
                        'name'        => 'finalpdf_taxonomy_fields_save_settings',
                );
                return $finalpdf_taxonomy_settings_arr;
        }
        /**
         * Adding custom subtab for template settings in customisation tab.
         *
         * @since 3.0.0
         * @param array $finalpdf_default_tabs array containing subtabs in customisation ta.
         * @return array
         */
        public function finalpdf_add_custom_template_settings_tab_dummy( $finalpdf_default_tabs ) {
                $finalpdf_default_tabs['finalpdf-cover-page-setting'] = array(
                        'title' => esc_html__( 'Cover Page', 'finalpdf' ),
                        'name'  => 'finalpdf-cover-page-setting',
                );

                $finalpdf_default_tabs['finalpdf-internal-page-setting'] = array(
                        'title' => esc_html__( 'Internal Page', 'finalpdf' ),
                        'name'  => 'finalpdf-internal-page-setting',
                );
                return $finalpdf_default_tabs;
        }
        /**
         * General setting page for pdf.
         *
         * @param array $finalpdf_template_pdf_settings array containing the html for the fields.
         * @return array
         */
        public function finalpdf_template_pdf_settings_page_dummy( $finalpdf_template_pdf_settings ) {
                if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

                        $order_stat = wc_get_order_statuses();
                } else {

                        $order_stat = array();
                }
                $temp       = array(
                        'wc-never' => __( 'Never', 'finalpdf' ),
                );
                $sub_pgfw_pdf_single_download_icon = '';
                // appending the default value.
                $order_statuses = is_array( $order_stat ) ? $temp + $order_stat : $temp;
                // array of html for pdf setting fields.
                $finalpdf_template_pdf_settings   = array(
                        array(
                                'title'       => __( 'Enable Invoice Feature', 'finalpdf' ),
                                'type'        => 'radio-switch',
                                'description' => __( 'Enable this to start the plugin functionality for users.', 'finalpdf' ),
                                'id'          => 'finalpdf_enable_plugin',
                                'value'       => '',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_enable_plugin',
                        ),
                        array(
                                'title'       => __( 'Automatically Attach Invoice', 'finalpdf' ),
                                'type'        => 'radio-switch',
                                'description' => __( 'Enable this to attach invoices with woocommerce mails.', 'finalpdf' ),
                                'id'          => 'finalpdf_send_invoice_automatically',
                                'value'       => '',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_send_invoice_automatically',
                        ),
                        array(
                                'title'       => __( 'Order Status to Send Invoice For', 'finalpdf' ),
                                'type'        => 'select',
                                'description' => __( 'Please choose the status of orders to send invoice for. If you do not want to send invoice please choose never.', 'finalpdf' ),
                                'id'          => 'finalpdf_send_invoice_for',
                                'value'       => get_option( 'finalpdf_send_invoice_for' ),
                                'name'        => 'finalpdf_send_invoice_for',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'placeholder' => '',
                                'options'     => $order_statuses,
                        ),
                        array(
                                'title'       => __( 'Download Invoice for Users at Order Status', 'finalpdf' ),
                                'type'        => 'multiselect',
                                'description' => __( 'Please choose the status of orders to allow invoice download for users.', 'finalpdf' ),
                                'id'          => 'finalpdf_allow_invoice_generation_for_orders',
                                'value'       => get_option( 'finalpdf_allow_invoice_generation_for_orders', array() ),
                                'name'        => 'finalpdf_allow_invoice_generation_for_orders',
                                'class'       => 'wps_finalpdf_pro_tag wpg-multiselect-class wpg-defaut-multiselect',
                                'placeholder' => '',
                                'options'     => $order_statuses,
                        ),
                        array(
                                'title'       => __( 'Generate Invoice from Cache', 'finalpdf' ),
                                'type'        => 'radio-switch',
                                'description' => __( 'Enable this to generate invoices from cache( invoices once downloaded will be stored in the preferred location and will be used later ), please note that once this is enabled changes after invoice generation will not reflect for earlier invoices, however changes will work for new order invoice downloads.', 'finalpdf' ),
                                'id'          => 'finalpdf_generate_invoice_from_cache',
                                'value'       => '',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_generate_invoice_from_cache',
                        ),
                        array(
                                'title' => __( 'How Do You Want To View PDF?', 'finalpdf' ),
                                'type'  => 'select',
                                'description'  => __( 'Choose if You want to  view in new tab or you want to download.', 'finalpdf' ),
                                'id'    => 'finalpdf_view_pdf',
                                'name' => 'finalpdf_view_pdf',
                                'value' => get_option( 'finalpdf_view_pdf' ),
                                'class' => 'wps_finalpdf_pro_tag',
                                'placeholder' => __( 'Select', 'finalpdf' ),
                                'options' => array(
                                        '' => __( 'Select option', 'finalpdf' ),
                                        'view' => __( 'View in new tab', 'finalpdf' ),
                                        'download' => __( 'Download', 'finalpdf' ),
                                ),
                        ),
                );

                $finalpdf_template_pdf_settings   = apply_filters( 'finalpdf_template_pdf_settings_array_filter', $finalpdf_template_pdf_settings );
                $finalpdf_template_pdf_settings[] = array(
                        'type'        => 'button',
                        'id'          => 'finalpdf_general_setting_save',
                        'button_text' => __( 'Save settings', 'finalpdf' ),
                        'class'       => 'finalpdf_general_setting_save',
                        'name'        => 'finalpdf_general_setting_save',
                );

                return $finalpdf_template_pdf_settings;
        }
        /**
         * Invoice settting html array.
         *
         * @param array $invoice_settings_arr invoice setting array fields.
         * @since 1.0.0
         * @return array
         */
        public function finalpdf_template_invoice_setting_html_fields_dummy( $invoice_settings_arr ) {
                $sub_wpg_upload_invoice_company_logo = get_option( 'sub_wpg_upload_invoice_company_logo' );
                $finalpdf_invoice_number_renew_month      = get_option( 'finalpdf_invoice_number_renew_month' );
                $finalpdf_months                          = array(
                        'never' => __( 'Never', 'finalpdf' ),
                        1       => __( 'January', 'finalpdf' ),
                        2       => __( 'February', 'finalpdf' ),
                        3       => __( 'March', 'finalpdf' ),
                        4       => __( 'April', 'finalpdf' ),
                        5       => __( 'May', 'finalpdf' ),
                        6       => __( 'June', 'finalpdf' ),
                        7       => __( 'July', 'finalpdf' ),
                        8       => __( 'August', 'finalpdf' ),
                        9       => __( 'September', 'finalpdf' ),
                        10      => __( 'October', 'finalpdf' ),
                        11      => __( 'November', 'finalpdf' ),
                        12      => __( 'December', 'finalpdf' ),
                );

                if ( ( 'never' !== $finalpdf_invoice_number_renew_month ) && ( $finalpdf_invoice_number_renew_month && '' !== $finalpdf_invoice_number_renew_month ) ) {
                        $number_of_days = cal_days_in_month( CAL_GREGORIAN, $finalpdf_invoice_number_renew_month, gmdate( 'Y' ) );
                        $dates          = range( 1, $number_of_days );
                        $finalpdf_date      = array_combine( $dates, $dates );
                } else {
                        $finalpdf_date = array();
                }

                $invoice_settings_arr = array(
                        array(
                                'title' => __( 'Company Details', 'finalpdf' ),
                                'type'  => 'multi',
                                'id'    => 'finalpdf_company_details',
                                'value' => array(
                                        array(
                                                'title'       => __( 'Name', 'finalpdf' ),
                                                'type'        => 'text',
                                                'id'          => 'finalpdf_company_name',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'value'       => get_option( 'finalpdf_company_name' ),
                                                'name'        => 'finalpdf_company_name',
                                                'placeholder' => __( 'name', 'finalpdf' ),
                                        ),
                                        array(
                                                'title'       => __( 'Address', 'finalpdf' ),
                                                'type'        => 'text',
                                                'id'          => 'finalpdf_company_address',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'value'       => get_option( 'finalpdf_company_address' ),
                                                'name'        => 'finalpdf_company_address',
                                                'placeholder' => __( 'address', 'finalpdf' ),
                                        ),
                                        array(
                                                'title'       => __( 'City', 'finalpdf' ),
                                                'type'        => 'text',
                                                'id'          => 'finalpdf_company_city',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'value'       => get_option( 'finalpdf_company_city' ),
                                                'name'        => 'finalpdf_company_city',
                                                'placeholder' => __( 'city', 'finalpdf' ),
                                        ),
                                        array(
                                                'title'       => __( 'State', 'finalpdf' ),
                                                'type'        => 'text',
                                                'id'          => 'finalpdf_company_state',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'value'       => get_option( 'finalpdf_company_state' ),
                                                'name'        => 'finalpdf_company_state',
                                                'placeholder' => __( 'state', 'finalpdf' ),
                                        ),
                                        array(
                                                'title'       => __( 'Pin', 'finalpdf' ),
                                                'type'        => 'text',
                                                'id'          => 'finalpdf_company_pin',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'value'       => get_option( 'finalpdf_company_pin' ),
                                                'name'        => 'finalpdf_company_pin',
                                                'placeholder' => __( 'pin', 'finalpdf' ),
                                        ),
                                        array(
                                                'title'       => __( 'Phone', 'finalpdf' ),
                                                'type'        => 'text',
                                                'id'          => 'finalpdf_company_phone',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'value'       => get_option( 'finalpdf_company_phone' ),
                                                'name'        => 'finalpdf_company_phone',
                                                'placeholder' => __( 'phone', 'finalpdf' ),
                                        ),
                                        array(
                                                'title'       => __( 'Email', 'finalpdf' ),
                                                'type'        => 'text',
                                                'id'          => 'finalpdf_company_email',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'value'       => get_option( 'finalpdf_company_email' ),
                                                'name'        => 'finalpdf_company_email',
                                                'placeholder' => __( 'email', 'finalpdf' ),
                                        ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Invoice Number', 'finalpdf' ),
                                'type'        => 'multi',
                                'id'          => 'finalpdf_invoice_number',
                                'description' => __( 'This combination will be used as the invoice ID : prefix + number of digits + suffix.', 'finalpdf' ),
                                'value'       => array(
                                        array(
                                                'title'       => __( 'Prefix', 'finalpdf' ),
                                                'type'        => 'text',
                                                'id'          => 'finalpdf_invoice_number_prefix',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'value'       => get_option( 'finalpdf_invoice_number_prefix' ),
                                                'name'        => 'finalpdf_invoice_number_prefix',
                                                'placeholder' => __( 'Prefix', 'finalpdf' ),
                                        ),
                                        array(
                                                'title'       => __( 'Digit', 'finalpdf' ),
                                                'type'        => 'number',
                                                'id'          => 'finalpdf_invoice_number_digit',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'value'       => get_option( 'finalpdf_invoice_number_digit' ),
                                                'name'        => 'finalpdf_invoice_number_digit',
                                                'placeholder' => __( 'digit', 'finalpdf' ),
                                                'min' => 0,
                                                'max' => 3000,
                                        ),
                                        array(
                                                'title'       => __( 'Suffix', 'finalpdf' ),
                                                'type'        => 'text',
                                                'id'          => 'finalpdf_invoice_number_suffix',
                                                'class'       => 'wps_finalpdf_pro_tag',
                                                'value'       => get_option( 'finalpdf_invoice_number_suffix' ),
                                                'name'        => 'finalpdf_invoice_number_suffix',
                                                'placeholder' => __( 'suffix', 'finalpdf' ),
                                        ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Invoice Number Renew Date', 'finalpdf' ),
                                'type'        => 'date-picker',
                                'description' => __( 'Please choose the invoice number to renew date', 'finalpdf' ),
                                'id'          => 'finalpdf_invoice_number_renew',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_invoice_number_renew',
                                'value'       => array(
                                        'month' => array(
                                                'id'      => 'finalpdf_invoice_number_renew_month',
                                                'title'   => __( 'Month', 'finalpdf' ),
                                                'type'    => 'select',
                                                'class'   => 'finalpdf_invoice_number_renew_month wps_finalpdf_pro_tag',
                                                'name'    => 'finalpdf_invoice_number_renew_month',
                                                'value'   => $finalpdf_invoice_number_renew_month,
                                                'options' => $finalpdf_months,
                                        ),
                                        'date'  => array(
                                                'id'      => 'finalpdf_invoice_number_renew_date',
                                                'title'   => __( 'Date', 'finalpdf' ),
                                                'type'    => 'select',
                                                'class'   => 'finalpdf_invoice_number_renew_date wps_finalpdf_pro_tag',
                                                'name'    => 'finalpdf_invoice_number_renew_date',
                                                'value'   => get_option( 'finalpdf_invoice_number_renew_date' ),
                                                'options' => $finalpdf_date,
                                        ),
                                ),
                        ),
                        array(
                                'title'       => __( 'Disclaimer', 'finalpdf' ),
                                'type'        => 'textarea',
                                'description' => __( 'Please enter desclaimer of your choice', 'finalpdf' ),
                                'id'          => 'finalpdf_invoice_disclaimer',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'value'       => get_option( 'finalpdf_invoice_disclaimer' ),
                                'placeholder' => __( 'disclaimer', 'finalpdf' ),
                                'name'        => 'finalpdf_invoice_disclaimer',

                        ),
                        array(
                                'title'       => __( 'Color', 'finalpdf' ),
                                'type'        => 'color',
                                'class'       => 'wps_finalpdf_pro_tag finalpdf_invoice_color',
                                'id'          => 'finalpdf_invoice_color',
                                'description' => __( 'Choose color of your choice for invoices', 'finalpdf' ),
                                'value'       => get_option( 'finalpdf_invoice_color' ),
                                'name'        => 'finalpdf_invoice_color',
                        ),
                        array(
                                'title'        => __( 'Choose Company Logo', 'finalpdf' ),
                                'type'         => 'upload-button',
                                'button_text'  => __( 'Upload Logo', 'finalpdf' ),
                                'class'        => 'wps_finalpdf_pro_tag',
                                'id'           => 'sub_wpg_upload_invoice_company_logo',
                                'value'        => $sub_wpg_upload_invoice_company_logo,
                                'sub_id'       => 'finalpdf_upload_invoice_company_logo',
                                'sub_class'    => 'finalpdf_upload_invoice_company_logo',
                                'sub_name'     => 'finalpdf_upload_invoice_company_logo',
                                'name'         => 'sub_wpg_upload_invoice_company_logo',
                                'parent-class' => 'mwb_pgfw_setting_separate_border',
                                'description'  => '',
                                'img-tag'      => array(
                                        'img-class' => 'finalpdf_invoice_company_logo_image',
                                        'img-id'    => 'finalpdf_invoice_company_logo_image',
                                        'img-style' => ( $sub_wpg_upload_invoice_company_logo ) ? 'margin:10px;height:100px;width:100px;' : 'display:none;margin:10px;height:100px;width:100px;',
                                        'img-src'   => $sub_wpg_upload_invoice_company_logo,
                                ),
                                'img-remove'   => array(
                                        'btn-class' => 'finalpdf_invoice_company_logo_image_remove',
                                        'btn-id'    => 'finalpdf_invoice_company_logo_image_remove',
                                        'btn-text'  => __( 'Remove Logo', 'finalpdf' ),
                                        'btn-title' => __( 'Remove Logo', 'finalpdf' ),
                                        'btn-name'  => 'finalpdf_invoice_company_logo_image_remove',
                                        'btn-style' => ! ( $sub_wpg_upload_invoice_company_logo ) ? 'display:none' : '',
                                ),
                        ),
                        array(
                                'title'       => __( 'Add Logo On Invoice', 'finalpdf' ),
                                'type'        => 'checkbox',
                                'description' => __( 'Please select if you want the above selected image to be used on invoice.', 'finalpdf' ),
                                'id'          => 'finalpdf_is_add_logo_invoice',
                                'value'       => get_option( 'finalpdf_is_add_logo_invoice' ),
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_is_add_logo_invoice',
                        ),
                        array(
                                'title'       => __( 'Choose Template', 'finalpdf' ),
                                'type'        => 'temp-select',
                                'id'          => 'finalpdf_invoice_template',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'description' => __( 'This template will be used as the invoice and packing slip', 'finalpdf' ),
                                'selected'    => get_option( 'finalpdf_invoice_template' ),
                                'value'       => array(
                                        array(
                                                'title' => __( 'Template1', 'finalpdf' ),
                                                'type'  => 'radio',
                                                'id'    => 'finalpdf_invoice_template_one',
                                                'class' => 'finalpdf_invoice_preview finalpdf_invoice_template_one',
                                                'name'  => 'finalpdf_invoice_template',
                                                'value' => 'one',
                                                'src'   => FINALPDF_DIR_URL . 'admin/src/images/template1.png',
                                        ),
                                        array(
                                                'title' => __( 'Template2', 'finalpdf' ),
                                                'type'  => 'radio',
                                                'id'    => 'finalpdf_invoice_template_two',
                                                'class' => 'finalpdf_invoice_preview finalpdf_invoice_template_two',
                                                'src'   => FINALPDF_DIR_URL . 'admin/src/images/template2.png',
                                                'name'  => 'finalpdf_invoice_template',
                                                'value' => 'two',
                                        ),
                                        array(
                                                'title' => __( 'Template3', 'finalpdf' ),
                                                'type'  => 'radio',
                                                'id'    => 'finalpdf_invoice_template_three',
                                                'class' => 'finalpdf_invoice_preview finalpdf_invoice_template_three',
                                                'src'   => FINALPDF_DIR_URL . 'admin/src/images/temp3.png',
                                                'name'  => 'finalpdf_invoice_template',
                                                'value' => 'three',
                                        ),
                                ),
                        ),
                );

                $invoice_settings_arr[] = array(
                        'type'        => 'button',
                        'id'          => 'finalpdf_invoice_setting_save',
                        'button_text' => __( 'Save settings', 'finalpdf' ),
                        'class'       => 'finalpdf_invoice_setting_save',
                        'name'        => 'finalpdf_invoice_setting_save',
                );
                return $invoice_settings_arr;
        }
        /**
         * Html fields for cover page layout.
         *
         * @since 3.0.0
         * @param array $cover_page_html_arr cover page html array.
         * @return array
         */
        public function finalpdf_cover_page_html_layout_fields_dummy( $cover_page_html_arr ) {
                $finalpdf_coverpage_settings_data = get_option( 'finalpdf_coverpage_setting_save', array() );
                $coverpage_single_enable     = array_key_exists( 'finalpdf_cover_page_single_enable', $finalpdf_coverpage_settings_data ) ? $finalpdf_coverpage_settings_data['finalpdf_cover_page_single_enable'] : '';
                $coverpage_bulk_enable       = array_key_exists( 'finalpdf_cover_page_bulk_enable', $finalpdf_coverpage_settings_data ) ? $finalpdf_coverpage_settings_data['finalpdf_cover_page_bulk_enable'] : '';
                $coverpage_company_name      = array_key_exists( 'finalpdf_cover_page_company_name', $finalpdf_coverpage_settings_data ) ? $finalpdf_coverpage_settings_data['finalpdf_cover_page_company_name'] : '';
                $coverpage_company_tagline   = array_key_exists( 'finalpdf_cover_page_company_tagline', $finalpdf_coverpage_settings_data ) ? $finalpdf_coverpage_settings_data['finalpdf_cover_page_company_tagline'] : '';
                $coverpage_company_email     = array_key_exists( 'finalpdf_cover_page_company_email', $finalpdf_coverpage_settings_data ) ? $finalpdf_coverpage_settings_data['finalpdf_cover_page_company_email'] : '';
                $coverpage_company_address   = array_key_exists( 'finalpdf_cover_page_company_address', $finalpdf_coverpage_settings_data ) ? $finalpdf_coverpage_settings_data['finalpdf_cover_page_company_address'] : '';
                $coverpage_company_url       = array_key_exists( 'finalpdf_cover_page_company_url', $finalpdf_coverpage_settings_data ) ? $finalpdf_coverpage_settings_data['finalpdf_cover_page_company_url'] : '';
                $cover_page_image            = array_key_exists( 'sub_pgfw_cover_page_image_upload', $finalpdf_coverpage_settings_data ) ? $finalpdf_coverpage_settings_data['sub_pgfw_cover_page_image_upload'] : '';
                $cover_page_company_logo     = array_key_exists( 'sub_pgfw_cover_page_company_logo_upload', $finalpdf_coverpage_settings_data ) ? $finalpdf_coverpage_settings_data['sub_pgfw_cover_page_company_logo_upload'] : '';
                $coverpage_company_phone     = array_key_exists( 'finalpdf_cover_page_company_Phone', $finalpdf_coverpage_settings_data ) ? $finalpdf_coverpage_settings_data['finalpdf_cover_page_company_Phone'] : '';

                $cover_page_html_arr = array(
                        array(
                                'title'       => __( 'Add Cover Page to Single PDF', 'finalpdf' ),
                                'type'        => 'checkbox',
                                'id'          => 'finalpdf_cover_page_single_enable',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_cover_page_single_enable',
                                'value'       => $coverpage_single_enable,
                                'description' => __( 'Selecting this will add cover page to generated single PDFs', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Add Cover Page to Bulk PDF', 'finalpdf' ),
                                'type'        => 'checkbox',
                                'id'          => 'finalpdf_cover_page_bulk_enable',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_cover_page_bulk_enable',
                                'value'       => $coverpage_bulk_enable,
                                'description' => __( 'Selecting this will add cover page to generated bulk continuation PDFs', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Company Name', 'finalpdf' ),
                                'type'        => 'text',
                                'id'          => 'finalpdf_cover_page_company_name',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_cover_page_company_name',
                                'value'       => $coverpage_company_name,
                                'description' => __( 'Add company name at the cover page.', 'finalpdf' ),
                                'placeholder' => __( 'company name', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Company Logo', 'finalpdf' ),
                                'type'        => 'upload-button',
                                'button_text' => __( 'Upload Image', 'finalpdf' ),
                                'sub_class'   => 'finalpdf_cover_page_company_logo_upload',
                                'sub_id'      => 'finalpdf_cover_page_company_logo_upload',
                                'id'          => 'sub_pgfw_cover_page_company_logo_upload',
                                'name'        => 'sub_pgfw_cover_page_company_logo_upload',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'value'       => $cover_page_company_logo,
                                'sub_name'    => 'finalpdf_cover_page_company_logo_upload',
                                'img-tag'     => array(
                                        'img-class' => 'finalpdf_cover_page_company_logo',
                                        'img-id'    => 'finalpdf_cover_page_company_logo',
                                        'img-style' => ( $cover_page_company_logo ) ? 'width:100px;height:100px;margin-right:10px;' : 'display:none;margin-right:10px;width:100px;height:100px;',
                                        'img-src'   => $cover_page_company_logo,
                                ),
                                'img-remove'  => array(
                                        'btn-class' => 'finalpdf_cover_page_company_logo_remove',
                                        'btn-id'    => 'finalpdf_cover_page_company_logo_remove',
                                        'btn-text'  => __( 'Remove logo', 'finalpdf' ),
                                        'btn-title' => __( 'Remove logo', 'finalpdf' ),
                                        'btn-name'  => 'finalpdf_cover_page_company_logo_remove',
                                        'btn-style' => ! ( $cover_page_company_logo ) ? 'display:none' : '',
                                ),
                        ),
                        array(
                                'title'       => __( 'Cover Page Image', 'finalpdf' ),
                                'type'        => 'upload-button',
                                'button_text' => __( 'Upload Image', 'finalpdf' ),
                                'sub_class'   => 'finalpdf_cover_page_image_upload',
                                'sub_id'      => 'finalpdf_cover_page_image_upload',
                                'id'          => 'sub_pgfw_cover_page_image_upload',
                                'name'        => 'sub_pgfw_cover_page_image_upload',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'value'       => $cover_page_image,
                                'sub_name'    => 'finalpdf_cover_page_image_upload',
                                'img-tag'     => array(
                                        'img-class' => 'finalpdf_cover_page_image',
                                        'img-id'    => 'finalpdf_cover_page_image',
                                        'img-style' => ( $cover_page_image ) ? 'width:100px;height:100px;margin-right:10px;' : 'display:none;margin-right:10px;width:100px;height:100px;',
                                        'img-src'   => $cover_page_image,
                                ),
                                'img-remove'  => array(
                                        'btn-class' => 'finalpdf_cover_page_image_remove',
                                        'btn-id'    => 'finalpdf_cover_page_image_remove',
                                        'btn-text'  => __( 'Remove image', 'finalpdf' ),
                                        'btn-title' => __( 'Remove image', 'finalpdf' ),
                                        'btn-name'  => 'finalpdf_cover_page_image_remove',
                                        'btn-style' => ! ( $cover_page_image ) ? 'display:none' : '',
                                ),
                        ),
                        array(
                                'title'       => __( 'Company Tagline', 'finalpdf' ),
                                'type'        => 'textarea',
                                'id'          => 'finalpdf_cover_page_company_tagline',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_cover_page_company_tagline',
                                'value'       => $coverpage_company_tagline,
                                'description' => __( 'Add company tagline at the cover page.', 'finalpdf' ),
                                'placeholder' => __( 'company tagline', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Company Email', 'finalpdf' ),
                                'type'        => 'text',
                                'id'          => 'finalpdf_cover_page_company_email',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_cover_page_company_email',
                                'value'       => $coverpage_company_email,
                                'description' => __( 'Add email at the cover page bottom.', 'finalpdf' ),
                                'placeholder' => __( 'email', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Company Address', 'finalpdf' ),
                                'type'        => 'textarea',
                                'id'          => 'finalpdf_cover_page_company_address',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_cover_page_company_address',
                                'value'       => $coverpage_company_address,
                                'description' => __( 'Add address at the cover page bottom.', 'finalpdf' ),
                                'placeholder' => __( 'address', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Company URL', 'finalpdf' ),
                                'type'        => 'text',
                                'id'          => 'finalpdf_cover_page_company_url',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_cover_page_company_url',
                                'value'       => $coverpage_company_url,
                                'description' => __( 'Add URL at the cover page bottom.', 'finalpdf' ),
                                'placeholder' => __( 'url', 'finalpdf' ),
                        ),
                        array(
                                'title'       => __( 'Company Phone No.', 'finalpdf' ),
                                'type'        => 'text',
                                'id'          => 'finalpdf_cover_page_company_Phone',
                                'class'       => 'wps_finalpdf_pro_tag',
                                'name'        => 'finalpdf_cover_page_company_Phone',
                                'value'       => $coverpage_company_phone,
                                'description' => __( 'Add Phone at the cover page bottom.', 'finalpdf' ),
                                'placeholder' => __( 'phone no.', 'finalpdf' ),
                        ),
                        array(
                                'type'        => 'button',
                                'id'          => 'finalpdf_coverpage_setting_save',
                                'button_text' => __( 'Save Settings', 'finalpdf' ),
                                'class'       => 'finalpdf_coverpage_setting_save',
                                'name'        => 'finalpdf_coverpage_setting_save',
                        ),
                );
                return $cover_page_html_arr;
        }
        /**
         * Add custom Page size in dropdown.
         *
         * @since 3.0.0
         * @param array $finalpdf_custom_page_size array containing font styles.
         * @return array
         */
        public function finalpdf_custom_page_size_in_dropdown( $finalpdf_custom_page_size ) {

                $finalpdf_custom_page_size['custom_page'] = __( 'Custom page size', 'finalpdf' );

                return $finalpdf_custom_page_size;
        }
        /**
         * Set cron.
         *
         * @since 3.0.0
         */
        public function wps_finalpdf_set_cron_for_plugin_notification() {
                $wps_finalpdf_offset = get_option( 'gmt_offset' );
                $wps_finalpdf_time   = time() + $wps_finalpdf_offset * 60 * 60;
                if ( ! wp_next_scheduled( 'wps_finalpdf_check_for_notification_update' ) ) {
                        wp_schedule_event( $wps_finalpdf_time, 'daily', 'wps_finalpdf_check_for_notification_update' );
                }
        }
        /**
         * Add Notice.
         *
         * @since 3.0.0
         */
        public function wps_finalpdf_save_notice_message() {
                $wps_notification_data = $this->wps_finalpdf_get_update_notification_data();
                if ( is_array( $wps_notification_data ) && ! empty( $wps_notification_data ) ) {
                        $banner_id      = array_key_exists( 'notification_id', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_id'] : '';
                        $banner_image = array_key_exists( 'notification_message', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_image'] : '';
                        $banner_url = array_key_exists( 'notification_message', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_url'] : '';
                        $banner_type = array_key_exists( 'notification_message', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_type'] : '';
                        update_option( 'wps_finalpdf_notify_new_banner_id', $banner_id );
                        update_option( 'wps_finalpdf_notify_new_banner_image', $banner_image );
                        update_option( 'wps_finalpdf_notify_new_banner_url', $banner_url );
                        if ( 'regular' == $banner_type ) {
                                update_option( 'wps_finalpdf_notify_hide_baneer_notification', '' );
                        }
                }
        }
        /**
         * Update notification data.
         *
         * @since 3.0.0
         */
        public function wps_finalpdf_get_update_notification_data() {
                $wps_notification_data = array();
                $url                   = 'https://yourwebsite.com/demo';
                $attr                  = array(
                        'action'         => 'wps_notification_fetch',
                        'plugin_version' => FINALPDF_VERSION,
                );
                $query                 = esc_url_raw( add_query_arg( $attr, $url ) );
                $response              = wp_remote_get(
                        $query,
                        array(
                                'timeout'   => 20,
                                'sslverify' => false,
                        )
                );

                if ( is_wp_error( $response ) ) {
                        $error_message = $response->get_error_message();
                        echo '<p><strong>Something went wrong: ' . esc_html( stripslashes( $error_message ) ) . '</strong></p>';
                } else {
                        $wps_notification_data = json_decode( wp_remote_retrieve_body( $response ), true );
                }
                return $wps_notification_data;
        }

        /**
         * Display Notice.
         *
         * @since 3.0.0
         */
        public function wps_finalpdf_dismiss_notice_banner_callback() {
                if ( isset( $_REQUEST['wps_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps_nonce'] ) ), 'wps-finalpdf-verify-notice-nonce' ) ) {

                        $banner_id = get_option( 'wps_finalpdf_notify_new_banner_id', false );

                        if ( isset( $banner_id ) && '' != $banner_id ) {
                                update_option( 'wps_finalpdf_notify_hide_baneer_notification', $banner_id );
                        }

                        wp_send_json_success();
                }
        }


        /**
         * Register google embed block.
         *
         * @return void
         */
        public function register_google_embed_blocks() {
                $wps_finalpdf_is_pro_active = false;
                $wps_finalpdf_plugin_list = get_option( 'active_plugins' );
                $wps_finalpdf_plugin = 'finalpdf/finalpdf.php';
                if ( in_array( $wps_finalpdf_plugin, $wps_finalpdf_plugin_list ) ) {
                        $wps_finalpdf_is_pro_active = true;
                }
                $license_check = get_option( 'wps_finalpdf_license_check', 0 );

                wp_register_script(
                        'google-embed-block',
                        plugins_url( 'src/js/pdf-google-embed-block.js', __FILE__ ),
                        array( 'wp-blocks', 'wp-editor', 'wp-element', 'wp-components' ),
                        filemtime( plugin_dir_path( __FILE__ ) . '/src/js/pdf-google-embed-block.js' )
                );

                register_block_type(
                        'wpswings/google-embed',
                        array(
                                'editor_script' => 'google-embed-block',
                        )
                );

                register_block_type(
                        'custom/calendly-embed',
                        array(
                                'editor_script' => 'google-embed-block',
                                'render_callback' => 'wps_render_calendly_block',
                                'attributes' => array(
                                        'url' => array(
                                                'type' => 'string',
                                                'default' => 'https://yourwebsite.com/contact
                                        ),
                                ),
                        )
                );

                wp_localize_script(
                        'google-embed-block',
                        'embed_block_param',
                        array(
                                'ajaxurl'             => admin_url( 'admin-ajax.php' ),
                                'reloadurl'           => admin_url( 'admin.php?page=finalpdf_menu' ),
                                'is_pro_active' => $wps_finalpdf_is_pro_active,
                                'license_check' => $license_check,
                                'is_linkedln_active' => get_option( 'wps_embed_source_linkedin', '' ),
                                'is_loom_active' => get_option( 'wps_embed_source_loom', '' ),
                                'is_twitch_active' => get_option( 'wps_embed_source_twitch', '' ),
                                'is_ai_chatbot_active' => get_option( 'wps_embed_source_ai_chatbot', '' ),
                                'is_canva_active' => get_option( 'wps_embed_source_canva', '' ),
                                'is_reddit_active' => get_option( 'wps_embed_source_reddit', '' ),
                                'is_google_active' => get_option( 'wps_embed_source_google_elements', '' ),
                                'is_calendly_active' => get_option( 'wps_embed_source_calendly', '' ),
                                'is_strava_active' => get_option( 'wps_embed_source_strava', '' ),
                                'is_rss_feed_active' => get_option( 'wps_embed_source_rss_feed', '' ),
                                'is_x_active' => get_option( 'wps_embed_source_x', '' ),
                                'is_view_pdf_active' => get_option( 'wps_embed_source_pdf_embed', '' ),
                        )
                );
        }

        /**
         * Callback of calendly Block.
         *
         * @param array $attributes string of HTML.
         * @return string
         */
        public function wps_render_calendly_block( $attributes ) {
                $url = esc_url( $attributes['url'] );
                return '<iframe src="' . $url . '" width="100%" height="600px" style="border: none;color : red;" ></iframe>';
        }

        /**
         * This is a ajax callback function for migration.
         */
        public function wps_finalpdf_save_embed_source_callback() {

                $source = ! empty( $_POST['souce_name'] ) ? sanitize_text_field( wp_unslash( $_POST['souce_name'] ) ) : '';
                $value = ! empty( $_POST['is_enable'] ) ? sanitize_text_field( wp_unslash( $_POST['is_enable'] ) ) : '';
                $wps_nonce = ! empty( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

                if ( ! wp_verify_nonce( $wps_nonce, 'wps_finalpdf_embed_ajax_nonce' ) ) {
                        wp_send_json_error( 'Invalid nonce' );
                }

                if ( ! current_user_can( 'manage_options' ) ) {
                        wp_send_json_error( 'Permission denied' );
                }

                update_option( "wps_embed_source_{$source}", $value );
                wp_send_json_success( "Saved $source as $value" );

                wp_die();
        }
}
