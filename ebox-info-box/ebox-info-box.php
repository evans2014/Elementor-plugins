<?php
/**
 * Plugin Name: Elementor Info Box
 * Description: Elementor Info Box widget with presets.
 * Version: 1.2
 * Author: IVB
 * Text Domain: ebox-info
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'elementor/widgets/register', function( $widgets_manager ) {
    require_once __DIR__ . '/widgets/info-box.php';
    $widgets_manager->register( new \Ebox_Info_Box() );
});

add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style( 'ebox-info-box', plugins_url( 'assets/css/info-box.css', __FILE__ ) );
});
