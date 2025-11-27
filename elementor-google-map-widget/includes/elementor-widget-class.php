<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class EGMW_Elementor_Map_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'egmw_map'; }
    public function get_title() { return __( 'EGMW Google Map', 'egmw' ); }
    public function get_icon() { return 'eicon-google-maps'; }
    public function get_categories() { return array( 'general' ); }

    protected function register_controls() {
        $this->start_controls_section( 'section_map', array( 'label' => __( 'Map', 'egmw' ) ) );

        $this->add_control( 'height', [
            'label' => __( 'Map height (px)', 'egmw' ),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 400,
        ] );

        $this->add_control( 'center_lat', [
            'label' => __( 'Center Latitude (optional)', 'egmw' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
        ]);
        $this->add_control( 'center_lng', [
            'label' => __( 'Center Longitude (optional)', 'egmw' ),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '',
        ]);
        $this->add_control( 'zoom', [
            'label' => __( 'Zoom', 'egmw' ),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 10,
        ]);
        $this->add_control( 'cluster', [
            'label' => __( 'Enable clustering', 'egmw' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default' => 'yes',
        ]);

        $this->add_control( 'map_style', [
    'label' => __( 'Map Style', 'egmw' ),
    'type' => \Elementor\Controls_Manager::SELECT,
    'default' => 'default',
    'options' => [
        'default' => __( 'Default', 'egmw' ),
        'silver' => __( 'Silver', 'egmw' ),
        'retro' => __( 'Retro', 'egmw' ),
        'dark' => __( 'Dark', 'egmw' ),
        'night' => __( 'Night', 'egmw' ),
        'aubergine' => __( 'Aubergine', 'egmw' ),
        'custom' => __( 'Custom JSON', 'egmw' ),
    ],
]);


$this->add_control( 'map_style_json', [
    'label' => __( 'Custom JSON Style', 'egmw' ),
    'type' => \Elementor\Controls_Manager::TEXTAREA,
    'placeholder' => __( 'Paste custom JSON here', 'egmw' ),
    'condition' => [ 'map_style' => 'custom' ],
]);


$this->add_control( 'map_style_preview', [
    'label' => __( 'Preview', 'egmw' ),
    'type' => \Elementor\Controls_Manager::RAW_HTML,
    'raw' => '<div id="egmw-map-style-preview" style="width:100%;height:200px;border:1px solid #ccc;margin-top:10px"></div>',
]);
$this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // gather all published map_location posts
        $args = array(
            'post_type' => 'map_location',
            'post_status' => 'publish',
            'numberposts' => -1,
        );
        $posts = get_posts( $args );
        $locs = array();
        foreach ( $posts as $p ) {
            $lat = get_post_meta( $p->ID, '_egmw_lat', true );
            $lng = get_post_meta( $p->ID, '_egmw_lng', true );
            $address = get_post_meta( $p->ID, '_egmw_address', true );
            $icon = get_post_meta( $p->ID, '_egmw_icon', true );
            $svg_id = get_post_meta( $p->ID, '_egmw_svg_id', true );
            $svg_url = get_post_meta( $p->ID, '_egmw_svg_url', true );
            $popup = get_post_meta( $p->ID, '_egmw_popup', true );

            if ( $lat && $lng ) {
                // decide icon url: prefer uploaded SVG (svg_url), then legacy asset filename
                $icon_url = '';
                if ( $svg_url ) {
                    $icon_url = $svg_url;
                } elseif ( $icon ) {
                    $icon_url = EGMW_URL . 'assets/svg/' . $icon;
                }
                $locs[] = array(
                    'title' => get_the_title( $p ),
                    'lat' => (float)$lat,
                    'lng' => (float)$lng,
                    'address' => $address,
                    'icon_url' => $icon_url,
                    'popup' => $popup,
                );
            }
        }

        // prepare settings to pass
        $map_settings = array(
            'center_lat' => $settings['center_lat'],
            'center_lng' => $settings['center_lng'],
            'zoom' => (int)$settings['zoom'],
            'cluster' => ($settings['cluster'] === 'yes'),
            'map_style' => $settings['map_style'],
            'map_style_json' => $settings['map_style_json'],
        );

        // enqueue scripts & localize data (helper from includes/widget.php)
        $enqueued = egmw_enqueue_map_assets( $locs, $map_settings );

        // if no API key present, show admin notice in widget area
        if ( ! $enqueued ) {
            echo '<div style="padding:10px;border:1px solid #f00;color:#900;background:#fff8f8">';
            echo '<strong>EGMW:</strong> Google Maps API key not set. Please set it under Settings → Google Maps (EGMW).';
            echo '</div>';
            return;
        }

        // output container
        $map_id = 'egmw-map-' . uniqid();
        $height = intval( $settings['height'] );
        echo '<div id="'. esc_attr($map_id) .'" class="egmw-map-container" style="width:100%;height:' . $height . 'px" data-map-id="'. esc_attr($map_id) .'"></div>';
    }

    protected function _content_template() {
        ?>
        <# var h = settings.height || 400; #>
        <div class="egmw-map-container" style="width:100%;height:{{ h }}px"></div>
        <?php
    }
}