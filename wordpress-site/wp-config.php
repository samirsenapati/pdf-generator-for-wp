<?php
/**
 * WordPress configuration for Replit with SQLite
 */

// SQLite database configuration - the db.php drop-in will handle this
define( 'DB_DIR', dirname(__FILE__) . '/wp-content/database/' );
define( 'DB_FILE', 'database/.ht.sqlite' );

// Create database directory if it doesn't exist
if (!file_exists(DB_DIR)) {
    mkdir(DB_DIR, 0755, true);
}

// Security keys - generated for this installation
define( 'AUTH_KEY',         'QO2z~H2G?d!u^G5K5E4%|x_gT4+Gx$yH,j@g:Fb:q=Qvg~w+K8~2|Jx+g@3r' );
define( 'SECURE_AUTH_KEY',  'k|~$o}@-M3F8|mZ4&:c|6x/N:Qd*e+b~6o6|w@wX7l.z5s-j-y|r)s*+e-+a' );
define( 'LOGGED_IN_KEY',    'x3b~9-5m|g3-$6i&@x:m|5o~w@6k~9r~y|3s-q|5i-+a&@x|2m~g|8r~y+4s' );
define( 'NONCE_KEY',        'a~3x|5m~g|9r~y+6s-q|2i-+b&@w|4n~f|7t~z+8u-r|3k-+c&@v|6o~h|9s' );
define( 'AUTH_SALT',        'p|5s~y+9v-q|3j-+c&@w|7n~g|8r~z+4t-u|2k-+d&@x|5o~h|6s~y+9v-q' );
define( 'SECURE_AUTH_SALT', 'l|6t~z+3u-r|4k-+e&@x|8o~h|9s~y+5v-p|2j-+f&@w|6n~g|7r~z+3t-u' );
define( 'LOGGED_IN_SALT',   'm|7u~a+4v-s|5l-+g&@y|9p~i|3t~z+6w-q|4k-+h&@x|7o~g|8s~a+4v-s' );
define( 'NONCE_SALT',       'n|8v~b+5w-t|6m-+h&@z|4q~j|9u~a+7x-r|5l-+i&@y|8p~h|9t~b+5w-t' );

$table_prefix = 'wp_';

define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );

// Suppress PHP warnings from SQLite integration
@error_reporting(E_ALL & ~E_WARNING & ~E_DEPRECATED);
@ini_set('display_errors', '0');

// Allow direct file updates in Replit
define( 'FS_METHOD', 'direct' );

// Increase memory limit
define( 'WP_MEMORY_LIMIT', '256M' );

/* That's all, stop editing! Happy publishing. */

if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

require_once ABSPATH . 'wp-settings.php';
