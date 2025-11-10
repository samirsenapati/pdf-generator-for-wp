<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           FinalPDF
 *
 * @wordpress-plugin
 * Plugin Name:       FinalPDF
 * Plugin URI:        https://finaldoc.io/finalpdf/
 * Description:       FinalPDF allows you to generate and download PDF files from WordPress sites with automatic Table of Contents generation. Perfect for knowledge bases, documentation, and eCommerce stores.
 * Version:           2.0.6
 * Author:            FinalDoc
 * Author URI:        https://finaldoc.io/
 * Text Domain:       finalpdf
 * Domain Path:       /languages
 *
 * Requires at least:    5.5.0
 * Tested up to:         6.8.0
 * WC requires at least: 5.2.0
 * WC tested up to:      9.8.1
 * Stable tag:           2.0.6
 * Requires PHP:         7.4
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
        die;
}
use Automattic\WooCommerce\Utilities\OrderUtil;
// HPOS Compatibility.
add_action(
        'before_woocommerce_init',
        function() {
                if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
                        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
                }
        }
);
require_once ABSPATH . '/wp-admin/includes/plugin.php';
$finalpdf_old_plugin_exists = false;
$plug           = get_plugins();
if ( isset( $plug['finalpdf/finalpdf.php'] ) ) {
        if ( version_compare( $plug['finalpdf/finalpdf.php']['Version'], '3.0.5', '<' ) ) {
                $finalpdf_old_plugin_exists = true;
        }
}

/**
 * Define plugin constants.
 *
 * @since 1.0.0
 */
function define_finalpdf_constants() {
        finalpdf_constants( 'FINALPDF_VERSION', '2.0.6' );
        finalpdf_constants( 'FINALPDF_DIR_PATH', plugin_dir_path( __FILE__ ) );
        finalpdf_constants( 'FINALPDF_DIR_URL', plugin_dir_url( __FILE__ ) );
        finalpdf_constants( 'FINALPDF_SERVER_URL', 'https://finaldoc.io' );
        finalpdf_constants( 'FINALPDF_ITEM_REFERENCE', 'FinalPDF' );
}

/**
 * Callable function for defining plugin constants.
 *
 * @param   String $key    Key for contant.
 * @param   String $value   value for contant.
 * @since   1.0.0
 */
function finalpdf_constants( $key, $value ) {

        if ( ! defined( $key ) ) {

                define( $key, $value );
        }
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-finalpdf-activator.php
 *
 * @param boolean $network_wide if activated network wide.
 * @since 1.0.3
 * @return void
 */
function activate_finalpdf( $network_wide ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-finalpdf-activator.php';
        FinalPDF_Activator::finalpdf_activate( $network_wide );
        $wps_finalpdf_active_plugin                         = get_option( 'wps_all_plugins_active', array() );
        $wps_finalpdf_active_plugin['finalpdf'] = array(
                'plugin_name' => __( 'FinalPDF For WordPress', 'finalpdf' ),
                'active'      => '1',
        );
        update_option( 'wps_all_plugins_active', $wps_finalpdf_active_plugin );
}

/**
 * Update default values when new site is created.
 *
 * @param object $new_site current blog object.
 * @since 1.0.3
 * @return void
 */
function wps_finalpdf_new_site_created_options( $new_site ) {
        if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
                require_once ABSPATH . '/wp-admin/includes/plugin.php';
        }
        if ( is_plugin_active_for_network( 'finalpdf/finalpdf.php' ) ) {
                $blog_id = $new_site->blog_id;
                switch_to_blog( $blog_id );
                require_once plugin_dir_path( __FILE__ ) . 'includes/class-finalpdf-activator.php';
                FinalPDF_Activator::finalpdf_updating_default_settings_indb();
                $wps_finalpdf_active_plugin                         = get_option( 'wps_all_plugins_active', array() );
                $wps_finalpdf_active_plugin['finalpdf'] = array(
                        'plugin_name' => __( 'FinalPDF For WordPress', 'finalpdf' ),
                        'active'      => '1',
                );
                update_option( 'wps_all_plugins_active', $wps_finalpdf_active_plugin );
                restore_current_blog();
        }

}

