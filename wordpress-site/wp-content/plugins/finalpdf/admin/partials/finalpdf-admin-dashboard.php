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

global $finalpdf_obj, $wps_finalpdf_gen_flag, $finalpdf_save_check_flag;
$finalpdf_active_tab   = isset( $_GET['finalpdf_tab'] ) ? sanitize_key( $_GET['finalpdf_tab'] ) : 'finalpdf-general'; // phpcs:ignore
do_action( 'finalpdf_license_activation_notice_on_dashboard' );
$finalpdf_default_tabs = $finalpdf_obj->wps_finalpdf_plug_default_tabs();

$wps_finalpdf_plugin_list = get_option( 'active_plugins' );
$wps_finalpdf_plugin = 'finalpdf/finalpdf.php';
$wps_finalpdf_is_pro_active = false;
if ( in_array( $wps_finalpdf_plugin, $wps_finalpdf_plugin_list ) ) {
	$wps_finalpdf_is_pro_active = true;
}
?>
<header>
	<?php
	// desc - This hook is used for trial.
	do_action( 'wps_finalpdf_settings_saved_notice' );
	?>
	<div class="wps-header-container wps-bg-white wps-r-8">
		<h1 class="wps-header-title"><?php echo esc_attr( strtoupper( str_replace( '-', ' ', apply_filters( 'wps_finalpdf_update_plugin_name_dashboard', $finalpdf_obj->finalpdf_get_plugin_name() ) ) ) ); ?></h1>
		<?php if ( ! $wps_finalpdf_is_pro_active ) { ?>
		<a href="https://yourwebsite.com/documentation/?utm_source=wpswings-pdf-docs&utm_medium=wpswings-org-backend&utm_campaign=documentation" target="_blank" class="wps-link"><?php esc_html_e( 'Documentation', 'finalpdf' ); ?></a>
		<?php } else { ?>
		<a href="https://yourwebsite.com/documentation/?utm_source=wpswings-pdf-docs&utm_medium=wpswings-org-backend&utm_campaign=documentation" target="_blank" class="wps-link"><?php esc_html_e( 'Documentation', 'finalpdf' ); ?></a>	
		<?php } ?>
		<span>|</span>
		<a href="https://yourwebsite.com/submit-query/?utm_source=wpswings-pdf-support&utm_medium=pdf-org-backend&utm_campaign=submit-query" target="_blank" class="wps-link"><?php esc_html_e( 'Support', 'finalpdf' ); ?></a>
	</div>
</header>
<?php
if ( $finalpdf_save_check_flag ) {
	if ( ! $wps_finalpdf_gen_flag ) {
		$wps_finalpdf_error_text = esc_html__( 'Settings saved successfully !', 'finalpdf' );
		$finalpdf_obj->wps_finalpdf_plug_admin_notice( $wps_finalpdf_error_text, 'success' );
	} elseif ( $wps_finalpdf_gen_flag ) {
		$wps_finalpdf_error_text = esc_html__( 'There might be some error, Please reload the page and try again.', 'finalpdf' );
		$finalpdf_obj->wps_finalpdf_plug_admin_notice( $wps_finalpdf_error_text, 'error' );
	}
}
?>
<div class="wps-pdf__popup-for-pro-wrap">
	<div class="wps-pdf__popup-for-pro-shadow"></div>
	<div class="wps-pdf__popup-for-pro">
		<span class="wps-pdf__popup-for-pro-close">+</span>
		<h2 class="wps-pdf__popup-for-pro-title">Want More ? <b>Go Pro !</b></h2>
		<p class="wps-pdf__popup-for-pro-content"><i>This will offer features like WhatsApp sharing, Multiple Customization options, Creation of Custom Templates, Downloading in Bulk, and Many more exciting features.</i></p>
		<div class="wps-pdf__popup-for-pro-link-wrap">
			<a target="_blank" href="https://yourwebsite.com/product/finalpdf-pro/?utm_source=wpswings-pdf-pro&utm_medium=pdf-org-backend&utm_campaign=go-pro" class="wps-pdf__popup-for-pro-link">Go pro now</a>
		</div>
	</div>
