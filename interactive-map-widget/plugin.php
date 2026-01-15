<?php
/**
 * Plugin Name: Interactive Map Widget
 * Description: A map widget that allows to choose a country and add markers with images an description in any point of the map
 * Version:     1.2.19
 * Author:      Wordpress Hilfe & Support - Nahiro.net
 * Author URI:  https://nahiro.net/
 * Text Domain: interactive-map-widget
 * Domain Path: /languages
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Elementor tested up to: 3.32.2
 * Elementor Pro tested up to: 3.32.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

function nahiro_interactive_map_add_elementor_widget_map_categories( $elements_manager ) {

	$elements_manager->add_category(
		'nahiro_net',
		[
			'title' => esc_html__( 'Nahiro.net', 'interactive-map-widget' ),
			'icon' => 'eicon-plug',
		]
	);

}
//-----------s
add_action('admin_menu', 'nahiro_interactive_map_menu');

function nahiro_interactive_map_menu() {
    add_menu_page(
        esc_html__('Nahiro Interactive Map', 'interactive-map-widget'),
        esc_html__('Nahiro Interactive Map', 'interactive-map-widget'),
        'manage_options',
        'nahiro-interactive-map-info',
        'nahiro_interactive_map_main_menu_page',
        'dashicons-location',
        6
    );
    //Guide
    add_submenu_page(
        'nahiro-interactive-map-info',
        esc_html__('Quick Start', 'interactive-map-widget'),
        esc_html__('Quick Start', 'interactive-map-widget'),
        'manage_options',
        'nahiro-interactive-map-info',
        'nahiro_interactive_map_main_menu_page'
    );
    //settings
    add_submenu_page(
        'nahiro-interactive-map-info',
        esc_html__('Settings', 'interactive-map-widget'),
        esc_html__('Map Selector', 'interactive-map-widget'),
        'manage_options',
        'nahiro-interactive-map-settings',
        'nahiro_interactive_map_options_page_html'
    );
    //More from nahiro.net
    add_submenu_page(
        'nahiro-interactive-map-info',
        esc_html__('Plugins', 'interactive-map-widget'),
        esc_html__('Plugins', 'interactive-map-widget'),
        'manage_options',
        'nahiro-interactive-map-plugins',
        'nahiro_interactive_map_plugins_page'
    );
}

function nahiro_interactive_map_main_menu_page() {
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Map Guide', 'interactive-map-widget'); ?></h1>
        <div class="table-of-contents">
            <h3><?php esc_html_e('On this guide', 'interactive-map-widget'); ?></h3>
            <ol>
                <li>
                    <a class="nahiro-tbl-link" href="#nh1">
                        <?php esc_html_e('Using the Map', 'interactive-map-widget'); ?>
                    </a>
                </li>                
                <li>
                    <a class="nahiro-tbl-link" href="#nh2">
                        <?php esc_html_e('Modifying Pins and Cards', 'interactive-map-widget'); ?>
                    </a>
                </li>                
                <li>
                    <a class="nahiro-tbl-link" href="#nh3">
                        <?php esc_html_e('Changing the map country', 'interactive-map-widget'); ?>
                    </a>
                </li>                
                <li>
                    <a class="nahiro-tbl-link" href="#nh4">
                        <?php esc_html_e('Hiding Pins/Markers', 'interactive-map-widget'); ?>
                    </a>
                </li>                
            </ol>
        </div>
        <div class="container">
            <h1 class="nahiro-h1"><?php esc_html_e('Using the Map', 'interactive-map-widget'); ?><a id="nh1"></a></h1>
            <p><?php esc_html_e('First thing we need to do is create or edit a post or page in Elementor. After launching the editor the next steps are:', 'interactive-map-widget'); ?>
                <ol>
                    <li><?php esc_html_e('Click the icon', 'interactive-map-widget'); ?> <code>+</code> <?php esc_html_e('to create a container to use your map', 'interactive-map-widget'); ?></li>
                    <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/tutorial/01-1.png'); ?>">
                    
                    <li><?php esc_html_e('Select a container structure (if you want to keep things simple, select the first one)', 'interactive-map-widget'); ?></li>
                    <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/tutorial/01-2.png'); ?>">
                    
                    <li><?php esc_html_e('Click', 'interactive-map-widget'); ?> <code>+</code> <?php esc_html_e('again to add a Widget to Elementor', 'interactive-map-widget'); ?></li>
                    <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/tutorial/01-3.png'); ?>">
                    
                    <li><?php esc_html_e('Now in the editor panel search for "interactive map", you will find the map widget shown in the image below, when you have found it click on the widget box', 'interactive-map-widget'); ?></li>
                    <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/tutorial/01-4.png'); ?>">
                    
                    <li><?php esc_html_e('Now you will see the widget on your page.', 'interactive-map-widget'); ?></li>
                    <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/tutorial/01-5.png'); ?>">
                    
                    <li><?php esc_html_e('Next, click the widget to open the editor panel where you will be able to modify the map to your liking. If you need more configuration options, pins or styles please check', 'interactive-map-widget'); ?> <a href="https://nahiro.net/wordpress-plugins/interaktive-karte-fuer-elementor-pro/"><?php esc_html_e('Interactive Map Widget Pro', 'interactive-map-widget'); ?></a></li>
                    <a id="editor-panel-map"><?php esc_html_e('editor panel', 'interactive-map-widget'); ?></a>
                    <img class="nahiro-img-vertical" src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/tutorial/01-6.png'); ?>">
                </ol>
            <p>
            <br>
            <br>
            <br>
            <h1 class="nahiro-h1"><?php esc_html_e('Modifying Pins and Cards', 'interactive-map-widget'); ?><a id="nh2"></a></h1>
            <p>
                <ol>
                    <li><?php esc_html_e('Cards are informational elements that appears on top of pins when the pin is clicked', 'interactive-map-widget'); ?></li>
                    <img class="nahiro-img-horizontal-big" src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/tutorial/02-1.png'); ?>">
                    
                    <li><?php esc_html_e('To edit the Pins content, head to the editor panel (to the', 'interactive-map-widget'); ?> <a href="#editor-panel-map"><?php esc_html_e('map widget settings on editor panel', 'interactive-map-widget'); ?></a>), <?php esc_html_e('and open the section called', 'interactive-map-widget'); ?> <code><?php esc_html_e('Pins Content', 'interactive-map-widget'); ?></code>, <?php esc_html_e('inside this section you will see horizontal bars with the name of each pin, to modify a pin and its card, click the element you wish to modify', 'interactive-map-widget'); ?></li>
                    <img class="nahiro-img-vertical m-28" src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/tutorial/02-2.png'); ?>">
                    
                    <li><?php esc_html_e('At the end of the previous section you will see another horizontal bar with the title', 'interactive-map-widget'); ?> <code><?php esc_html_e('Cards Content', 'interactive-map-widget'); ?></code>, <?php esc_html_e('this bar when clicked will open the pin card\'s content and will allow you to modify it too', 'interactive-map-widget'); ?></li>
                    <img class="nahiro-img-vertical m-28" src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/tutorial/02-3.png'); ?>">
                    
                    <li><?php esc_html_e('If you would like to have more options in the cards content and even add custom HTML please check', 'interactive-map-widget'); ?> <a href="https://nahiro.net/wordpress-plugins/interaktive-karte-fuer-elementor-pro/"><?php esc_html_e('Interactive Map Widget Pro', 'interactive-map-widget'); ?></a></li>
                </ol>
            <p>
            <br>
            <br>
            <br>
            <h1 class="nahiro-h1"><?php esc_html_e('Changing the map country', 'interactive-map-widget'); ?><a id="nh3"></a></h1>
            <p>
                <ol>
                    <li><?php esc_html_e('To change the map country go to the map settings page (', 'interactive-map-widget'); ?><a href="<?php echo esc_url(admin_url('admin.php?page=nahiro-interactive-map-settings')); ?>" target="_blank"><?php esc_html_e('click here to go there now', 'interactive-map-widget'); ?></a>), <?php esc_html_e('in this page you will be able to choose one map to use in your widget at a time, select one country save your selection and you are ready to use your map in any page you want!', 'interactive-map-widget'); ?></li>
                    <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/tutorial/03-1.png'); ?>">
                    
                    <li><?php esc_html_e('To use multiple maps of different countries at the same time you can try our paid alternative', 'interactive-map-widget'); ?> <a href="https://nahiro.net/wordpress-plugins/interaktive-karte-fuer-elementor-pro/"><?php esc_html_e('Interactive Map Widget Pro', 'interactive-map-widget'); ?></a></li>
                    <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/tutorial/03-2.png'); ?>">
                </ol>
            <p>
            <br>
            <br>
            <br>
            <h1 class="nahiro-h1"><?php esc_html_e('Hiding Pins/Markers', 'interactive-map-widget'); ?><a id="nh4"></a></h1>
            <p>
                <ol>
                    <li><?php esc_html_e('The maps come with only 2 pins/markers, but if you want to use only one or two you can hide them individually, to do so please head to the editor panel (', 'interactive-map-widget'); ?><a href="#editor-panel-map"><?php esc_html_e('this editor panel', 'interactive-map-widget'); ?></a>), <?php esc_html_e('then select the TAB', 'interactive-map-widget'); ?> <code><?php esc_html_e('Styles', 'interactive-map-widget'); ?></code></li>
                    <img class="nahiro-img-vertical" src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/tutorial/04-1.png'); ?>">

                    <li><?php esc_html_e('Inside the previous section you will have the options:', 'interactive-map-widget'); ?> <code><?php esc_html_e('Display Pin N:1', 'interactive-map-widget'); ?></code> <?php esc_html_e('and', 'interactive-map-widget'); ?> <code><?php esc_html_e('Display Pin N:2', 'interactive-map-widget'); ?></code>; <?php esc_html_e('by default all these options are set to "Yes" which means all pins will be shown. To hide one pin simply set that pin display option to "No"', 'interactive-map-widget'); ?></li>
                    <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/tutorial/04-2.png'); ?>">

                    <li><?php esc_html_e('If you need more pins you can', 'interactive-map-widget'); ?> <a href="https://nahiro.net/wordpress-plugins/interaktive-karte-fuer-elementor-pro/"><?php esc_html_e('Upgrade to pro', 'interactive-map-widget'); ?></a> <?php esc_html_e('and gain more available pins for you to use!', 'interactive-map-widget'); ?></li>
                </ol>
            <p>
                <br>
        </div>
    </div>
    <?php
}


// plugin page
//10-02-25
function nahiro_interactive_map_plugins_page(){
    
    $lang=get_bloginfo("language");


    require_once(dirname(__FILE__).'/includes/list-plugins.php'); 
}

//2
	
add_action('admin_init', 'nahiro_interactive_map_register_settings');

function nahiro_interactive_map_register_settings() {
    register_setting('nahiro_interactive_map_options_group', 'nahiro_country_option', [
        'type' => 'string', 
        'sanitize_callback' => 'sanitize_text_field',
        'default' => NULL,
    ]);
}

add_action('admin_init', 'nahiro_interactive_map_settings_init');

function nahiro_interactive_map_settings_init() {
    add_settings_section(
        'nahiro_interactive_map_settings_section', //id
        esc_html__('Map Settings', 'interactive-map-widget'), //title
        'nahiro_interactive_map_section_callback', //callback
        'country' //page
    );

    add_settings_field(
        'nahiro_interactive_map_select_field', //id
        esc_html__('Select a Country', 'interactive-map-widget'), //title
        'nahiro_interactive_map_field_callback', //callback
        'country', //page
        'nahiro_interactive_map_settings_section' //section
    );
}

function nahiro_interactive_map_section_callback() {
    echo '<p>' . esc_html__('Choose an option from the dropdown below:', 'interactive-map-widget') . '</p>';
}

function nahiro_interactive_map_field_callback() {
    $options = get_option('nahiro_country_option');
    require_once plugin_dir_path(__FILE__) . 'includes/country-list.php';
}

function nahiro_interactive_map_options_page_html() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // Output security fields for the registered setting "nahiro_interactive_map_options_group"
            settings_fields('nahiro_interactive_map_options_group');
            // Output setting sections and their fields
            // (sections are registered for "country", each field is registered to a specific section)
            do_settings_sections('country');
            // Output save settings button
            submit_button(esc_html__('Save Settings', 'interactive-map-widget'));
            ?>
        </form>
    </div>
    <?php
}

// Plugin activation hook
register_activation_hook(__FILE__, 'nahiro_interactive_map_activate');

function nahiro_interactive_map_activate() {
    // Set an option to indicate the plugin was just activated
    add_option('nahiro_interactive_map_show_welcome', true);
}

// Redirect to the welcome screen
add_action('admin_init', 'nahiro_interactive_map_redirect_welcome');

function nahiro_interactive_map_redirect_welcome() {
    // Check if the welcome page should be shown
    if (get_option('nahiro_interactive_map_show_welcome')) {
        // Delete the option so it doesn’t show again
        delete_option('nahiro_interactive_map_show_welcome');
        
        // Redirect to the welcome page
        wp_safe_redirect(esc_url(admin_url('admin.php?page=nahiro-interactive-map-welcome')));
        exit;
    }
}


// Add the welcome page
add_action('admin_menu', 'nahiro_interactive_map_add_welcome_page');
function nahiro_interactive_map_add_welcome_page() {
    add_menu_page(
        esc_html__('Welcome to Nahiro Interactive Map', 'interactive-map-widget'), // Page title
        esc_html__('Nahiro Interactive Map Welcome', 'interactive-map-widget'), // Menu title
        'manage_options', // Capability
        'nahiro-interactive-map-welcome', // Menu slug
        'nahiro_interactive_map_setup_page_content' // Function to display the content
    );
}

function nahiro_interactive_map_setup_page_content() {
    if (!current_user_can('manage_options')) {
        return;
    }

    // If the form is submitted, save the options and redirect
    if (isset($_POST['submit'])) {
        check_admin_referer('nahiro_interactive_map_setup'); // Security nonce check
        
        $country = isset($_POST['nahiro_country_option']) ? sanitize_text_field($_POST['nahiro_country_option']) : 'germany';
        update_option('nahiro_country_option', $country);
        
        update_option('nahiro_interactive_map_setup_complete', true);
        
        if (!headers_sent()) {
            wp_safe_redirect(admin_url('admin.php?page=nahiro-interactive-map-info'));
            exit;
        } else {
            // Add a hidden input to flag the redirection
            echo '<input type="hidden" id="nahiro-redirect-flag" value="1">';
            echo '<div class="wrap"><a href="' . esc_url(admin_url('admin.php?page=nahiro-interactive-map-info')) . '" class="nahiro-link nahiro-fallback-redirect">' . esc_html__('Click here to go to the plugin page', 'interactive-map-widget') . '</a></div>';
            return;
        }
    }

    ?>
    <menu>
        <div>
            <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/Logo-Nahiro.Net-Color.png'); ?>">
        </div>
        <div class="menu-buttons">
            <a href="https://nahiro.net/wordpress-plugins/interaktive-karte-fuer-elementor-pro/" class="button button-primary upgrade">
                <?php esc_html_e('Upgrade', 'interactive-map-widget'); ?>
            </a>
            <a href="https://my.nahiro.net/register.php" class="nahiro-link">
                <?php esc_html_e('Create an account', 'interactive-map-widget'); ?>
            </a>
        </div>
    </menu>
    <div class="wrap">
        <div class='content'>
            <h1><?php esc_html_e('Welcome to Nahiro Interactive Map', 'interactive-map-widget'); ?></h1>
            <form method="post">
                <?php wp_nonce_field('nahiro_interactive_map_setup'); // Security nonce ?>
                <p><?php esc_html_e('Thank you for installing our Plugin! To begin please select the country you wish to use on your widget:', 'interactive-map-widget'); ?></p>
                
                <?php
                require_once plugin_dir_path(__FILE__) . 'includes/country-list.php';
                submit_button(esc_html__('Complete Setup', 'interactive-map-widget'));
                ?>
            </form>
        </div>
        <div class='img-container'>
            <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/WordPress-Hilfe-Berlin-8.png'); ?>">
        </div>
    </div>
    <?php
}

add_action('admin_menu', 'nahiro_interactive_map_remove_welcome_page');

function nahiro_interactive_map_remove_welcome_page() {
    // Check if the setup is complete
    if (get_option('nahiro_interactive_map_setup_complete')) {
        remove_menu_page('nahiro-interactive-map-welcome');
    }
}

// Elementor widget registration
add_action('elementor/elements/categories_registered', 'nahiro_interactive_map_add_elementor_widget_map_categories');


function nahiro_interactive_map_register_custom_widget() {
    require_once plugin_dir_path(__FILE__) . 'widget_map.php';
    \Elementor\Plugin::instance()->widgets_manager->register(new Nahiro_Interactive_Map_Widget());
}
add_action('elementor/widgets/register', 'nahiro_interactive_map_register_custom_widget');

function nahiro_interactive_map_add_dependencies() {
    wp_register_script('nahiro_map_widget_js', plugins_url('assets/js/map_widget.js', __FILE__), array('jquery'), '3.1.4', true);
    wp_enqueue_script('nahiro_core_widget_js', plugins_url('assets/js/core_map.js', __FILE__), array('jquery'), '1.9', true);
    wp_register_style('nahiro_map_widget_css', plugins_url('assets/css/map_widget.css', __FILE__), array(), '1.3.5');
    wp_enqueue_style('nahiro_map_dynamic_css', plugin_dir_url(__FILE__) . 'assets/css/map_dynamic.css', array(), '1.0.1');
}
add_action('wp_enqueue_scripts', 'nahiro_interactive_map_add_dependencies');

function nahiro_interactive_map_add_admin_dependencies() {
    wp_enqueue_style('nahiro_map_dynamic_css', plugin_dir_url(__FILE__) . 'assets/css/map_dynamic.css', array(), '1.3.1');
}
add_action('admin_enqueue_scripts', 'nahiro_interactive_map_add_admin_dependencies');

function nahiro_interactive_map_editor_dependencies() {
    wp_enqueue_style('nahiro_map_editor_css', plugins_url('assets/css/editor.css', __FILE__), array(), '1.3.1');
	
}
add_action('elementor/editor/before_enqueue_styles', 'nahiro_interactive_map_editor_dependencies');

function nahiro_interactive_map_add_welcome_styles($hook) {
    $screen = get_current_screen();

    if ($hook === 'toplevel_page_nahiro-interactive-map-welcome') {
        wp_enqueue_style('nahiro_map_welcome_css', plugin_dir_url(__FILE__) . 'assets/css/welcome.css', array(), '1.3.1');
        wp_enqueue_script('nahiro_map_welcome_js', plugins_url('assets/js/welcome.js', __FILE__), array(), '1.3.1', true);
        // Localize script to pass data from PHP to JavaScript
        wp_localize_script('nahiro_map_welcome_js', 'nahiroRedirectData', array(
            'redirectUrl' => esc_url(admin_url('admin.php?page=nahiro-interactive-map-info'))
        ));
    }
    if ($hook === 'toplevel_page_nahiro-interactive-map-info') {
        wp_enqueue_style('nahiro_map_info_css', plugin_dir_url(__FILE__) . 'assets/css/info.css', array(), '1.3.1');
    }
    //submenus are managed different because url changes in the language
    if (strpos($hook, 'nahiro-interactive-map-plugins') !== false) {
        wp_enqueue_style(
            'nahiro_font_css', 
            plugin_dir_url( __FILE__ ) . 'assets/css/nhfont.css'
        );
        /**
         * neccesary to show the modal properly
         */
        // Load ThickBox (for the modal functionality)
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        // Load the WP scripts that handle plugin info modals
        wp_enqueue_script('plugin-install');
        wp_enqueue_script('updates');
    }
    // var_dump($hook);die();
}
add_action('admin_enqueue_scripts', 'nahiro_interactive_map_add_welcome_styles', 9);

function nahiro_interactive_map_editor_scripts() {
    wp_enqueue_script('nahiro_map_editor_panel', plugin_dir_url(__FILE__) . 'assets/js/editor.js', array('jquery'), '1.1.0', true);
	
	 	 wp_enqueue_style( 'map_region_control_css', plugin_dir_url( __FILE__ ) . 'assets/css/map-region-control.css', [], '1.0.2' );
        wp_enqueue_script( 'map_region_control_js', plugin_dir_url( __FILE__ ) . 'assets/js/map-region-control.js', ['jquery'], '1.0.2', true );
	 
}
add_action('wp_enqueue_scripts', 'nahiro_interactive_map_editor_scripts');

function nahiro_load_plugin_textdomain() {
    load_plugin_textdomain('interactive-map-widget', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'nahiro_load_plugin_textdomain');