add_action( 'wp_initialize_site', 'wps_finalpdf_new_site_created_options', 900 );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-finalpdf-deactivator.php
 */
function deactivate_finalpdf() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-finalpdf-deactivator.php';
        FinalPDF_Deactivator::finalpdf_deactivate();
        $wps_finalpdf_deactive_plugin = get_option( 'wps_all_plugins_active', false );
        if ( is_array( $wps_finalpdf_deactive_plugin ) && ! empty( $wps_finalpdf_deactive_plugin ) ) {
                foreach ( $wps_finalpdf_deactive_plugin as $wps_finalpdf_deactive_key => $wps_finalpdf_deactive ) {
                        if ( 'finalpdf' === $wps_finalpdf_deactive_key ) {
                                $wps_finalpdf_deactive_plugin[ $wps_finalpdf_deactive_key ]['active'] = '0';
                        }
                }
        }

        update_option( 'wps_all_plugins_active', $wps_finalpdf_deactive_plugin );
        wp_clear_scheduled_hook( 'wps_finalpdf_check_for_notification_update' );
}

register_activation_hook( __FILE__, 'activate_finalpdf' );
register_deactivation_hook( __FILE__, 'deactivate_finalpdf' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-finalpdf.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_finalpdf() {
        define_finalpdf_constants();
        $finalpdf_plugin_standard = new FinalPDF();
        $finalpdf_plugin_standard->finalpdf_run();
        $GLOBALS['finalpdf_obj'] = $finalpdf_plugin_standard;
        require_once FINALPDF_DIR_PATH . 'includes/finalpdf-global-functions.php';
        require_once FINALPDF_DIR_PATH . 'includes/finalpdf-toc-generator.php';
}
run_finalpdf();


// Add settings link on plugin page.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'finalpdf_settings_link' );

/**
 * Settings link.
 *
 * @since 1.0.0
 * @param array $links    Settings link array.
 */
function finalpdf_settings_link( $links ) {

        $my_link = array(
                '<a href="' . admin_url( 'admin.php?page=finalpdf_menu' ) . '">' . __( 'Settings', 'finalpdf' ) . '</a>',
        );
        return array_merge( $my_link, $links );
}
/**
 * Adding custom setting links at the plugin activation list.
 *
 * @param array  $links_array array containing the links to plugin.
 * @param string $plugin_file_name plugin file name.
 * @return array
 */
function finalpdf_custom_settings_at_plugin_tab( $links_array, $plugin_file_name ) {
        if ( strpos( $plugin_file_name, basename( __FILE__ ) ) ) {
                $links_array[] = '<a href="https://finaldoc.io/finalpdf/documentation/" target="_blank"><img src="' . esc_html( FINALPDF_DIR_URL ) . 'admin/src/images/Documentation.svg" class="wps-info-img" alt="documentation image" style="width: 20px;height: 20px;padding-right:2px;">' . __( 'Documentation', 'finalpdf' ) . '</a>';
                $links_array[] = '<a href="https://finaldoc.io/support/" target="_blank"><img src="' . esc_html( FINALPDF_DIR_URL ) . 'admin/src/images/Support.svg" class="wps-info-img" alt="support image" style="width: 20px;height: 20px;padding-right:2px;">' . __( 'Support', 'finalpdf' ) . '</a>';
        }
        return $links_array;
}
add_filter( 'plugin_row_meta', 'finalpdf_custom_settings_at_plugin_tab', 10, 2 );

/**
 * Description: Embeds a Calendly Meeting.
 *
 * @param array $atts post id to print PDF for.
 */
function wps_calendly_embed_shortcode( $atts ) {
        $atts = shortcode_atts(
                array(
                        'url' => '',
                ),
                $atts,
                'wps_calendly'
        );

        return '<iframe src="' . esc_url( $atts['url'] ) . '" width="100%" height="600px" style="border: none;"></iframe>';
}

/**
 * Adding shortcode to show calendly Events anywhere on the page.
 */
if('on' === get_option( 'wps_embed_source_calendly' , '')){
add_shortcode( 'wps_calendly', 'wps_calendly_embed_shortcode' );
}
if('on' === get_option( 'wps_embed_source_twitch' , '')){
add_shortcode( 'wps_twitch', 'wps_twitch_stream_with_chat_shortcode' );
}

if('on' === get_option( 'wps_embed_source_strava' , '')){
add_shortcode( 'wps_strava', 'wps_strava_embed_shortcode' );
}

if('on' === get_option( 'wps_embed_source_ai_chatbot' , '')){
add_shortcode( 'wps_ai_chatbot', 'wps_chatbot_ai_shortcode' );
}
if('on' === get_option( 'wps_embed_source_rss_feed' , '')){
add_shortcode( 'wps_rssapp_feed', 'wps_rssapp_feed_shortcode' );
}

/**
 * Shortcode: [wps_twitch].
 * Description: Embeds a Twitch stream with optional live chat side-by-side.
 *
 * @param array $atts post id to print PDF for.
 *
 * Attributes:
 * - channel: (string) Twitch channel username (required).
 * - width: (string) Width of the Twitch player iframe (default: '100%').
 * - height: (string|int) Height of the Twitch player iframe (default: '480').
 * - chat_width: (string) Width of the chat iframe (default: '100%').
 * - chat_height: (string|int) Height of the chat iframe (default: '480').
 * - show_chat: (string) Whether to show chat iframe (yes/no, default: 'yes').
 *
 * Example usage:
 * [wps_twitch channel="your_channel" width="70%" chat_width="30%" show_chat="yes"].
 */
function wps_twitch_stream_with_chat_shortcode( $atts ) {
        // Set default attributes and merge with user-provided values.
        $atts = shortcode_atts(
                array(
                        'channel'     => '',          // Twitch channel name.
                        'width'       => '100%',      // Player width.
                        'height'      => '480',       // Player height.
                        'chat_width'  => '100%',      // Chat width.
                        'chat_height' => '480',       // Chat height.
                        'show_chat'   => 'yes',       // Show chat? 'yes' or 'no'.
                ),
                $atts,
                'wps_twitch'
        );

        // Return error if no channel provided.
        if ( empty( $atts['channel'] ) ) {
                return '<p>Please provide a Twitch channel name.</p>';
        }

        // Set the parent domain (required by Twitch embed policy).
        $host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
        $parent = esc_attr( $host );

        // Start building output.
        $output = '<div class="wps-twitch-embed" style="display: flex; flex-wrap: wrap; gap: 20px;">';

        // Twitch video stream.
        $output .= '<iframe
        src="https://player.twitch.tv/?channel=' . esc_attr( $atts['channel'] ) . '&parent=' . $parent . '"
        frameborder="0"
        allowfullscreen="true"
        scrolling="no"
        height="' . esc_attr( $atts['height'] ) . '"
        width="' . esc_attr( $atts['width'] ) . '">
    </iframe>';

        // Twitch live chat (optional).
        if ( 'yes' === $atts['show_chat'] ) {
                $output .= '<iframe
            src="https://www.twitch.tv/embed/' . esc_attr( $atts['channel'] ) . '/chat?parent=' . $parent . '"
            frameborder="0"
            scrolling="no"
            height="' . esc_attr( $atts['chat_height'] ) . '"
            width="' . esc_attr( $atts['chat_width'] ) . '">
        </iframe>';
        }

        $output .= '</div>';

        return $output;
}

// [wps_twitch channel="twitch_username"].
// [wps_twitch channel="twitch_username" width="700" height="400" chat_width="350" chat_height="400" show_chat="no"].


/**
 * Shortcode: [wps_strava].
 * Description: Embeds a Strava activity or segment using Strava Embeds service.
 *
 * @param array $atts post id to print PDF for.
 * Attributes:
 * - id: (string) The Strava activity or segment ID (required).
 * - type: (string) Type of embed: 'activity', 'segment', etc. (default: 'activity').
 * - style: (string) Display style, e.g., 'standard' (default: 'standard').
 * - from_embed: (string) Optional flag for embed source, e.g., 'true' or 'false' (default: 'false').
 *
 * Example usage:
 * [wps_strava id="1234567890" type="activity" style="standard"].
 */
function wps_strava_embed_shortcode( $atts ) {
        // Merge user-provided attributes with defaults.
        $atts = shortcode_atts(
                array(
                        'id' => '', // Strava Activity or Segment ID.
                        'type' => 'activity', // Type of embed (activity, segment, etc.).
                        'style' => 'standard', // Visual style of embed.
                        'from_embed' => 'false', // Optional flag for embed behavior.
                ),
                $atts,
                'wps_strava'
        );

        // If no ID is provided, return an error message.
        if ( empty( $atts['id'] ) ) {
                return '<p>Please provide a valid Strava activity ID.</p>';
        }

        // Start capturing output.
        ob_start();
        ?>
        <!-- Strava Embed Placeholder -->
        <div class="strava-embed-placeholder"
                 data-embed-type="<?php echo esc_attr( $atts['type'] ); ?>"
                 data-embed-id="<?php echo esc_attr( $atts['id'] ); ?>"
                 data-style="<?php echo esc_attr( $atts['style'] ); ?>"
                 data-from-embed="<?php echo esc_attr( $atts['from_embed'] ); ?>">
        </div>

        <!-- Strava Embed Script -->
        <?php
        if ( ! wp_script_is( 'strava-embed', 'enqueued' ) ) {
                wp_enqueue_script( 'strava-embed', 'https://strava-embeds.com/embed.js', array(), time(), true );
        }
        // Return the buffered output.
        return ob_get_clean();
}

/**
 * Shortcode: [wps_ai_chatbot].
 * Description: Embeds a customizable AI chatbot iframe widget.
 *
 * @param array $atts Post ID to print PDF for.
 * Attributes:
 * - url: (string) The chatbot URL to be embedded (required).
 * - height: (string) Height of the chatbot iframe (default: 700px).
 * - header_color: (string) Background color or gradient of the chatbot header (default: #4e54c8).
 * - header_title: (string) Title text shown in the header (default: "AI Chat Assistant").
 *
 * Example:
 * [wps_ai_chatbot url="https://yourbot.com/chat" height="600px" header_title="Support Bot"].
 */
function wps_chatbot_ai_shortcode( $atts ) {
        // Set default values and merge with user-supplied attributes.
        $atts = shortcode_atts(
                array(
                        'url' => '',
                        'height' => '700px',
                        'header_color' => '#4e54c8',
                        'header_title' => 'AI Chat Assistant',
                ),
                $atts
        );

        // Show an error message if the required URL is missing.
        if ( empty( $atts['url'] ) ) {
                return '<div style="color: red; font-weight: bold;">Chatbot URL is missing.</div>';
        }

        // Start output buffering to return generated HTML.
        ob_start();
        ?>
        <style>
                /* Main chatbot wrapper styling */
                .wps-chatbot-wrapper {
                        max-width: 960px;
                        margin: 40px auto;
                        border-radius: 16px;
                        overflow: hidden;
                        box-shadow: 0 12px 30px rgba(0,0,0,0.1);
                        background: #fff;
                        animation: fadeIn 1s ease-in-out;
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }

                /* Header styling with dynamic background color */
                .wps-chatbot-header {
                        background: <?php echo esc_attr( $atts['header_color'] ); ?>;
                        color: #fff;
                        padding: 20px 30px;
                        font-size: 20px;
                        font-weight: bold;
                        display: flex;
                        align-items: center;
                        gap: 10px;
                }

                /* Optional icon style */
                .wps-chatbot-header svg {
                        width: 24px;
                        height: 24px;
                        fill: #fff;
                }

                /* Iframe container */
                .wps-chatbot-iframe-container iframe {
                        width: 100%;
                        min-height: <?php echo esc_attr( $atts['height'] ); ?>;
                        border: none;
                        display: block;
                }

                /* Simple fade-in animation */
                @keyframes fadeIn {
                        from { opacity: 0; transform: translateY(20px); }
                        to { opacity: 1; transform: translateY(0); }
                }
        </style>

        <!-- Chatbot Embed Structure -->
        <div class="wps-chatbot-wrapper wps-no-print">
                <!-- Header with title and optional icon -->
                <div class="wps-chatbot-header">
                        <svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 0 0-10 10 9.99 9.99 0 0 0 5.29 8.75c-.1.75-.32 1.84-.79 3.01 0 0-.04.09.01.14.05.05.13.02.13.02 1.72-.24 3.05-.99 3.58-1.33A10.01 10.01 0 0 0 22 12 10 10 0 0 0 12 2z"/></svg>
                        <?php echo esc_html( $atts['header_title'] ); ?>
                </div>

                <!-- Embedded chatbot iframe -->
                <div class="wps-chatbot-iframe-container">
                        <iframe src="<?php echo esc_url( $atts['url'] ); ?>" style="min-height: <?php echo esc_attr( $atts['height'] ); ?>;"></iframe>
                </div>
        </div>
        <?php
        // Return the output buffer content.
        return ob_get_clean();
}

/**
 * Shortcode: [wps_rssapp_feed].
 * Description: Embeds an RSS.app widget feed with customizable styles via shortcode attributes.
 *
 * @param array $atts array.
 *
 * Attributes:
 * - url: (string) The RSS.app embed URL (required).
 * - height: (string) Height of the embedded iframe (default: 600px).
 * - title: (string) Title displayed above the feed (default: "📰 Latest News").
 * - bg_color: (string) Background color of the header (default: #ffffff).
 * - text_color: (string) Text color of the header and content (default: #333333).
 * - border_color: (string) Border color for the widget container (default: #eeeeee).
 *
 * Usage:
 * [wps_rssapp_feed url="https://rss.app/embed/your-widget" height="500px" title="My Feed"].
 */
function wps_rssapp_feed_shortcode( $atts ) {
        // Define default attribute values and merge with user-supplied attributes.
        $atts = shortcode_atts(
                array(
                        'url' => '',
                        'height' => '600px',
                        'title' => '📰 Latest News',
                        'bg_color' => '#ffffff',
                        'text_color' => '#333333',
                        'border_color' => '#eeeeee',
                ),
                $atts
        );

        // If URL is missing, show a helpful message.
        if ( empty( $atts['url'] ) ) {
                return '<p>Please provide a valid RSS.app widget URL.</p>';
        }

        // Start output buffering to return HTML content as a string.
        ob_start();
        ?>
        <div class="wps-rssapp-news-wrapper wps-no-print" style="
                color: <?php echo esc_attr( $atts['text_color'] ); ?>;
                border: 1px solid <?php echo esc_attr( $atts['border_color'] ); ?>;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.07);
                margin: 30px auto;
                max-width: 1000px;
        ">
                <!-- Feed Header -->
                <div class="wps-rssapp-header wps-no-print" style="
                        padding: 16px 24px;
                        font-size: 22px;
                        font-weight: bold;
                        border-bottom: 1px solid <?php echo esc_attr( $atts['border_color'] ); ?>;
                        background-color: <?php echo esc_attr( $atts['bg_color'] ); ?>;
                        color: <?php echo esc_attr( $atts['text_color'] ); ?>;
                ">
                        <?php echo esc_html( $atts['title'] ); ?>
                </div>

                <!-- RSS App Iframe -->
                <iframe
                        src="<?php echo esc_url( $atts['url'] ); ?>"
                        width="100%"
                        height="<?php echo esc_attr( $atts['height'] ); ?>"
                        style="border: none; overflow-y: auto;"
                        scrolling="yes"
                ></iframe>
        </div>
        <?php
        // Return the buffered content.
        return ob_get_clean();
}

