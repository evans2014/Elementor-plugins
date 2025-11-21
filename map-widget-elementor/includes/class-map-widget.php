<?php
if ( ! defined('ABSPATH') ) exit;

class Map_Widget_Elementor extends \Elementor\Widget_Base {

    public function get_name() { return 'map_widget_elementor'; }
    public function get_title() { return __('Map Widget Elementor', 'map-widget-elementor'); }
    public function get_icon() { return 'eicon-google-maps'; }
    public function get_categories() { return ['general']; }

    protected function register_controls() {
        $this->start_controls_section('section_map', ['label' => __('Map', 'map-widget-elementor')]);
        $this->add_control('height', ['label'=>'Height (px)', 'type'=>\Elementor\Controls_Manager::NUMBER, 'default'=>400]);
        $this->add_control('zoom', ['label'=>'Zoom', 'type'=>\Elementor\Controls_Manager::NUMBER, 'default'=>10]);
        $this->add_control('cluster', ['label'=>'Clustering', 'type'=>\Elementor\Controls_Manager::SWITCHER, 'return_value'=>'yes', 'default'=>'yes']);
        $this->add_control('zoom_control', ['label'=>'Zoom controls', 'type'=>\Elementor\Controls_Manager::SWITCHER, 'return_value'=>'yes', 'default'=>'yes']);
        $this->add_control('map_style', [
            'label'=>'Map Style',
            'type'=>\Elementor\Controls_Manager::SELECT,
            'options'=>['default'=>'Default','silver'=>'Silver','retro'=>'Retro','dark'=>'Dark','night'=>'Night','aubergine'=>'Aubergine','custom'=>'Custom JSON'],
            'default'=>'default'
        ]);
        $this->add_control('map_style_json', [
            'label'=>'Custom JSON',
            'type'=>\Elementor\Controls_Manager::TEXTAREA,
            'condition'=>['map_style'=>'custom']
        ]);
        $this->end_controls_section();

        $this->start_controls_section('section_locations',['label'=>'Locations']);
        $repeater = new \Elementor\Repeater();
        $repeater->add_control('title',['label'=>'Title','type'=>\Elementor\Controls_Manager::TEXT,'default'=>'Location']);
        $repeater->add_control('address',['label'=>'Address','type'=>\Elementor\Controls_Manager::TEXT]);
        $repeater->add_control('lat',['label'=>'Latitude','type'=>\Elementor\Controls_Manager::TEXT]);
        $repeater->add_control('lng',['label'=>'Longitude','type'=>\Elementor\Controls_Manager::TEXT]);
        $repeater->add_control('popup',['label'=>'Popup HTML','type'=>\Elementor\Controls_Manager::WYSIWYG]);
        $repeater->add_control('icon',['label'=>'SVG Icon','type'=>\Elementor\Controls_Manager::MEDIA]);
        $this->add_control('locations',[
            'label'=>'Map Locations','type'=>\Elementor\Controls_Manager::REPEATER,'fields'=>$repeater->get_controls(),'title_field'=>'{{{ title }}}'
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $locations = $settings['locations'] ?? [];

        $map_id = 'mwe-map-'.uniqid();
        $height = intval($settings['height'] ?? 400);

        $map_settings = [
            'zoom' => intval($settings['zoom'] ?? 10),
            'cluster' => ($settings['cluster'] ?? 'yes') === 'yes',
            'zoomControl' => ($settings['zoom_control'] ?? 'yes') === 'yes',
            'map_style' => $settings['map_style'] ?? 'default',
            'map_style_json' => $settings['map_style_json'] ?? '',
        ];


        $api_key = defined('MWEL_GOOGLE_MAPS_API_KEY') ? MWEL_GOOGLE_MAPS_API_KEY : '';

        wp_localize_script('mwe-map-init','MWE_DATA_'.$this->get_id(),[
            'locations' => $locations,
            'settings' => $map_settings,
            'apiKey' => $api_key
        ]);

        echo '<div id="'.esc_attr($map_id).'" class="mwe-map-container" data-localize="MWE_DATA_'.$this->get_id().'" style="width:100%;height:'.$height.'px"></div>';
    }


    protected function _content_template() {
		
        ?>
        <# var h = 100; #>
        <div class="mwe-map-container-placeholder" style="width:100%;height:{{ h }}px">Google map only frontend</div>

        <?php
    }
}
