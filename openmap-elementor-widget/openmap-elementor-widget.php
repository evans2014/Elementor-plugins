<?php
/**
 * Plugin Name: OpenMap for Elementor
 * Description: OpenStreetMap with multiple locations and custom icons
 * Version: 2.0.0
 * Author: IVB
 * Requires Plugins: elementor
 * Text Domain: openmap-elementor
 */

if (!defined('ABSPATH')) exit;

// 1. ПРОВЕРКА: ДАЛИ ЕЛЕМЕНТОР Е ЗАРЕДЕН?
add_action('plugins_loaded', 'openmap_init_widget');
function openmap_init_widget() {
    if (!did_action('elementor/loaded')) {
        add_action('admin_notices', 'openmap_elementor_missing_notice');
        return;
    }

    add_action('elementor/widgets/register', 'openmap_register_widget');
}

function openmap_register_widget($widgets_manager) {
    require_once __DIR__ . '/widget-class.php';
    $widgets_manager->register(new OpenMap_Elementor_Widget());
}

function openmap_elementor_missing_notice() {
    if (current_user_can('install_plugins')) {
        $url = admin_url('plugin-install.php?s=elementor&tab=search&type=term');
        echo '<div class="notice notice-error">
            <p><strong>OpenMap for Elementor</strong> requires  -111
               <a href="' . esc_url($url) . '" target="_blank">Elementor</a> 
               to be installed and activated.</p>
        </div>';
    }
}

register_activation_hook(__FILE__, 'openmap_activation_check');
function openmap_activation_check() {
    if (!did_action('elementor/loaded')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(
            'OpenMap for Elementor requires Elementor. Plugin is disabled. <a href="' . admin_url('plugins.php') . '">Back</a>',
            'The plugin requires Elementor',
            ['back_link' => true]
        );
    }
}