/**
 * Adding shortcode to show create pdf icon anywhere on the page.
 */
add_shortcode( 'WPS_SINGLE_IMAGE', 'wps_display_uploaded_image_shortcode' );

/**
 * Callback function for shortcode.
 *
 * @since 1.0.0
 * @param array $atts An array of shortcode.
 * @return string
 */
function wps_display_uploaded_image_shortcode( $atts ) {
        // Set default attributes for the shortcode.
        $atts = shortcode_atts(
                array(
                        'id'  => '',    // Attachment ID.
                        'url' => '',    // Image URL if no ID is given.
                        'alt' => 'Image', // Alt text for accessibility.
                        'width'  => '100%', // Width of the image.
                        'height' => 'auto',  // Height of the image.
                ),
                $atts,
                'wps_image'
        );

        // Get image URL from attachment ID if provided.
        if ( ! empty( $atts['id'] ) ) {
                $image_src = wp_get_attachment_image_url( $atts['id'], 'full' );
        } else {
                $image_src = esc_url( $atts['url'] );
        }

        // Check if image source exists.
        if ( empty( $image_src ) ) {
                return '<p>No image found.</p>';
        }
        // Return the image HTML.
        return '<img src="' . esc_url( $image_src ) . '" alt="' . esc_attr( $atts['alt'] ) . '" style="width: ' . esc_attr( $atts['width'] ) . '; height: ' . esc_attr( $atts['height'] ) . ';">';
}


