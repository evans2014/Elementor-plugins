<?php
/**
 * Plugin Name: EHC Hover Card for Elementor
 * Description: Smooth hover card with mobile support.
 * Version: 1.0
 * Author: IVB
 * Text Domain: ehc-hover-card
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function ehc_load_plugin_textdomain() {
    load_plugin_textdomain( 'ehc-hover-card', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'ehc_load_plugin_textdomain' );


if ( ! did_action( 'elementor/loaded' ) ) {
    add_action( 'admin_notices', function() {

    } );
    //return;
}

function ehc_register_widget( $widgets_manager ) {
    require_once __DIR__ . '/widgets/ehc-hover-card-widget.php';
    $widgets_manager->register( new \Elementor\EHC_Hover_Card_Widget() );
}
add_action( 'elementor/widgets/register', 'ehc_register_widget' );

function ehc_enqueue_styles() {
    wp_enqueue_style( 'ehc-hover-card-style', plugins_url( 'assets/style.css', __FILE__ ), [], '1.0.0' );
}
add_action( 'elementor/frontend/after_enqueue_styles', 'ehc_enqueue_styles' );

function ehc_enqueue_scripts() {
    wp_enqueue_script( 'ehc-hover-card-script', plugins_url( 'assets/script.js', __FILE__ ), [], '1.0.0', true );
}
add_action( 'elementor/frontend/after_enqueue_scripts', 'ehc_enqueue_scripts' );