</div>
<main class="wps-main wps-bg-white wps-r-8">
	<nav class="wps-navbar">
		<ul class="wps-navbar__items">
			<?php
			if ( is_array( $finalpdf_default_tabs ) && ! empty( $finalpdf_default_tabs ) ) {

				foreach ( $finalpdf_default_tabs as $finalpdf_tab_key => $finalpdf_default_tabs ) {

					$finalpdf_tab_classes = 'wps-link ';

					$finalpdf_tab_classe_pro = '';
					if ( ! empty( $finalpdf_active_tab ) && $finalpdf_active_tab === $finalpdf_tab_key ) {
						$finalpdf_tab_classes .= 'active';
					} elseif ( ! empty( $finalpdf_active_tab ) && in_array( $finalpdf_active_tab, array( 'finalpdf-header', 'finalpdf-body', 'finalpdf-footer', 'finalpdf-pdf-icon-setting' ), true ) ) {
						if ( 'finalpdf-pdf-setting' === $finalpdf_tab_key ) {
							$finalpdf_tab_classes .= 'active';
						}
					} elseif ( ! empty( $finalpdf_active_tab ) && in_array( $finalpdf_active_tab, array( 'finalpdf-cover-page-setting', 'finalpdf-internal-page-setting' ), true ) ) {
						if ( 'finalpdf-layout-settings' === $finalpdf_tab_key ) {
							$finalpdf_tab_classes .= 'active';

						}
					}
					if ( 'Taxonomy Settings' == $finalpdf_default_tabs['title'] || 'Layout Settings' == $finalpdf_default_tabs['title'] || 'PDF Logs' == $finalpdf_default_tabs['title'] || 'Invoice settings' == $finalpdf_default_tabs['title'] || 'Invoice page settings' == $finalpdf_default_tabs['title'] ) {
						if ( ! $wps_finalpdf_is_pro_active ) {
							$finalpdf_tab_classe_pro .= 'wps_finalpdf_pro_tag_lable_main';
						}
					}
					?>
					<li class="<?php echo esc_attr( $finalpdf_tab_classe_pro ); ?>">
						<a id="<?php echo esc_attr( $finalpdf_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=finalpdf_menu' ) . '&finalpdf_tab=' . esc_attr( $finalpdf_tab_key ) ); ?>" class="<?php echo esc_attr( $finalpdf_tab_classes ); ?>"><?php echo esc_html( $finalpdf_default_tabs['title'] ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>
	<?php
		$plugin_admin = new FinalPDF_Admin( 'finalpdf', '1.0.7' );
		$count        = $plugin_admin->wps_finalpdf_get_count( 'settings' );
		$key3 = get_option( 'wps_finalpdf_activated_timestamp' );
	if ( ! empty( $count ) && ( empty( $key3 ) ) ) {
			$global_custom_css = 'const triggerError = () => {
				swal({
					title: "Attention Required!",
					text: "Please Migrate Your Database keys first by click on the below button then you can access the dashboard page.",
					icon: "error",
					button: "Click to Import",
					closeOnClickOutside: false,
				}).then(function() {
					jQuery( ".treat-button" ).click();
				});
			}
			triggerError();';
			wp_register_script( 'wps_finalpdf_incompatible_css', false, array(), $this->version, 'all' );
			wp_enqueue_script( 'wps_finalpdf_incompatible_css' );
			wp_add_inline_script( 'wps_finalpdf_incompatible_css', $global_custom_css );
	}
	?>
	<section class="wps-section">
		<div>
			<?php
			do_action( 'wps_finalpdf_before_general_settings_form' );
			// if submenu is directly clicked on woocommerce.
			if ( empty( $finalpdf_active_tab ) ) {
				$finalpdf_active_tab = 'wps_finalpdf_plug_general';
			}

			// look for the path based on the tab id in the admin templates.
			$finalpdf_tab_content_path = 'admin/partials/' . $finalpdf_active_tab . '.php';

			$finalpdf_obj->wps_finalpdf_plug_load_template( $finalpdf_tab_content_path );

			do_action( 'wps_finalpdf_after_general_settings_form' );
			?>
		</div>
		</section>