/**
 * Notification update.
 */
function wps_finalpdf_remove_cron_for_notification_update() {
           wp_clear_scheduled_hook( 'wps_finalpdf_check_for_notification_update' );
}

add_action( 'admin_notices', 'wps_banner_notification_plugin_html' );

if ( ! function_exists( 'wps_banner_notification_plugin_html' ) ) {
        /**
         * Notification.
         */
        function wps_banner_notification_plugin_html() {
                $screen = get_current_screen();
                if ( isset( $screen->id ) ) {
                        $pagescreen = $screen->id;
                }
                if ( ( isset( $pagescreen ) && 'plugins' === $pagescreen ) || ( 'wp-swings_page_home' == $pagescreen ) ) {
                        $notification_id = get_option( 'wps_finalpdf_notify_new_msg_id', false );
                        $banner_id = get_option( 'wps_finalpdf_notify_new_banner_id', false );
                        if ( isset( $banner_id ) && '' !== $banner_id ) {
                                $hidden_banner_id            = get_option( 'wps_finalpdf_notify_hide_baneer_notification', false );
                                $banner_image = get_option( 'wps_finalpdf_notify_new_banner_image', '' );
                                $banner_url = get_option( 'wps_finalpdf_notify_new_banner_url', '' );
                                if ( isset( $hidden_banner_id ) && $hidden_banner_id < $banner_id ) {

                                        if ( '' !== $banner_image && '' !== $banner_url ) {

                                                ?>
                                                        <div class="wps-offer-notice notice notice-warning is-dismissible">
                                                                <div class="notice-container">
                                                                        <a href="<?php echo esc_url( $banner_url ); ?>" target="_blank"><img src="<?php echo esc_url( $banner_image ); ?>" alt="Gift cards"/></a>
                                                                </div>
                                                                <button type="button" class="notice-dismiss dismiss_banner" id="dismiss-banner"><span class="screen-reader-text">Dismiss this notice.</span></button>
                                                        </div>
                                                        
                                                <?php
                                        }
                                }
                        }
                }
        }
}

