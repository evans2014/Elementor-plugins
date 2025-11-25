<?php
/**
 * Plugin Name: XAI Video Lightbox
 * Description: Lightweight, fast video popup – works everywhere
 * Version: 1.0
 */

if (!defined('ABSPATH')) exit;



function video_enqueue_scripts() {
	 //wp_register_script('video-init', plugins_url('assets/video.js', __FILE__), ['jquery'], '1.0', true);
    wp_register_style('video-style', plugins_url('assets/video.css', __FILE__));
    wp_enqueue_script('video-init');
    wp_enqueue_style('video-style');
}
add_action('wp_enqueue_scripts', 'video_enqueue_scripts');


final class XAI_Video_Lightbox_Plugin {
    private static $instance;
    public static function instance() {
        if (!self::$instance) self::$instance = new self();
        return self::$instance;
    }

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init() {
        if (!did_action('elementor/loaded')) return;
        add_action('elementor/widgets/register', [$this, 'register']);
    }

    public function register($manager) {
        require_once __DIR__ . '/widget-class.php';
        $manager->register(new XAI_Video_Lightbox_Widget());
    }
}
XAI_Video_Lightbox_Plugin::instance();