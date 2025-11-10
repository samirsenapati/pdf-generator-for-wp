<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://finaldoc.com/
 * @since      1.0.0
 *
 * @package    FinalPDF
 * @subpackage FinalPDF/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    FinalPDF
 * @subpackage FinalPDF/includes
 * @author     FinalDoc <support@finaldoc.com>
 */
class FinalPDF {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @var      FinalPDF_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $finalpdf_onboard    To initializsed the object of class onboard.
	 */
	protected $finalpdf_onboard;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area,
	 * the public-facing side of the site and common side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'FINALPDF_VERSION' ) ) {

			$this->version = FINALPDF_VERSION;
		} else {

			$this->version = '1.4.1';
		}

		$this->plugin_name = 'finalpdf';

		$this->finalpdf_dependencies();
		$this->finalpdf_locale();
		if ( is_admin() ) {
			$this->finalpdf_admin_hooks();
		} else {
			$this->finalpdf_public_hooks();
		}
		$this->finalpdf_common_hooks();

		$this->finalpdf_api_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - FinalPDF_Loader. Orchestrates the hooks of the plugin.
	 * - FinalPDF_i18n. Defines internationalization functionality.
	 * - FinalPDF_Admin. Defines all hooks for the admin area.
	 * - FinalPDF_Common. Defines all hooks for the common area.
	 * - FinalPDF_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function finalpdf_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-finalpdf-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-finalpdf-i18n.php';

		if ( is_admin() ) {

			// The class responsible for defining all actions that occur in the admin area.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-finalpdf-admin.php';

			// The class responsible for on-boarding steps for plugin.
			if ( is_dir( plugin_dir_path( dirname( __FILE__ ) ) . 'onboarding' ) && ! class_exists( 'FinalPDF_Onboarding_Steps' ) ) {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-finalpdf-onboarding-steps.php';
			}

			if ( class_exists( 'FinalPDF_Onboarding_Steps' ) ) {
				$finalpdf_onboard_steps = new FinalPDF_Onboarding_Steps();
			}
		} else {

			// The class responsible for defining all actions that occur in the public-facing side of the site.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-finalpdf-public.php';

		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'package/rest-api/class-finalpdf-rest-api.php';

		/**
		 * This class responsible for defining common functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'common/class-finalpdf-common.php';
		$this->loader = new FinalPDF_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the FinalPDF_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function finalpdf_locale() {

		$plugin_i18n = new FinalPDF_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function finalpdf_admin_hooks() {

		$finalpdf_plugin_admin = new FinalPDF_Admin( $this->finalpdf_get_plugin_name(), $this->finalpdf_get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $finalpdf_plugin_admin, 'finalpdf_admin_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $finalpdf_plugin_admin, 'finalpdf_admin_enqueue_scripts' );

		// Add settings menu for FinalPDF For WordPress.
		$this->loader->add_action( 'admin_menu', $finalpdf_plugin_admin, 'finalpdf_options_page' );
		$this->loader->add_action( 'admin_menu', $finalpdf_plugin_admin, 'wps_finalpdf_remove_default_submenu', 50 );

		// All admin actions and filters after License Validation goes here.
		// Adding sub menu page.
		$this->loader->add_filter( 'wps_add_plugins_menus_array', $finalpdf_plugin_admin, 'finalpdf_admin_submenu_page', 15 );

		// Fields for general settings tab.
		$this->loader->add_filter( 'finalpdf_general_settings_array', $finalpdf_plugin_admin, 'finalpdf_admin_general_settings_page', 10 );
		// Fields for display setting tab.
		$this->loader->add_filter( 'finalpdf_display_settings_array', $finalpdf_plugin_admin, 'finalpdf_admin_display_settings_page', 10 );
		// Fields for header customizations.
		$this->loader->add_filter( 'finalpdf_header_settings_array', $finalpdf_plugin_admin, 'finalpdf_admin_header_settings_page', 10 );
		// Fields for footer customizations.
		$this->loader->add_filter( 'finalpdf_footer_settings_array', $finalpdf_plugin_admin, 'finalpdf_admin_footer_settings_page', 10 );
		// Fields for body customizations.
		$this->loader->add_filter( 'finalpdf_body_settings_array', $finalpdf_plugin_admin, 'finalpdf_admin_body_settings_page', 10 );
		// Fields for advanced settings.
		$this->loader->add_filter( 'finalpdf_advanced_settings_array', $finalpdf_plugin_admin, 'finalpdf_admin_advanced_settings_page', 10 );
		// Fields for meta fields settings.
		$this->loader->add_filter( 'finalpdf_meta_fields_settings_array', $finalpdf_plugin_admin, 'finalpdf_admin_meta_fields_settings_page', 10 );
		// Fields for PDF upload settings.
		$this->loader->add_filter( 'finalpdf_pdf_upload_fields_settings_array', $finalpdf_plugin_admin, 'finalpdf_admin_pdf_upload_settings_page', 10 );
		// Request handling for saving general settings.
		$this->loader->add_action( 'admin_init', $finalpdf_plugin_admin, 'finalpdf_admin_save_tab_settings' );
		// Deleting media from table by media ID.
		$this->loader->add_action( 'wp_ajax_wps_pgfw_delete_poster_by_media_id_from_table', $finalpdf_plugin_admin, 'wps_finalpdf_delete_poster_by_media_id_from_table' );
		// schedular fo deleting documents form server.
		$this->loader->add_action( 'init', $finalpdf_plugin_admin, 'finalpdf_delete_pdf_form_server_scheduler' );
		$this->loader->add_action( 'finalpdf_cron_delete_pdf_from_server', $finalpdf_plugin_admin, 'finalpdf_delete_pdf_from_server' );
		// Reset all the settings to default.
		$this->loader->add_action( 'wp_ajax_pgfw_reset_default_settings', $finalpdf_plugin_admin, 'finalpdf_reset_default_settings' );
		
		$this->loader->add_action( 'wp_ajax_wpg_ajax_callbacks', $finalpdf_plugin_admin, 'wps_finalpdf_ajax_callbacks' );
		$this->loader->add_filter( 'wps_finalpdf_custom_page_size_filter_hook', $finalpdf_plugin_admin, 'finalpdf_custom_page_size_in_dropdown' );
		///////////////
		$this->loader->add_action( 'admin_init', $finalpdf_plugin_admin, 'wps_finalpdf_set_cron_for_plugin_notification' );
		$this->loader->add_action( 'wps_finalpdf_check_for_notification_update', $finalpdf_plugin_admin, 'wps_finalpdf_save_notice_message' );
		$this->loader->add_action( 'wp_ajax_wps_pgfw_dismiss_notice_banner', $finalpdf_plugin_admin, 'wps_finalpdf_dismiss_notice_banner_callback' );
		
		$this->loader->add_action( 'init', $finalpdf_plugin_admin, 'register_google_embed_blocks' );
	
							// PRO PLUGIN DUMMY CONTENT HTML FUNCTIONS  ////////////.
		if ( ! is_plugin_active( 'finalpdf/finalpdf.php' ) ) {
			$this->loader->add_filter( 'finalpdf_taxonomy_settings_array_dummy', $finalpdf_plugin_admin, 'finalpdf_setting_fields_for_customising_taxonomy_dummy' );
			$this->loader->add_action( 'finalpdf_plugin_standard_admin_settings_sub_tabs_dummy', $finalpdf_plugin_admin, 'finalpdf_add_custom_template_settings_tab_dummy' );
			$this->loader->add_filter( 'finalpdf_template_pdf_settings_array_dummy', $finalpdf_plugin_admin, 'finalpdf_template_pdf_settings_page_dummy', 10 );
			$this->loader->add_filter( 'finalpdf_template_invoice_settings_array_dummy', $finalpdf_plugin_admin, 'finalpdf_template_invoice_setting_html_fields_dummy' );
			$this->loader->add_filter( 'finalpdf_layout_cover_page_setting_html_array_dummy', $finalpdf_plugin_admin, 'finalpdf_cover_page_html_layout_fields_dummy' );
		}

		$this->loader->add_action( 'wp_ajax_wps_pgfw_save_embed_source', $finalpdf_plugin_admin, 'wps_finalpdf_save_embed_source_callback' );
	}

	/**
	 * Register all of the hooks related to the common functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function finalpdf_common_hooks() {

		$finalpdf_plugin_common = new FinalPDF_Common( $this->finalpdf_get_plugin_name(), $this->finalpdf_get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $finalpdf_plugin_common, 'finalpdf_common_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $finalpdf_plugin_common, 'finalpdf_common_enqueue_scripts' );
		$pdf_general_settings_arr = get_option( 'finalpdf_general_settings_save', array() );
		$finalpdf_enable_plugin       = array_key_exists( 'finalpdf_enable_plugin', $pdf_general_settings_arr ) ? $pdf_general_settings_arr['finalpdf_enable_plugin'] : '';
		if ( 'yes' === $finalpdf_enable_plugin ) {
			// catching pdf generate link with $_GET.
			$this->loader->add_action( 'init', $finalpdf_plugin_common, 'finalpdf_generate_pdf_link_catching_user', 20 );
			$this->loader->add_action( 'plugins_loaded', $finalpdf_plugin_common, 'finalpdf_poster_download_shortcode' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_pgfw_ajax_for_single_pdf_mail', $finalpdf_plugin_common, 'wps_finalpdf_generate_pdf_single_and_mail' );
			$this->loader->add_action( 'wp_ajax_wps_pgfw_ajax_for_single_pdf_mail', $finalpdf_plugin_common, 'wps_finalpdf_generate_pdf_single_and_mail' );

			$this->loader->add_action( 'load-edit.php', $finalpdf_plugin_common, 'finalpdf_aspose_pdf_exporter_bulk_action' );
			// Bulk pdf gentrate hook for the page post and producta.
			$this->loader->add_filter( 'bulk_actions-edit-post', $finalpdf_plugin_common, 'finalpdf_add_custom_bulk_action_post', 10, 2 );
			$this->loader->add_filter( 'bulk_actions-edit-page', $finalpdf_plugin_common, 'finalpdf_add_custom_bulk_actions_page', 10, 2 );
			$this->loader->add_filter( 'bulk_actions-edit-product', $finalpdf_plugin_common, 'finalpdf_add_custom_bulk_actionss_product', 10, 2 );
			// invoice.
			$finalpdf_enable_plugin = get_option( 'finalpdf_enable_plugin' );
			if ( 'yes' === $finalpdf_enable_plugin ) {
				//adding shortcodes to fetch all order detials [ISFW_FETCH_ORDER].
				$this->loader->add_action( 'plugins_loaded', $finalpdf_plugin_common, 'finalpdf_fetch_order_details_shortcode' );
				$this->loader->add_action( 'finalpdf_reset_invoice_number_hook', $finalpdf_plugin_common, 'finalpdf_reset_invoice_number' );
			}
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality.
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function finalpdf_public_hooks() {

		$finalpdf_plugin_public = new FinalPDF_Public( $this->finalpdf_get_plugin_name(), $this->finalpdf_get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $finalpdf_plugin_public, 'finalpdf_public_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $finalpdf_plugin_public, 'finalpdf_public_enqueue_scripts' );
		$pdf_general_settings_arr     = get_option( 'finalpdf_general_settings_save', array() );
		$finalpdf_display_settings        = get_option( 'finalpdf_save_admin_display_settings', array() );
		$finalpdf_enable_plugin           = array_key_exists( 'finalpdf_enable_plugin', $pdf_general_settings_arr ) ? $pdf_general_settings_arr['finalpdf_enable_plugin'] : '';
		$finalpdf_pdf_icon_after          = array_key_exists( 'finalpdf_display_pdf_icon_after', $finalpdf_display_settings ) ? $finalpdf_display_settings['finalpdf_display_pdf_icon_after'] : '';
		$finalpdf_exclude_wp_filter_hooks = array( 'before_content', 'after_content' );
		if ( 'yes' === $finalpdf_enable_plugin ) {
			$this->loader->add_action( 'plugins_loaded', $finalpdf_plugin_public, 'finalpdf_shortcode_to_generate_pdf' );
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
				if ( '' !== $finalpdf_pdf_icon_after && ! in_array( $finalpdf_pdf_icon_after, $finalpdf_exclude_wp_filter_hooks, true ) ) {
					// post to pdf generate button if woocomerce is activated.
					$this->loader->add_action( $finalpdf_pdf_icon_after, $finalpdf_plugin_public, 'finalpdf_show_download_icon_to_users_for_woocommerce' );
				} else {
					// Post to pdf generate button if woocommerce is activated but hook the content is used.
					$this->loader->add_filter( 'the_content', $finalpdf_plugin_public, 'finalpdf_show_download_icon_to_users', 20 );
				}
			} else {
				// Post to pdf generate button if woocommerce is not activated.
				$this->loader->add_filter( 'the_content', $finalpdf_plugin_public, 'finalpdf_show_download_icon_to_users', 20 );
			}
		}

	}

	/**
	 * Register all of the hooks related to the api functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function finalpdf_api_hooks() {

		$finalpdf_plugin_api = new FinalPDF_Rest_Api( $this->finalpdf_get_plugin_name(), $this->finalpdf_get_version() );

		$this->loader->add_action( 'rest_api_init', $finalpdf_plugin_api, 'wps_finalpdf_add_endpoint' );

	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function finalpdf_run() {
		$this->loader->finalpdf_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function finalpdf_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    FinalPDF_Loader    Orchestrates the hooks of the plugin.
	 */
	public function finalpdf_get_loader() {
		return $this->loader;
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    FinalPDF_Onboard    Orchestrates the hooks of the plugin.
	 */
	public function finalpdf_get_onboard() {
		return $this->finalpdf_onboard;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function finalpdf_get_version() {
		return $this->version;
	}

	/**
	 * Predefined default wps_finalpdf_plug tabs.
	 *
	 * @return array An key=>value pair of FinalPDF For WordPress tabs.
	 */
	public function wps_finalpdf_plug_default_tabs() {
		$finalpdf_default_tabs = array();

		$finalpdf_default_tabs['finalpdf-general'] = array(
			'title' => esc_html__( 'General Settings', 'finalpdf' ),
			'name'  => 'finalpdf-general',
		);

		$finalpdf_default_tabs['finalpdf-pdf-setting'] = array(
			'title' => esc_html__( 'PDF Settings', 'finalpdf' ),
			'name'  => 'finalpdf-pdf-setting',
		);

		$finalpdf_default_tabs['finalpdf-advanced'] = array(
			'title' => esc_html__( 'Advanced Settings', 'finalpdf' ),
			'name'  => 'finalpdf-advanced',
		);

		$finalpdf_default_tabs['finalpdf-meta-fields'] = array(
			'title' => esc_html__( 'Meta Fields Settings', 'finalpdf' ),
			'name'  => 'finalpdf-meta-fields',
		);
		$finalpdf_default_tabs['finalpdf-embed-source'] = array(
			'title' => esc_html__( 'Embed Source', 'finalpdf' ),
			'name'  => 'finalpdf-embed-source',
		);
		// Check if the pro plugin is active.
		if ( ! is_plugin_active( 'finalpdf/finalpdf.php' ) ) {
			// Pro plugin is active.
			$finalpdf_default_tabs['finalpdf-taxonomy'] = array(
				'title' => esc_html__( 'Taxonomy Settings', 'finalpdf' ),
				'name'  => 'finalpdf-taxonomy',
			);

			$finalpdf_default_tabs['finalpdf-layout-settings'] = array(
				'title' => esc_html__( 'Layout Settings', 'finalpdf' ),
				'name'  => 'finalpdf-layout-settings',
			);

			$finalpdf_default_tabs['finalpdf-logs'] = array(
				'title' => esc_html__( 'PDF Logs', 'finalpdf' ),
				'name'  => 'finalpdf-logs',
			);
			// tabs for the invoice genration.
			$finalpdf_default_tabs['finalpdf-invoice-general'] = array(
				'title' => esc_html__( 'Invoice settings', 'finalpdf' ),
				'name'  => 'finalpdf-invoice-general',
			);
			// invoice main page setting tab.
			$finalpdf_default_tabs['finalpdf-invoice-page-setting'] = array(
				'title' => esc_html__( 'Invoice page settings', 'finalpdf' ),
				'name'  => 'finalpdf-invoice-page-setting',
			);
		}
						// END DUMMY CODE TABS ////////.
			$finalpdf_default_tabs = apply_filters( 'wps_finalpdf_plugin_standard_admin_settings_tabs', $finalpdf_default_tabs );

			$finalpdf_default_tabs['finalpdf-pdf-upload'] = array(
				'title' => esc_html__( 'PDF Upload', 'finalpdf' ),
				'name'  => 'finalpdf-pdf-upload',
			);

			$finalpdf_default_tabs['finalpdf-shortcode'] = array(
				'title' => esc_html__( 'Shortcodes', 'finalpdf' ),
				'name'  => 'finalpdf-shortcode',
			);

			$finalpdf_default_tabs['finalpdf-overview'] = array(
				'title' => esc_html__( 'Overview', 'finalpdf' ),
				'name'  => 'finalpdf-overview',
			);
			return $finalpdf_default_tabs;
	}
	/**
	 * Customizations sub tabs.
	 *
	 * @since 1.0.0
	 * @return array array containing sub tabs menus details.
	 */
	public function wps_finalpdf_plug_default_sub_tabs() {
		$finalpdf_default_tabs = array();
		$finalpdf_default_tabs['finalpdf-pdf-icon-setting'] = array(
			'title' => esc_html__( 'Icon Display', 'finalpdf' ),
			'name'  => 'finalpdf-pdf-icon-setting',
		);

		$finalpdf_default_tabs['finalpdf-header'] = array(
			'title' => esc_html__( 'Header', 'finalpdf' ),
			'name'  => 'finalpdf-header',
		);

		$finalpdf_default_tabs['finalpdf-body'] = array(
			'title' => esc_html__( 'Body', 'finalpdf' ),
			'name'  => 'finalpdf-body',
		);

		$finalpdf_default_tabs['finalpdf-footer'] = array(
			'title' => esc_html__( 'Footer', 'finalpdf' ),
			'name'  => 'finalpdf-footer',
		);

		return $finalpdf_default_tabs;
	}
	/**
	 * Loading sub tabs for layout settings used by pro plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function wps_finalpdf_plug_layout_setting_sub_tabs() {
		$finalpdf_default_sub_tabs = array();
		$finalpdf_default_sub_tabs = apply_filters( 'wps_finalpdf_plugin_standard_admin_settings_sub_tabs', $finalpdf_default_sub_tabs );
		return $finalpdf_default_sub_tabs;
	}
	/**
	 * Loading sub tabs for layout settings used by pro plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function wps_finalpdf_plug_layout_setting_sub_tabs_dummy() {
		$finalpdf_default_sub_tabs = array();
		$finalpdf_default_sub_tabs = apply_filters( 'finalpdf_plugin_standard_admin_settings_sub_tabs_dummy', $finalpdf_default_sub_tabs );
		return $finalpdf_default_sub_tabs;
	}
	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 * @param string $path path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function wps_finalpdf_plug_load_template( $path, $params = array() ) {
		$finalpdf_file_path = FINALPDF_DIR_PATH . $path;
		$finalpdf_file_path = apply_filters( 'wps_finalpdf_setting_page_loading_filter_hook', $finalpdf_file_path, $path );
		if ( file_exists( $finalpdf_file_path ) ) {
			include $finalpdf_file_path;
		} else {
			/* translators: %s: file path */
			$finalpdf_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'finalpdf' ), $finalpdf_file_path );
			$this->wps_finalpdf_plug_admin_notice( $finalpdf_notice, 'error' );
		}
	}
	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 * @param string $path path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function wps_finalpdf_plug_load_sub_template( $path, $params = array() ) {
		$finalpdf_file_path = FINALPDF_DIR_PATH . $path;
		$finalpdf_file_path = apply_filters( 'wps_finalpdf_setting_sub_page_loading_filter_hook', $finalpdf_file_path, $path );
		if ( file_exists( $finalpdf_file_path ) ) {
			include $finalpdf_file_path;
		} else {

			/* translators: %s: file path */
			$finalpdf_notice = sprintf( esc_html__( 'Unable to locate file at location %s. Some features may not work properly in this plugin. Please contact us!', 'finalpdf' ), $finalpdf_file_path );
			$this->wps_finalpdf_plug_admin_notice( $finalpdf_notice, 'error' );
		}
	}

	/**
	 * Show admin notices.
	 *
	 * @param  string $finalpdf_message    Message to display.
	 * @param  string $type       notice type, accepted values - error/update/update-nag.
	 * @since  1.0.0
	 */
	public static function wps_finalpdf_plug_admin_notice( $finalpdf_message, $type = 'error' ) {

		$finalpdf_classes = 'notice ';

		switch ( $type ) {

			case 'update':
				$finalpdf_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$finalpdf_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$finalpdf_classes .= 'notice-success is-dismissible';
				break;

			default:
				$finalpdf_classes .= 'notice-error is-dismissible';
				break;
		}

		$finalpdf_notice  = '<div class="' . esc_attr( $finalpdf_classes ) . ' wps-errorr-5">';
		$finalpdf_notice .= '<p>' . esc_html( $finalpdf_message ) . '</p>';
		$finalpdf_notice .= '</div>';

		echo wp_kses_post( $finalpdf_notice );
	}
	/**
	 * Generate html components.
	 *
	 * @param  string $finalpdf_components    html to display.
	 * @since  1.0.0
	 */
	public function wps_finalpdf_plug_generate_html( $finalpdf_components = array() ) {
		if ( is_array( $finalpdf_components ) && ! empty( $finalpdf_components ) ) {
			foreach ( $finalpdf_components as $finalpdf_component ) {
				if ( ! empty( $finalpdf_component['type'] ) && ! empty( $finalpdf_component['id'] ) ) {
					switch ( $finalpdf_component['type'] ) {
						case 'hidden':
						case 'number':
						case 'email':
						case 'text':
							?>
						<div class="wps-form-group wps-finalpdf-<?php echo esc_attr( $finalpdf_component['type'] . ' ' . $finalpdf_component['class'] . ' ' . ( isset( $finalpdf_component['parent-class'] ) ? $finalpdf_component['parent-class'] : '' ) ); ?>" style="<?php echo esc_attr( array_key_exists( 'style', $finalpdf_component ) ? $finalpdf_component['style'] : '' ); ?>">
							<div class="wps-form-group__label">
								<label for="<?php echo esc_attr( $finalpdf_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $finalpdf_component['title'] ) ? esc_html( $finalpdf_component['title'] ) : '' ); ?></label>
							</div>
							<div class="wps-form-group__control">
								<label class="mdc-text-field mdc-text-field--outlined">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<?php if ( 'number' !== $finalpdf_component['type'] ) { ?>
												<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $finalpdf_component['placeholder'] ) ? esc_attr( $finalpdf_component['placeholder'] ) : '' ); ?></span>
											<?php } ?>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input
									class="mdc-text-field__input <?php echo ( isset( $finalpdf_component['class'] ) ? esc_attr( $finalpdf_component['class'] ) : '' ); ?>" 
									name="<?php echo ( isset( $finalpdf_component['name'] ) ? esc_html( $finalpdf_component['name'] ) : esc_html( $finalpdf_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $finalpdf_component['id'] ); ?>"
									type="<?php echo esc_attr( $finalpdf_component['type'] ); ?>"
									value="<?php echo ( isset( $finalpdf_component['value'] ) ? esc_attr( $finalpdf_component['value'] ) : '' ); ?>"
									placeholder="<?php echo ( isset( $finalpdf_component['placeholder'] ) ? esc_attr( $finalpdf_component['placeholder'] ) : '' ); ?>"
									<?php echo ( 'number' === $finalpdf_component['type'] && isset( $finalpdf_component['min'] ) ) ? esc_html( 'min=' . $finalpdf_component['min'] ) : ''; ?>
									<?php echo ( 'number' === $finalpdf_component['type'] && isset( $finalpdf_component['max'] ) ) ? esc_html( 'max=' . $finalpdf_component['max'] ) : ''; ?>
									<?php echo isset( $finalpdf_component['step'] ) ? esc_html( 'step=' . $finalpdf_component['step'] ) : ''; ?>
									>
								</label>
								<div class="mdc-text-field-helper-line">
									<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $finalpdf_component['description'] ) ? esc_attr( $finalpdf_component['description'] ) : '' ); ?></div>
								</div>
							</div>
						</div>
							<?php
							break;
						case 'password':
							?>
						<div class="wps-form-group">
							<div class="wps-form-group__label">
								<label for="<?php echo esc_attr( $finalpdf_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $finalpdf_component['title'] ) ? esc_html( $finalpdf_component['title'] ) : '' ); ?></label>
							</div>
							<div class="wps-form-group__control">
								<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<input 
									class="mdc-text-field__input <?php echo ( isset( $finalpdf_component['class'] ) ? esc_attr( $finalpdf_component['class'] ) : '' ); ?> wps-form__password" 
									name="<?php echo ( isset( $finalpdf_component['name'] ) ? esc_html( $finalpdf_component['name'] ) : esc_html( $finalpdf_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $finalpdf_component['id'] ); ?>"
									type="<?php echo esc_attr( $finalpdf_component['type'] ); ?>"
									value="<?php echo ( isset( $finalpdf_component['value'] ) ? esc_attr( $finalpdf_component['value'] ) : '' ); ?>"
									placeholder="<?php echo ( isset( $finalpdf_component['placeholder'] ) ? esc_attr( $finalpdf_component['placeholder'] ) : '' ); ?>"
									>
									<i class="material-icons mdc-text-field__icon mdc-text-field__icon--trailing wps-password-hidden" tabindex="0" role="button">visibility</i>
								</label>
								<div class="mdc-text-field-helper-line">
									<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $finalpdf_component['description'] ) ? esc_attr( $finalpdf_component['description'] ) : '' ); ?></div>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'textarea':
							?>
						<div class="wps-form-group">
							<div class="wps-form-group__label">
								<label class="wps-form-label" for="<?php echo esc_attr( $finalpdf_component['id'] ); ?>"><?php echo ( isset( $finalpdf_component['title'] ) ? esc_html( $finalpdf_component['title'] ) : '' ); ?></label>
							</div>
							<div class="wps-form-group__control">
								<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea"  	for="text-field-hero-input">
									<span class="mdc-notched-outline">
										<span class="mdc-notched-outline__leading"></span>
										<span class="mdc-notched-outline__notch">
											<span class="mdc-floating-label"><?php echo ( isset( $finalpdf_component['placeholder'] ) ? esc_attr( $finalpdf_component['placeholder'] ) : '' ); ?></span>
										</span>
										<span class="mdc-notched-outline__trailing"></span>
									</span>
									<span class="mdc-text-field__resizer">
										<textarea class="mdc-text-field__input <?php echo ( isset( $finalpdf_component['class'] ) ? esc_attr( $finalpdf_component['class'] ) : '' ); ?>" rows="2" cols="25" aria-label="Label" name="<?php echo ( isset( $finalpdf_component['name'] ) ? esc_html( $finalpdf_component['name'] ) : esc_html( $finalpdf_component['id'] ) ); ?>" id="<?php echo esc_attr( $finalpdf_component['id'] ); ?>" placeholder="<?php echo ( isset( $finalpdf_component['placeholder'] ) ? esc_attr( $finalpdf_component['placeholder'] ) : '' ); ?>"><?php echo ( isset( $finalpdf_component['value'] ) ? esc_textarea( $finalpdf_component['value'] ) : '' ); ?></textarea>
									</span>
								</label>
								<br/>
								<label class="mdl-textfield__label" for="octane"><?php echo ( isset( $finalpdf_component['description'] ) ? esc_attr( $finalpdf_component['description'] ) : '' ); ?></label>
							</div>
						</div>

							<?php
							break;

						case 'select':
						case 'multiselect':
							?>
						<div class="wps-form-group <?php echo esc_attr( isset( $finalpdf_component['parent-class'] ) ? $finalpdf_component['parent-class'] : '' ); ?>">
							<div class="wps-form-group__label">
								<label class="wps-form-label" for="<?php echo esc_attr( $finalpdf_component['id'] ); ?>"><?php echo ( isset( $finalpdf_component['title'] ) ? esc_html( $finalpdf_component['title'] ) : '' ); ?></label>
							</div>
							<div class="wps-form-group__control">
								<div class="wps-form-select">
									<select id="<?php echo esc_attr( $finalpdf_component['id'] ); ?>" name="<?php echo ( isset( $finalpdf_component['name'] ) ? esc_html( $finalpdf_component['name'] ) : '' ); ?><?php echo ( 'multiselect' === $finalpdf_component['type'] ) ? '[]' : ''; ?>" id="<?php echo esc_attr( $finalpdf_component['id'] ); ?>" class="mdl-textfield__input <?php echo ( isset( $finalpdf_component['class'] ) ? esc_attr( $finalpdf_component['class'] ) : '' ); ?>" <?php echo 'multiselect' === $finalpdf_component['type'] ? 'multiple="multiple"' : ''; ?> >
										<?php
										foreach ( $finalpdf_component['options'] as $finalpdf_key => $finalpdf_val ) {
											?>
											<option value="<?php echo esc_attr( $finalpdf_key ); ?>"
												<?php
												if ( is_array( $finalpdf_component['value'] ) ) {
													selected( in_array( (string) $finalpdf_key, $finalpdf_component['value'], true ), true );
												} else {
													selected( $finalpdf_component['value'], (string) $finalpdf_key );
												}
												?>
												>
												<?php echo esc_html( $finalpdf_val ); ?>
											</option>
											<?php
										}
										?>
									</select>
									<label class="mdl-textfield__label" for="octane"><?php echo ( isset( $finalpdf_component['description'] ) ? esc_attr( $finalpdf_component['description'] ) : '' ); ?></label>
								</div>
							</div>
						</div>

							<?php
							break;

						case 'checkbox':
							?>
						<div class="wps-form-group <?php echo esc_attr( isset( $finalpdf_component['parent-class'] ) ? $finalpdf_component['parent-class'] : '' ); ?>">
							<div class="wps-form-group__label">
								<label for="<?php echo esc_attr( $finalpdf_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $finalpdf_component['title'] ) ? esc_html( $finalpdf_component['title'] ) : '' ); ?></label>
							</div>
							<div class="wps-form-group__control wps-pl-4">
								<div class="mdc-form-field">
									<div class="mdc-checkbox">
										<input 
										name="<?php echo ( isset( $finalpdf_component['name'] ) ? esc_html( $finalpdf_component['name'] ) : esc_html( $finalpdf_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $finalpdf_component['id'] ); ?>"
										type="checkbox"
										class="mdc-checkbox__native-control <?php echo ( isset( $finalpdf_component['class'] ) ? esc_attr( $finalpdf_component['class'] ) : '' ); ?>"
										value="yes"
										<?php checked( $finalpdf_component['value'], 'yes' ); ?>
										/>
										<div class="mdc-checkbox__background">
											<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
												<path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
											</svg>
											<div class="mdc-checkbox__mixedmark"></div>
										</div>
										<div class="mdc-checkbox__ripple"></div>
									</div>
									<label for="checkbox-1"><?php echo ( isset( $finalpdf_component['description'] ) ? esc_attr( $finalpdf_component['description'] ) : '' ); ?></label>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'radio':
							?>
						<div class="wps-form-group">
							<div class="wps-form-group__label">
								<label for="<?php echo esc_attr( $finalpdf_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $finalpdf_component['title'] ) ? esc_html( $finalpdf_component['title'] ) : '' ); ?></label>
							</div>
							<div class="wps-form-group__control wps-pl-4">
								<div class="wps-flex-col">
									<?php
									foreach ( $finalpdf_component['options'] as $finalpdf_radio_key => $finalpdf_radio_val ) {
										?>
										<div class="mdc-form-field">
											<div class="mdc-radio">
												<input
												name="<?php echo ( isset( $finalpdf_component['name'] ) ? esc_html( $finalpdf_component['name'] ) : esc_html( $finalpdf_component['id'] ) ); ?>"
												value="<?php echo esc_attr( $finalpdf_radio_key ); ?>"
												type="radio"
												class="mdc-radio__native-control <?php echo ( isset( $finalpdf_component['class'] ) ? esc_attr( $finalpdf_component['class'] ) : '' ); ?>"
												<?php checked( $finalpdf_radio_key, $finalpdf_component['value'] ); ?>
												>
												<div class="mdc-radio__background">
													<div class="mdc-radio__outer-circle"></div>
													<div class="mdc-radio__inner-circle"></div>
												</div>
												<div class="mdc-radio__ripple"></div>
											</div>
											<label for="radio-1"><?php echo esc_html( $finalpdf_radio_val ); ?></label>
										</div>	
										<?php
									}
									?>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'radio-switch':
							?>

						<div class="wps-form-group">
							<div class="wps-form-group__label">
								<label for="" class="wps-form-label"><?php echo ( isset( $finalpdf_component['title'] ) ? esc_html( $finalpdf_component['title'] ) : '' ); ?></label>
							</div>
							<div class="wps-form-group__control">
								<div>
									<div class="mdc-switch">
										<div class="mdc-switch__track"></div>
										<div class="mdc-switch__thumb-underlay">
											<div class="mdc-switch__thumb"></div>
											<input
											name="<?php echo ( isset( $finalpdf_component['name'] ) ? esc_html( $finalpdf_component['name'] ) : esc_html( $finalpdf_component['id'] ) ); ?>"
											type="checkbox"
											id="<?php echo esc_html( $finalpdf_component['id'] ); ?>"
											value="yes"
											class="mdc-switch__native-control <?php echo ( isset( $finalpdf_component['class'] ) ? esc_attr( $finalpdf_component['class'] ) : '' ); ?>"
											role="switch"
											aria-checked="<?php echo esc_html( 'yes' === $finalpdf_component['value'] ) ? 'true' : 'false'; ?>"
											<?php checked( $finalpdf_component['value'], 'yes' ); ?>
											>
										</div>
									</div>
								</div>
								<div class="mdc-text-field-helper-line">
									<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $finalpdf_component['description'] ) ? esc_attr( $finalpdf_component['description'] ) : '' ); ?></div>
								</div>
							</div>
						</div>
							<?php
							break;

						case 'button':
							?>
						<div class="wps-form-group">
							<div class="wps-form-group__label"></div>
							<div class="wps-form-group__control">
								<button type="submit" class="mdc-button mdc-button--raised" name= "<?php echo ( isset( $finalpdf_component['name'] ) ? esc_html( $finalpdf_component['name'] ) : esc_html( $finalpdf_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $finalpdf_component['id'] ); ?>"> <span class="mdc-button__ripple"></span>
									<span class="mdc-button__label <?php echo ( isset( $finalpdf_component['class'] ) ? esc_attr( $finalpdf_component['class'] ) : '' ); ?>"><?php echo ( isset( $finalpdf_component['button_text'] ) ? esc_html( $finalpdf_component['button_text'] ) : '' ); ?></span>
								</button>
							</div>
						</div>
							<?php
							break;
						case 'reset-button':
							?>
						<div class="wps-form-group">
							<div class="wps-form-group__label">
								<label for="<?php echo esc_attr( $finalpdf_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $finalpdf_component['title'] ) ? esc_html( $finalpdf_component['title'] ) : '' ); ?></label>
							</div>
							<div class="wps-form-group__control">
								<button type="submit" class="<?php echo esc_attr( $finalpdf_component['class'] ); ?>" name= "<?php echo ( isset( $finalpdf_component['name'] ) ? esc_html( $finalpdf_component['name'] ) : esc_html( $finalpdf_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $finalpdf_component['id'] ); ?>"> <span class="mdc-button__ripple"></span>
									<span class="mdc-button__label <?php echo ( isset( $finalpdf_component['class'] ) ? esc_attr( $finalpdf_component['class'] ) : '' ); ?>"><?php echo ( isset( $finalpdf_component['button_text'] ) ? esc_html( $finalpdf_component['button_text'] ) : '' ); ?></span>
								</button>
								<span id="<?php echo ( isset( $finalpdf_component['loader-id'] ) ? esc_attr( $finalpdf_component['loader-id'] ) : '' ); ?>" ></span>
								<div class="mdc-text-field-helper-line">
									<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $finalpdf_component['description'] ) ? esc_attr( $finalpdf_component['description'] ) : '' ); ?></div>
								</div>
							</div>
						</div>

							<?php
							break;

						case 'multi':
							?>
							<div class="wps-form-group wps-isfw-<?php echo esc_attr( $finalpdf_component['type'] ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $finalpdf_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $finalpdf_component['title'] ) ? esc_html( $finalpdf_component['title'] ) : '' ); ?></label>
									</div>
									<div class="wps-form-group__control">
									<?php
									foreach ( $finalpdf_component['value'] as $component ) {
										if ( 'color' !== $component['type'] ) {
											?>
											<label class="mdc-text-field mdc-text-field--outlined">
												<span class="mdc-notched-outline">
													<span class="mdc-notched-outline__leading"></span>
													<span class="mdc-notched-outline__notch">
														<?php if ( 'number' !== $component['type'] ) { ?>
															<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $component['placeholder'] ) ? esc_attr( $component['placeholder'] ) : '' ); ?></span>
														<?php } ?>
													</span>
													<span class="mdc-notched-outline__trailing"></span>
												</span>
												<?php } ?>
												<input 
												class="mdc-text-field__input <?php echo ( isset( $component['class'] ) ? esc_attr( $component['class'] ) : '' ); ?>" 
												name="<?php echo ( isset( $component['name'] ) ? esc_html( $component['name'] ) : esc_html( $component['id'] ) ); ?>"
												id="<?php echo esc_attr( $component['id'] ); ?>"
												type="<?php echo esc_attr( 'color' === $component['type'] ) ? 'text' : esc_html( $component['type'] ); ?>"
												value="<?php echo ( isset( $component['value'] ) ? esc_attr( $component['value'] ) : '' ); ?>"
												placeholder="<?php echo ( isset( $component['placeholder'] ) ? esc_attr( $component['placeholder'] ) : '' ); ?>"
												<?php echo esc_attr( ( 'number' === $component['type'] ) ? 'min=' . $component['min'] . ' max=' . $component['max'] : '' ); ?>
												>
												<?php if ( 'color' !== $component['type'] ) { ?>
											</label>
											<?php } ?>
								<?php } ?>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $finalpdf_component['description'] ) ? esc_attr( $finalpdf_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
								<?php
							break;
						case 'multiwithcheck':
							?>
							<div class="wps-form-group wps-isfw-<?php echo esc_attr( $finalpdf_component['type'] ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $finalpdf_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $finalpdf_component['title'] ) ? esc_html( $finalpdf_component['title'] ) : '' ); ?></label>
									</div>
									<div class="wps-form-group__control">
									<?php
									foreach ( $finalpdf_component['value'] as $component ) {
										if ( 'color' !== $component['type'] ) {
											?>
											<label class="mdc-text-field mdc-text-field--outlined">
												<span class="mdc-notched-outline">
													<span class="mdc-notched-outline__leading"></span>
													<span class="mdc-notched-outline__notch">
														<?php if ( 'number' !== $component['type'] ) { ?>
															<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $component['placeholder'] ) ? esc_attr( $component['placeholder'] ) : '' ); ?></span>
														<?php } ?>
													</span>
													<span class="mdc-notched-outline__trailing"></span>
												</span>
												<?php } ?>
												<input type="checkbox" class="wpg-multi-checkbox" name="<?php echo ( isset( $component['checkbox_name'] ) ? esc_attr( $component['checkbox_name'] ) : '' ); ?>" id="<?php echo ( isset( $component['checkbox_id'] ) ? esc_attr( $component['checkbox_id'] ) : '' ); ?>" <?php checked( ( isset( $component['checkbox_value'] ) ? $component['checkbox_value'] : '' ), 'yes' ); ?> value="yes">
												<input 
												class="mdc-text-field__input <?php echo ( isset( $component['class'] ) ? esc_attr( $component['class'] ) : '' ); ?>" 
												name="<?php echo ( isset( $component['name'] ) ? esc_html( $component['name'] ) : esc_html( $component['id'] ) ); ?>"
												id="<?php echo esc_attr( $component['id'] ); ?>"
												type="<?php echo esc_attr( 'color' === $component['type'] ) ? 'text' : esc_html( $component['type'] ); ?>"
												value="<?php echo ( isset( $component['value'] ) ? esc_attr( $component['value'] ) : '' ); ?>"
												placeholder="<?php echo ( isset( $component['placeholder'] ) ? esc_attr( $component['placeholder'] ) : '' ); ?>"
												<?php echo esc_attr( ( 'number' === $component['type'] ) ? 'min=' . $component['min'] . ' max=' . $component['max'] : '' ); ?>
												>
												<?php if ( 'color' !== $component['type'] ) { ?>
											</label>
											<?php } ?>
								<?php } ?>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $finalpdf_component['description'] ) ? esc_attr( $finalpdf_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
								<?php
							break;
						case 'color':
						case 'date':
						case 'file':
							?>
							<div class="wps-form-group wps-isfw-<?php echo esc_attr( $finalpdf_component['type'] ); ?> <?php echo esc_attr( isset( $finalpdf_component['parent-class'] ) ? $finalpdf_component['parent-class'] : '' ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $finalpdf_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $finalpdf_component['title'] ) ? esc_html( $finalpdf_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<input 
									class="<?php echo ( isset( $finalpdf_component['class'] ) ? esc_attr( $finalpdf_component['class'] ) : '' ); ?>" 
									name="<?php echo ( isset( $finalpdf_component['name'] ) ? esc_html( $finalpdf_component['name'] ) : esc_html( $finalpdf_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $finalpdf_component['id'] ); ?>"
									type="<?php echo esc_attr( ( 'color' === $finalpdf_component['type'] ) ? 'text' : $finalpdf_component['type'] ); ?>"
									value="<?php echo ( isset( $finalpdf_component['value'] ) ? esc_attr( $finalpdf_component['value'] ) : '' ); ?>"
									<?php echo esc_html( ( 'date' === $finalpdf_component['type'] ) ? 'max=' . gmdate( 'Y-m-d', strtotime( gmdate( 'Y-m-d', mktime() ) . ' + 365 day' ) ) . ' min=' . gmdate( 'Y-m-d' ) . '' : '' ); ?>
									>
									<?php if ( 'file' === $finalpdf_component['type'] ) { ?>
									<span><?php echo esc_attr( $finalpdf_component['value'] ); ?></span>
									<?php } ?>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $finalpdf_component['description'] ) ? esc_attr( $finalpdf_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;
						case 'temp-select':
							?>
								<div class="wps-form-group wps_finalpdf_pro_tag wps-wpg-<?php echo esc_attr( array_key_exists( 'type', $finalpdf_component ) ? $finalpdf_component['type'] : '' ); ?>">
									<div class="wps-form-group__label">
										<label for="<?php echo esc_attr( array_key_exists( 'id', $finalpdf_component ) ? $finalpdf_component['id'] : '' ); ?>" class="wps-form-label"><?php echo esc_html( array_key_exists( 'title', $finalpdf_component ) ? $finalpdf_component['title'] : '' ); ?></label>
									</div>
									<div class="wps-form-group__control">
									<?php
									foreach ( $finalpdf_component['value'] as $finalpdf_subcomponent ) {
										?>
											<img src="<?php echo ( isset( $finalpdf_subcomponent['src'] ) ? esc_attr( $finalpdf_subcomponent['src'] ) : '' ); ?>" width="100" height="100" alt="">
											<input 
											class="<?php echo esc_attr( array_key_exists( 'class', $finalpdf_subcomponent ) ? $finalpdf_subcomponent['class'] : '' ); ?>" 
											name="<?php echo esc_attr( array_key_exists( 'name', $finalpdf_subcomponent ) ? $finalpdf_subcomponent['name'] : '' ); ?>"
											id="<?php echo esc_attr( array_key_exists( 'id', $finalpdf_subcomponent ) ? $finalpdf_subcomponent['id'] : '' ); ?>"
											type="<?php echo esc_attr( array_key_exists( 'type', $finalpdf_subcomponent ) ? $finalpdf_subcomponent['type'] : '' ); ?>"
											value="<?php echo esc_attr( array_key_exists( 'value', $finalpdf_subcomponent ) ? $finalpdf_subcomponent['value'] : '' ); ?>"
											<?php checked( $finalpdf_component['selected'], $finalpdf_subcomponent['value'] ); ?>
											>
										<?php } ?>
										<div class="mdc-text-field-helper-line">
											<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo wp_kses_post( array_key_exists( 'description', $finalpdf_component ) ? $finalpdf_component['description'] : '' ); ?></div>
										</div>
									</div>
								</div>
									<?php
							break;
						case 'submit':
							?>
						<tr valign="top">
							<td scope="row">
								<input type="submit" class="button button-primary" 
								name="<?php echo ( isset( $finalpdf_component['name'] ) ? esc_html( $finalpdf_component['name'] ) : esc_html( $finalpdf_component['id'] ) ); ?>"
								id="<?php echo esc_attr( $finalpdf_component['id'] ); ?>"
								class="<?php echo ( isset( $finalpdf_component['class'] ) ? esc_attr( $finalpdf_component['class'] ) : '' ); ?>"
								value="<?php echo esc_attr( $finalpdf_component['button_text'] ); ?>"
								/>
							</td>
						</tr>
							<?php
							break;
						case 'upload-button':
							?>
								<div class="wps-form-group <?php echo esc_attr( isset( $finalpdf_component['parent-class'] ) ? $finalpdf_component['parent-class'] : '' ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( array_key_exists( 'id', $finalpdf_component ) ? $finalpdf_component['id'] : '' ); ?>" class="wps-form-label"><?php echo esc_html( array_key_exists( 'title', $finalpdf_component ) ? $finalpdf_component['title'] : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<input
									type="hidden"
									id="<?php echo esc_attr( array_key_exists( 'id', $finalpdf_component ) ? $finalpdf_component['id'] : '' ); ?>"
									class="<?php echo esc_attr( array_key_exists( 'class', $finalpdf_component ) ? $finalpdf_component['class'] : '' ); ?>"
									name="<?php echo esc_attr( array_key_exists( 'name', $finalpdf_component ) ? $finalpdf_component['name'] : '' ); ?>"
									value="<?php echo esc_html( array_key_exists( 'value', $finalpdf_component ) ? $finalpdf_component['value'] : '' ); ?>"
									>
									<img
										src="<?php echo esc_attr( $finalpdf_component['img-tag']['img-src'] ); ?>"
										class="<?php echo esc_attr( $finalpdf_component['img-tag']['img-class'] ); ?>"
										id="<?php echo esc_attr( $finalpdf_component['img-tag']['img-id'] ); ?>"
										style="<?php echo esc_attr( $finalpdf_component['img-tag']['img-style'] ); ?>"
									>
									<button class="mdc-button--raised" name="<?php echo esc_attr( array_key_exists( 'sub_name', $finalpdf_component ) ? $finalpdf_component['sub_name'] : '' ); ?>"
										id="<?php echo esc_attr( array_key_exists( 'sub_id', $finalpdf_component ) ? $finalpdf_component['sub_id'] : '' ); ?>"> <span class="mdc-button__ripple"></span>
										<span class="mdc-button__label"><?php echo esc_attr( array_key_exists( 'button_text', $finalpdf_component ) ? $finalpdf_component['button_text'] : '' ); ?></span>
									</button>
									<button class="mdc-button--raised" name="<?php echo esc_attr( $finalpdf_component['img-remove']['btn-name'] ); ?>"
										id="<?php echo esc_attr( $finalpdf_component['img-remove']['btn-id'] ); ?>"
										style="<?php echo esc_attr( $finalpdf_component['img-remove']['btn-style'] ); ?>"
										> <span class="mdc-button__ripple"
										></span>
										<span class="mdc-button__label"><?php echo esc_attr( $finalpdf_component['img-remove']['btn-title'] ); ?></span>
									</button>
									<input
									type="hidden"
									id="<?php echo ( isset( $finalpdf_component['img-hidden'] ) ) ? esc_attr( $finalpdf_component['img-hidden']['id'] ) : ''; ?>"
									class="<?php echo ( isset( $finalpdf_component['img-hidden'] ) ) ? esc_attr( $finalpdf_component['img-hidden']['class'] ) : ''; ?>"
									name="<?php echo ( isset( $finalpdf_component['img-hidden'] ) ) ? esc_attr( $finalpdf_component['img-hidden']['name'] ) : ''; ?>"
									>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $finalpdf_component['description'] ) ? esc_attr( $finalpdf_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
								<?php
							break;
						case 'sub-text':
							?>
						<div class="sub-text-parent-class">
							<div class="wps-form-group wps-finalpdf-<?php echo esc_attr( $finalpdf_component['type'] . ' ' . $finalpdf_component['class'] . ' ' . ( isset( $finalpdf_component['parent-class'] ) ? $finalpdf_component['parent-class'] : '' ) ); ?>" style="<?php echo esc_attr( array_key_exists( 'style', $finalpdf_component ) ? $finalpdf_component['style'] : '' ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $finalpdf_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $finalpdf_component['title'] ) ? esc_html( $finalpdf_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
												<?php if ( 'number' !== $finalpdf_component['type'] ) { ?>
													<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $finalpdf_component['placeholder'] ) ? esc_attr( $finalpdf_component['placeholder'] ) : '' ); ?></span>
												<?php } ?>
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<input
										class="mdc-text-field__input <?php echo ( isset( $finalpdf_component['class'] ) ? esc_attr( $finalpdf_component['class'] ) : '' ); ?>" 
										name="<?php echo ( isset( $finalpdf_component['name'] ) ? esc_html( $finalpdf_component['name'] ) : esc_html( $finalpdf_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $finalpdf_component['id'] ); ?>"
										type="<?php echo esc_attr( $finalpdf_component['type'] ); ?>"
										value="<?php echo ( isset( $finalpdf_component['value'] ) ? esc_attr( $finalpdf_component['value'] ) : '' ); ?>"
										placeholder="<?php echo ( isset( $finalpdf_component['placeholder'] ) ? esc_attr( $finalpdf_component['placeholder'] ) : '' ); ?>"
										<?php echo ( 'number' === $finalpdf_component['type'] && isset( $finalpdf_component['min'] ) ) ? esc_html( 'min=' . $finalpdf_component['min'] ) : ''; ?>
										<?php echo ( 'number' === $finalpdf_component['type'] && isset( $finalpdf_component['max'] ) ) ? esc_html( 'max=' . $finalpdf_component['max'] ) : ''; ?>
										<?php echo isset( $finalpdf_component['step'] ) ? esc_html( 'step=' . $finalpdf_component['step'] ) : ''; ?>
										>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $finalpdf_component['description'] ) ? esc_attr( $finalpdf_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
						</div>
							<?php
							break;
						case 'date-picker':
							?>
								<div class="wps-form-group">
									<div class="wps-form-group__label">
										<label class="wps-form-label" for="<?php echo esc_attr( array_key_exists( 'id', $finalpdf_component ) ? $finalpdf_component['id'] : '' ); ?>"><?php echo esc_attr( array_key_exists( 'title', $finalpdf_component ) ? $finalpdf_component['title'] : '' ); ?></label>
									</div>
									<div class="wps-form-group__control">
								 <?php
									$sub_pgfw_component_value = $finalpdf_component['value'];
									?>
										<div class="wps-wpg-date-picker-group">
											<span class="wps-wpg-month-selector"><?php echo esc_attr( $sub_pgfw_component_value['month']['title'] ); ?></span>
											<select name="<?php echo esc_attr( $sub_pgfw_component_value['month']['name'] ); ?>" id="<?php echo esc_attr( $sub_pgfw_component_value['month']['id'] ); ?>" class="<?php echo esc_attr( $sub_pgfw_component_value['month']['class'] ); ?>">
										 <?php
											$month_options = $sub_pgfw_component_value['month']['options'];
											foreach ( $month_options as $key => $value ) {
												?>
													<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $sub_pgfw_component_value['month']['value'], $key ); ?>><?php echo esc_attr( $value ); ?></option>
													<?php
											}
											?>
											</select>
											<span class="wps-wpg-date-selector"><?php echo esc_attr( $sub_pgfw_component_value['date']['title'] ); ?></span>
											<select name="<?php echo esc_attr( $sub_pgfw_component_value['date']['name'] ); ?>" id="<?php echo esc_attr( $sub_pgfw_component_value['date']['id'] ); ?>" class="<?php echo esc_attr( $sub_pgfw_component_value['date']['class'] ); ?>">
												<?php
												$date_options = $sub_pgfw_component_value['date']['options'];
												foreach ( $date_options as $key => $value ) {
													?>
													<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $sub_pgfw_component_value['date']['value'], $key ); ?>><?php echo esc_attr( $value ); ?></option>
													<?php
												}
												?>
											</select>
										</div>
									</div>
								</div>
								<?php
							break;
						default:
							break;
					}
				}
			}
		}
	}
}