add_action( 'admin_notices', 'wps_finalpdf_notification_plugin_html' );
/**
 * Notification html.
 */
function wps_finalpdf_notification_plugin_html() {

        $screen = get_current_screen();
        if ( isset( $screen->id ) ) {
                $pagescreen = $screen->id;
        }
        if ( ( isset( $_GET['page'] ) && 'finalpdf_menu' === $_GET['page'] ) ) {
                $notification_id = get_option( 'wps_finalpdf_notify_new_msg_id', false );
                $banner_id = get_option( 'wps_finalpdf_notify_new_banner_id', false );
                if ( isset( $banner_id ) && '' !== $banner_id ) {
                        $hidden_banner_id            = get_option( 'wps_finalpdf_notify_hide_baneer_notification', false );
                        $banner_image = get_option( 'wps_finalpdf_notify_new_banner_image', '' );
                        $banner_url = get_option( 'wps_finalpdf_notify_new_banner_url', '' );
                        if ( isset( $hidden_banner_id ) && $hidden_banner_id < $banner_id ) {

                                if ( '' !== $banner_image && '' !== $banner_url ) {

                                        ?>
                                                        <div class="wps-offer-notice notice notice-warning is-dismissible">
                                                                <div class="notice-container">
                                                                        <a href="<?php echo esc_url( $banner_url ); ?>" target="_blank"><img src="<?php echo esc_url( $banner_image ); ?>" alt="Gift cards"/></a>
                                                                </div>
                                                                <button type="button" class="notice-dismiss dismiss_banner" id="dismiss-banner"><span class="screen-reader-text">Dismiss this notice.</span></button>
                                                        </div>
                                                        
                                                <?php
                                }
                        }
                }
        }

}
