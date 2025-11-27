<?php
/**
 * Plugin Name: Elementor Google Map Widget (Custom) v1.1
 * Description: Elementor widget + admin to manage locations and show them on Google Map with SVG icons and clustering. Now supports uploading/selecting SVG icons from Media Library.
 * Version: 1.1.0
 * Author: (your name)
 * Text Domain: egmw
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'EGMW_PATH', plugin_dir_path( __FILE__ ) );
define( 'EGMW_URL', plugin_dir_url( __FILE__ ) );

require_once EGMW_PATH . 'includes/cpt.php';
require_once EGMW_PATH . 'includes/admin.php';
require_once EGMW_PATH . 'includes/widget.php';

/* Activation hook - setup default options */
function egmw_activate() {
    // Flush rewrite rules for CPT
    egmw_register_cpt();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'egmw_activate' );

function egmw_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'egmw_deactivate' );
