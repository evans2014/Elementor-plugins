<?php
/**
 * Plugin Name: Elementor Hover Card Single
 * Description: Hover card widget for Elementor
 * Version: 1.0
 * Author: AllWeb Agency 2025 
 * Author URI: https://allweb.agency/
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Loading CSS and JS
add_action( 'wp_enqueue_scripts', function() {
    wp_enqueue_style(
        'hover-card-single-style',
        plugin_dir_url( __FILE__ ) . 'assets/css/hover-card-single.css',
        [],
        '1.0'
    );

    wp_enqueue_script(
        'hover-card-single-script',
        plugin_dir_url( __FILE__ ) . 'assets/js/hover-card-single.js',
        [ 'jquery' ],
        '1.0',
        true
    );
});

// Registration of Elementor widget
add_action( 'elementor/widgets/register', function( $widgets_manager ) {
    require_once( __DIR__ . '/widget-hover-card-single.php' );
    $widgets_manager->register( new \Hover_Card_Single_Widget() );
});