<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://finaldoc.io/
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
$finalpdf_default_tabs = $finalpdf_obj->wps_finalpdf_plug_default_tabs();
?>
<header>
        <?php
        // desc - This hook is used for trial.
        do_action( 'wps_finalpdf_settings_saved_notice' );
        ?>
        <div class="wps-header-container wps-bg-white wps-r-8">
                <h1 class="wps-header-title"><?php echo esc_attr( strtoupper( str_replace( '-', ' ', apply_filters( 'wps_finalpdf_update_plugin_name_dashboard', $finalpdf_obj->finalpdf_get_plugin_name() ) ) ) ); ?></h1>
                <a href="https://finaldoc.io/finalpdf/documentation/" target="_blank" class="wps-link"><?php esc_html_e( 'Documentation', 'finalpdf' ); ?></a>
                <span>|</span>
                <a href="https://finaldoc.io/support/" target="_blank" class="wps-link"><?php esc_html_e( 'Support', 'finalpdf' ); ?></a>
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
<main class="wps-main wps-bg-white wps-r-8">
        <nav class="wps-navbar">
                <ul class="wps-navbar__items">
                        <?php
                        if ( is_array( $finalpdf_default_tabs ) && ! empty( $finalpdf_default_tabs ) ) {

                                foreach ( $finalpdf_default_tabs as $finalpdf_tab_key => $finalpdf_default_tabs ) {

                                        $finalpdf_tab_classes = 'wps-link ';

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
        <?php
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
