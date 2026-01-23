<?php
/**
 * Plugin Name: Custom Video PopUp for Elementor
 * Description: Elementor widget for video popup.
 * Version: 1.0.0
 * Author: IVB
 * Text Domain: cvp-elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action( 'plugins_loaded', 'cvp_elementor_init' );

function cvp_elementor_init() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', 'cvp_elementor_missing_notice' );
        return;
    }

    add_action( 'elementor/widgets/register', 'cvp_register_video_popup_widget' );
}

add_action( 'elementor/frontend/after_enqueue_styles', 'cvp_enqueue_styles' );

function cvp_enqueue_styles() {
    wp_enqueue_style(
        'cvp-video-popup-style',
        plugins_url( 'assets/css/video-popup-style.css', __FILE__ ),
        [],
        '1.0.0'
    );
}

add_action('elementor/frontend/after_enqueue_scripts', function() {
    wp_enqueue_script(
        'cvp-video-popup-handler',
        plugins_url('assets/js/video-popup-handler.js', __FILE__),
        ['jquery'],
        '1.0.0',
        true
    );

    $video_data = [];

});

function cvp_elementor_missing_notice() {
    echo '<div class="notice notice-error"><p>' . esc_html__( 'Custom Video PopUp requires Elementor to be installed and activated.', 'cvp-elementor' ) . '</p></div>';
}

function cvp_register_video_popup_widget( $widgets_manager ) {
    require_once __DIR__ . '/widgets/video-popup-widget.php';
    $widgets_manager->register( new \CVP\Widgets\Video_Popup_Widget() );
}