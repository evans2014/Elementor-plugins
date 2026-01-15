<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Nahiro_Interactive_Map_Widget extends \Elementor\Widget_Base
{

    private static $dynamic_styles = '';
    // Your widget's name, title, icon and category
    public function get_name()
    {
        return 'nahiro_map_widget';
    }

    public function get_script_depends()
    {
        return ['nahiro_map_widget_js', 'map_editor_js',];
    }

    public function get_style_depends()
    {
        return ['nahiro_map_widget_css'];
    }

    public function get_title()
    {
        return __('Interactive Map Widget', 'interactive-map-widget');
    }

    public function get_icon()
    {
        return 'eicon-google-maps';
    }

    public function get_custom_help_url()
    {
        return 'https://nahiro.net/';
    }

    public function get_categories()
    {
        return ['nahiro_net','general'];
    }


    // Your widget's sidebar settings
    protected function register_controls()
    {
		
        $this->start_controls_section(
            'section_map',
            [
                'label' => __('Map', 'interactive-map-widget'),
            ]
        );
		$country = get_option('nahiro_country_option') ?? 'germany';
		 
		
        $this->add_control(
            'map_country',
            [
                'label' => __('Countries', 'interactive-map-widget'),
				//jo 01/06 hidden Control
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 'solid',
                'options' => [
                    $country => $country,
                ],
                'default' => $country, // Opción predeterminada
            ]
        );

        $this->add_control(
            'map_dimensions',
            [
                'label' => esc_html__('Map Dimensions', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'map_width_cont',
            [
                'label' => esc_html__('Container Width', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
				'description' => 'This control selects the width of the container of the map, it can be used to modify the height of the map when decreasing its value',
                'size_units' => ['px', '%', 'em', 'rem', 'custom'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    //'{{WRAPPER}} .map-contenedor' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'map_size_relation',
            [
                'label' => __('Map Relative Size (%)', 'interactive-map-widget'),
				'description' => 'This control modifies the size of the map in relation to the container width, to fill the container this should be at 100%',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 100,
                'min' => 0,
                'max' => 100,
                'step' => 1,
            ]
        );


       

        $this->add_control(
            'stroke_width',
            [
                'label' => __('Stroke Width', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 2,
                'selectors' => [
                    '{{WRAPPER}} svg' => 'stroke-width: {{VALUE}} !important;',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_pines',
            [
                'label' => __('Pins', 'interactive-map-widget'),
            ]
        );

        $this->add_control(
            'bound_velocity',
            [
                'label' => __('Bound Velocity (ms)', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1500,
                'selectors' => [
                	'{{WRAPPER}} .jo-animation' => 'animation-duration: {{VALUE}}ms;',
                ],
            ]
        );

       
        $this->add_control(
            'pines_display_shape',
            [
                'label' => __('Display Pin Shape', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Yes', 'interactive-map-widget'),
                    'no-xpin' => esc_html__('No', 'interactive-map-widget'),
                ],
            ]
        );
        $this->add_control(
            'pines_shape_color',
            [
                'label' => __('Pin Shape Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#89849b', // Default color
                'selectors' => [
                	'{{WRAPPER}} .xpin' => 'background: {{VALUE}}',
                ],
				'condition' => [
					//no es un error, no tocar
					'pines_display_shape' => '',
				],
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Pins Content', 'interactive-map-widget'),
            ]
        );
		$this->add_control(
            'display_pin1',
            [
                'label' => esc_html__('Display Pin N:1', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Yes', 'interactive-map-widget'),
                    'none' => esc_html__('No', 'interactive-map-widget'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-repeater-item-{{ID}}a :is(.jo-animation,.jo-pin-text)' => 'display: {{VALUE}} !important;',
                    '{{WRAPPER}} #\31 _c77eaaa' => 'display: {{VALUE}} !important;',
					
                ],
            ]
        );

     
        $this->add_control(
            'display_pin2',
            [
                'label' => esc_html__('Display Pin N:2', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Yes', 'interactive-map-widget'),
                    'none' => esc_html__('No', 'interactive-map-widget'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-repeater-item-{{ID}}b :is(.jo-animation,.jo-pin-text)' => 'display: {{VALUE}} !important;',
                    '{{WRAPPER}} #\32 _c77eaaa' => 'display: {{VALUE}} !important;',
					
                ],
            ]
        );
		
     
		$this->add_control(
			'pin_repeater_heading',
			[
				'label' => esc_html__( 'Pins', 'interactive-map-widget' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		

        $repeater = new \Elementor\Repeater();

        /*
        $repeater->add_control(
            'display_pin',
            [
                'label' => esc_html__('Display Pin', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Yes', 'interactive-map-widget'),
                    'none' => esc_html__('No', 'interactive-map-widget'),
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}  :is(.jo-animation, .jo-pin-text)' => 'display: {{VALUE}} !important;',
                ],
            ]
        );
        */


        $repeater->add_control(
            'cx',
            [
                'label' => __('Horizontal Position', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                 'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 200,
                ],
            ]
        );

        $repeater->add_control(
            'cy',
            [
                'label' => __('Vertical Position', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 400,
                ],
            ]
        );
		$repeater->add_control(
            'circle_name',
            [
                'label' => __('Pin Name', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Region Name', // Default name
            ]
        );
        

        


        $repeater->add_control(
            'left',
            [
                'label' => __('Position Left Name', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 0,
            ]
        );

        $repeater->add_control(
            'top',
            [
                'label' => __('Position Top Name', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 50,
            ]
        );

        $repeater->add_control(
            'circle_image',
            [
                'name' => 'circle_image',
                'label' => __('Pin Image', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
		
		$repeater->add_control(
            'circle_hide',
            [
                'label' => __('Pin Circle Hide', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'interactive-map-widget'),
                'label_off' => __('No', 'interactive-map-widget'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $repeater->add_control(
            'circle_color',
            [
                'label' => __('Pin Circle Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff', // Default color
				'condition' => [
					//no es un error, no tocar
					'circle_hide!' => 'yes',
				],
            ]
        );

        $repeater->add_control(
            'circle_ratio',
            [
                'label' => __('Pin Circle Ratio', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 3,
            ]
        );



        // anterior jo

        //FIN anterior JO

		$repeater->add_control(
			'card_repeater_heading',
			[
				'label' => esc_html__( 'Edit Card Content', 'interactive-map-widget' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $repeater->add_control(
            'people',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'person_name',
                        'label' => __('Name', 'interactive-map-widget'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => '',
                    ],
                    [
                        'name' => 'person_position',
                        'label' => __('Information', 'interactive-map-widget'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => '',
                    ],
                    [
                        'name' => 'person_email',
                        'label' => __('Email', 'interactive-map-widget'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => '',
                    ],
                    [
                        'name' => 'person_phone',
                        'label' => __('Phone', 'interactive-map-widget'),
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => '',
                    ],
                    [
                        'name' => 'person_image',
                        'label' => __('Photo', 'interactive-map-widget'),
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => \Elementor\Utils::get_placeholder_image_src(),
                        ],
                    ],
                ],
                'title_field' => '{{{ person_name }}}',
                'prevent_empty' => false,
				'item_actions' => [
                    'add' => false,
                    'duplicate' => false,
                    'remove' => false,
                    'sort' => false,
                ],
            ]
        );

        $this->add_control(
            'circle_coordinates',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'prevent_empty' => false,
                'title_field' => '{{{ circle_name }}}',
                'default' => [
                    [
                        'cx' => ['size'=>465],
                        'cy' =>  ['size'=>300],
                        'circle_hide' => 'yes',
                        'circle_color' => '#fff',
                        'circle_ratio' => 3,
                        'circle_name' => 'Alexander Plaza',
                        'left' => 0,
                        'top' => 70,
                        'circle_image' => ['url' => plugin_dir_url(__FILE__) . 'assets/img/alexanderplatz.jpg'],
                        'people' => [
                            [
                                'person_name' => 'Come Visit!',
                                'person_position' => '"Impresive architecture!"',
                                'person_email' => 'mail@mail.com',
                                'person_phone' => '+ 49 XX XXX XXX XXX',
                                'person_url' => '',
                                'person_image' => ['url' => plugin_dir_url(__FILE__) . 'assets/img/alexanderplatz.jpg']
                            ],
                        ]
                    ],
                    [
                        'cx' => ['size'=>246],
                        'cy' =>  ['size'=>197],
                        'circle_hide' => 'yes',
                        'circle_color' => '#fff',
                        'circle_ratio' => 3,
                        'circle_name' => 'Hamburg Port',
                        'left' => 0,
                        'top' => 70,
                        'circle_image' => ['url' => plugin_dir_url(__FILE__) . 'assets/img/hamburg-port.jpg'],
                        'people' => [
                            [
                                'person_name' => 'Bus tours everyday',
                                'person_position' => '"Five stars sightseeings!"',
                                'person_email' => 'mail@mail.com',
                                'person_phone' => '+49 XX XXX XXX XXX',
                                'person_url' => '',
                                'person_image' => ['url' => plugin_dir_url(__FILE__) . 'assets/img/hamburg-port.jpg']
                            ],
                        ]
                    ],
					//jo 01/06
					//fin jo 01/06
                ],
                'item_actions' => [
                    'add' => false,
                    'duplicate' => false,
                    'remove' => false,
                    'sort' => true,
                ],
            ]
        );
		
		
        $this->add_control(
            'nahiro-cta',
            [
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '
		<img class="nahiro-img" src="../wp-content/plugins/elementor/assets/images/go-pro.svg" alt="nahiro-net">
    <p class="nahiro-desc">Unlock More Pins, and more personalization options in pro version!</p>
    <a class="nahiro-map-btn" role="button" href="https://nahiro.net/wordpress-plugins/interaktive-karte-fuer-elementor-pro/" target="_blank"> Go Pro </a>',
                'content_classes' => 'your-class',
            ]
        );

        $this->end_controls_section();
		
		//MAP STYLES
		 $this->start_controls_section(
            'section_style_map',
            [
                'label' => __('Map', 'interactive-map-widget'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
		 $this->add_control(
            'map_color',
            [
                'label' => __('Map Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F1ECE9', // Default color
                'selectors' => [
                    '{{WRAPPER}} path' => 'fill: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'map_color_mouse_over',
            [
                'label' => __('Map Color Mouse Over Region', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#F1ECE9', // Default color
                'selectors' => [
                    '{{WRAPPER}} path:hover' => 'fill: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'stroke_color',
            [
                'label' => __('Stroke Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E7E2DF', // Default color
                'selectors' => [
                    '{{WRAPPER}} svg' => 'stroke: {{VALUE}} !important;',
                ],
            ]
        );
			 
		$this->end_controls_section();

        //JO NUEVO
        $this->start_controls_section(
            'section_style_pin',
            [
                'label' => esc_html__('Pin', 'interactive-map-widget'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        // JO pin controls

        $this->add_responsive_control(
            'pin_width',
            [
                'type' => \Elementor\Controls_Manager::SLIDER,
                'label' => esc_html__('Pin Size', 'textdomain'),
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 55,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 45,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 45,
                    'unit' => 'px',
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 55,
                ],
                //eliminado !important
                'selectors' => [
                    '{{WRAPPER}} .xpin' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .popupnb_class .xpin img.imgempnext' => 'width: calc({{SIZE}}{{UNIT}} / 1.33); height: calc({{SIZE}}{{UNIT}} / 1.33);',
                ],
            ]
        );

        $this->add_responsive_control(
            'pin_padding_top',
            [
                'label' => esc_html__('Pin Vertical Position', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                        'step' => 1,
                    ],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 4,
                    'unit' => 'px',
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .jo-animation' => 'translate: 0px {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		$this->add_control(
			'pin_style_text',
			[
				'label' => esc_html__( 'Pin Name Styles', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		 $this->add_responsive_control(
            'pin_name_vertical_position',
            [
                'label' => esc_html__('Pin Name Vertical Position', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                        'step' => 1,
                    ],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 4,
                    'unit' => 'px',
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .jo-pin-text' => 'translate: 0px {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pin_name_text',
            [
                'label' => esc_html__('Pin Name Text Size', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                        'step' => 1,
                    ],
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => [
                    'size' => 18,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 14,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 12,
                    'unit' => 'px',
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .jo-pin-text' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		 $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'pines_typography',
                'exclude' => [
                    'letter_spacing',
                    'word_spacing',
                    'line_height',
                    'default_generic_fonts'
                ],
                'selector' => '{{WRAPPER}} div.jo-pin-text',
            ]
        );
        $this->add_control(
            'pines_text_color',
            [
                'label' => __('Pin Name Color', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#837B72', // Default color
                'selectors' => [
                	'{{WRAPPER}} .popupnb_class .jo-pin-text' => 'color: {{VALUE}} !important',
                ],
            ]
        );
        
		
        //FIN JO pin controls


        $this->end_controls_section();
        //FIN JO NUEVO

		 $this->start_controls_section(
            'section_cards',
            [
                'label' => __('Cards', 'interactive-map-widget'),
            ]
        );
		 $this->add_control(
            'option_card',
            [
                'label' => __('Method to Hide Cards', 'interactive-map-widget'),
				'label_block' => true,
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'solid',
                'options' => [
                    'opcionA' => esc_html__('Pin Click', 'interactive-map-widget'),
                    'opcionB' => esc_html__('Scroll', 'interactive-map-widget'),
                    'opcionC' => esc_html__('Mouse Out', 'interactive-map-widget'),
                    'opcionD' => esc_html__('X Close', 'interactive-map-widget'),
                    'opcionE' => esc_html__('Pin Click & X Close', 'interactive-map-widget'),
                ],
                'default' => 'opcionE', // Opción predeterminada
            ]
        );

        $this->add_control(
            'card_width',
            [
                'label' => __('Card Width', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 258,
                'selectors' => [
                    '{{WRAPPER}} .cardnc' => 'width: {{VALUE}}px !important;',
                ],
            ]
        );

        $this->add_control(
            'card_height',
            [
                'label' => __('Card Height', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => 100,
                'selectors' => [
                    '{{WRAPPER}} .cardnc' => 'height: {{VALUE}}px !important;',
                    '{{WRAPPER}} .imagecrd' => 'height: {{VALUE}}px !important;',
                ],
            ]
        );
		
        $this->end_controls_section();
		
        $this->start_controls_section(
            'section_style_cards',
            [
                'label' => __('Cards', 'interactive-map-widget'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

       

        $this->add_control(
            'name_options',
            [
                'label' => esc_html__('Name Options', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'name_hide',
            [
                'label' => __('Show/Hide Name', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'interactive-map-widget'),
                'label_off' => __('Hide', 'interactive-map-widget'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'exclude' => [
                    'letter_spacing',
                    'word_spacing',
                    'line_height',
                    'default_generic_fonts'
                ],
                'selector' => '{{WRAPPER}} div.name_style',
            ]
        );

        $this->add_control(
            'position_options',
            [
                'label' => esc_html__('Position Options', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'position_hide',
            [
                'label' => __('Show/Hide Position', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'interactive-map-widget'),
                'label_off' => __('Hide', 'interactive-map-widget'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'position_typography',
                'exclude' => [
                    'letter_spacing',
                    'word_spacing',
                    'line_height',
                    'default_generic_fonts'
                ],
                'selector' => '{{WRAPPER}} div.position_style',
            ]
        );

        $this->add_control(
            'cel_options',
            [
                'label' => esc_html__('Telephone Options', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );


        $this->add_control(
            'cel_hide',
            [
                'label' => __('Show/Hide Telephone', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'interactive-map-widget'),
                'label_off' => __('Hide', 'interactive-map-widget'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'cel_typography',
                'exclude' => [
                    'letter_spacing',
                    'word_spacing',
                    'line_height',
                    'default_generic_fonts'
                ],
                'selectors' => [
                    '{{WRAPPER}} div.cel_style a',
                    '{{WRAPPER}} div.cel_style a:hover',
                ]
            ]
        );

        $this->add_control(
            'email_options',
            [
                'label' => esc_html__('Email Options', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'email_hide',
            [
                'label' => __('Show/Hide Email', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'interactive-map-widget'),
                'label_off' => __('Hide', 'interactive-map-widget'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'email_typography',
                'exclude' => [
                    'letter_spacing',
                    'word_spacing',
                    'line_height',
                    'default_generic_fonts'
                ],
                'selectors' => [
                    '{{WRAPPER}} div.email_style a',
                    '{{WRAPPER}} div.email_style a:hover',
                ]
            ]
        );

        $this->add_control(
            'pic_options',
            [
                'label' => esc_html__('Picture Options', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'pic_hide',
            [
                'label' => __('Show/Hide Picture', 'interactive-map-widget'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'interactive-map-widget'),
                'label_off' => __('Hide', 'interactive-map-widget'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control('pic_position', [
            'label' => esc_html__('Picture Position', 'interactive-map-widget'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'left',
            'options' => [
                'left' => esc_html__('Left', 'interactive-map-widget'),
                'right' => esc_html__('Right', 'interactive-map-widget'),
            ],
        ]);

        $this->end_controls_section();

    }


    protected function render()
{
    $settings = $this->get_settings_for_display();
    $circle_coordinates = $settings['circle_coordinates'];
    $widget = $this->get_data();
    $un_id = $widget['id'];
    $jo_pin_text_color = isset($settings['pines_text_color']) 
                            ? sanitize_hex_color($settings['pines_text_color']):'';
    $bound_velocity='';
    if(isset($settings['bound_velocity'])){
        $bound_velocity = $settings['bound_velocity'] / 1000;
    }
    $display_pin_shape = $settings['pines_display_shape']; //predefined values no need to sanitize
    $pin_shape_color = isset($settings['pines_shape_color']) 
                        ? sanitize_hex_color($settings['pines_shape_color']) : '';
    $pin_size = isset($settings['pin_width']['size']) 
                    ? intval($settings['pin_width']['size']) : 50;
    //jo 7 may
    $map_size = $settings['map_width_cont']['size'];
    $map_unit = $settings['map_width_cont']['unit'];
    
    //21 jun edw
    $percent = 1 + ((100 - $settings['map_size_relation']) / 1000);
    //fin 20 jun edw
    
    //jo 01/06
    $country = get_option('nahiro_country_option') ?? 'germany';
    $settings["map_country"] = $country;
    //fin jo 01/06

    self::$dynamic_styles = "
    .xpin {
        width: " . esc_attr($pin_size) . "px;
        height: " . esc_attr($pin_size) . "px;
        border-radius: 50% 50% 50% 0;
        background: " . esc_attr($pin_shape_color) . ";
        display: flex; 
        flex-direction: row;
        justify-content: center;
        align-items: center;
        -webkit-transform: rotate(-45deg);
        transform: rotate(-45deg);
        translate: 0 -0.2em;
    }
    .no-xpin {
        background: transparent !important;
        border: 0px;
    }
    @media (max-width:767px) {
        .zxpin {
            translate: 0 -1em;
        }
    }
    .xpin > img {
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg);
    }
    .elementor-widget-container .popupnb_class .xpin img.imgempnext {
        width: calc(" . esc_attr($pin_size) . "px / 1.33);
        height: calc(" . esc_attr($pin_size) . "px / 1.33);
        border-radius: 100%;
        padding-top: 0 !important;
    }
    .elementor-widget-container .popupnb_class .no-xpin img.imgempnext {
        border-radius: 1%;
    }
    .jo-animation {
        -webkit-animation-duration: " . esc_attr($bound_velocity) . "s;
        animation-duration: " . esc_attr($bound_velocity) . "s;
        -webkit-animation-iteration-count: infinite;
        animation-iteration-count: infinite;
        -webkit-animation-timing-function: ease-in-out;
        animation-timing-function: ease-in-out;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        justify-content: center;
    }
    @-webkit-keyframes reboteInfinito {
        0% {
            -webkit-transform: translateY(0.8em);
        }
        50% {
            -webkit-transform: translateY(0);
        }
        100% {
            -webkit-transform: translateY(0.8em);
        }
    }
    .reboteInfinito {
        -webkit-animation-name: reboteInfinito;
        animation-name: reboteInfinito;
    }
    .popupnb_class .jo-pin-text {
        color: " . esc_attr($jo_pin_text_color) . ";
    }
     .cardnc .email_style {
        margin-top: 4px;
    }
    .cardnc {
        border-radius: 4px;
        overflow: hidden;
        box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    }
    .elementor-widget-nahiro_map_widget .map-contenedor {
        width:" . esc_attr($map_size) . esc_attr($map_unit) .";
        display: block;
    }
    body.elementor-editor-active .map-contenedor {
        display: block;
    }
    @media (max-width: 767px) {
       .elementor-widget-nahiro_map_widget .map-contenedor {
            min-height: 90vh;
            width: 100%;
            margin: 0px;
        }
    }";

    if (!empty($display_pin_shape)) {
        echo '<input type="hidden" id="nahiro-hide-pin" value="no-xpin">';
    }

		
		// Only render regionSelector control in Elementor editor
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {
             echo '<div id="regionSelector" class="my-custom-widget">
			  <div id="regionSelectorMove">↔</div>
                <div class="nh_cord control-box" >
					<div class="title">Position</div>
                    <div class="coordinates">
						<div class="x-cord"><kbd><span class="icon">⇄</span>X </kbd> <span class="h-cord"></span></div>
						<div class="y-cord"><kbd><span class="icon">⇅</span>Y </kbd> <span class="v-cord"></span></div>
					</div>
                </div>
				<div class="nh-select-cont">
					<select class="control-box"></select>
				</div>
            </div>';
        }

    ?>

    <!--20 jun edw-->
    <div circlecoordinates="<?php echo esc_attr(wp_json_encode($circle_coordinates)); ?>" optioncard="<?php echo esc_attr($settings['option_card']); ?>" class="custom_map_wrap" percent="<?php echo esc_attr($percent); ?>" id="<?php echo esc_attr($un_id); ?>">
    <!--fin 20 jun edw-->

    <!--esc_attr agregadoo-->
    <div attrid="<?php echo esc_attr($un_id); ?>" class="cardnc" style="z-index:10000;" id="cardn_<?php echo esc_attr($un_id); ?>">
        <div class="close-btn"
            style="width:12px; position: absolute;top: -3px;right: 5px;cursor: pointer;color: black;font-weight:bold; padding: 1px;z-index: 11;"><img
                src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/img/Close_Icon_Black.svg')?>" /> </div>
        <?php if (!$settings["pic_hide"]) { ?>
            <div class="text"
                style="padding-top:10px; padding-left:10px;left:0%; width: 90%; position:absolute;top: 0;display: flex;flex-direction: column;justify-content: space-between;">
        <?php } else { ?>
            <?php if ($settings["pic_position"] == "left") { ?>
                <div class="text"
                    style="padding-top:10px; padding-left:10px;left:30%; width: 60%; position:absolute;top: 0;display: flex;flex-direction: column;justify-content: space-between;">
                <?php } else { ?>
                    <div class="text"
                        style="padding-top:10px; padding-left:1px;padding-right:10px;right:38%; width: 60%; position:absolute;top: 0;display: flex;flex-direction: column;justify-content: space-between;">
                <?php } ?>
            <?php } ?>

            <a id="aW_<?php echo esc_attr($un_id); ?>" href=""><?php if ($settings["name_hide"] == "yes") { ?>
                    <div style="text-align:left;line-height:1" class="name_style" id="nameW_<?php echo esc_attr($un_id); ?>">
                    </div><?php } ?>
                <?php if ($settings["position_hide"] == "yes") { ?>
                    <div class="position_style" style="text-align:left;line-height:1"
                        id="positionW_<?php echo esc_attr($un_id); ?>"></div><?php } ?>
            </a>
            <?php if ($settings["cel_hide"] == "yes") { ?>
                <div class="cel_style" style="margin-top:10px; text-align:left;line-height:1"
                    id="celW_<?php echo esc_attr($un_id); ?>"></div><?php } ?>
            <?php if ($settings["email_hide"] == "yes") { ?>
                <div class="email_style" style="text-align:left;line-height:1" id="emailW_<?php echo esc_attr($un_id); ?>"></div>
            <?php } ?>
        </div>
        <?php if (!$settings["pic_hide"]) { ?>
            <div class="imagecrd" style="height:100px; position: absolute; top: 0; left: 0; width: <?php echo '0%'; ?>">
            </div>
        <?php } else { ?>
            <?php if ($settings["pic_position"] == "left") { ?>
                <div class="imagecrd"
                    style="height:100px; position: absolute; top: 0; left: 0; width: <?php echo '30%'; ?>">
                </div>
            <?php } else { ?>
                <div class="imagecrd"
                    style="height:100px; position: absolute; top: 0; right: 0; width: <?php echo '30%'; ?>">
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <div class="popupnb_class" id="popupnb_<?php echo esc_attr($un_id); ?>"></div>
    <!-- INICIO DIV CONTENEDOR -->
    <div class="map-contenedor" id="nh-map-cont" style="width:<?php echo esc_attr($settings['map_width_cont']['size']) . esc_attr($settings['map_width_cont']['unit']);?>">
        <?php
        $dom = new DOMDocument();

        // Habilitar la manipulación de HTML como XML (para manejar etiquetas incorrectamente cerradas)
        libxml_use_internal_errors(true);

        // Cargar el contenido HTML desde la URL
        $dom->loadHTMLFile(plugin_dir_url(__FILE__) . 'assets/svgs/' . $settings["map_country"] . '.svg');

        // Crear una instancia de DOMXPath para buscar elementos
        $xpath = new DOMXPath($dom);

        // Encontrar el elemento SVG en la página
        $svgNodeList = $xpath->query('//svg');

        // Verificar si se encontró el elemento SVG
        if ($svgNodeList->length > 0) {
            // Obtener el primer elemento SVG encontrado
            $svgElement = $svgNodeList->item(0);

            // Establecer los nuevos atributos en el elemento SVG
            $svgElement->setAttribute('id', 'mapaalemaniab_' . $un_id);
            $svgElement->setAttribute('baseprofile', 'tiny');
            $svgElement->setAttribute('fill', '#7c7c7c');
            $svgElement->setAttribute('stroke', '#E7E2DF');
            $svgElement->setAttribute('stroke-linecap', 'round');
            $svgElement->setAttribute('stroke-linejoin', 'round');
            $svgElement->setAttribute('stroke-width', '2');
            $svgElement->setAttribute('version', '1.2');
            $svgElement->setAttribute('class', 'mapMain');

            // Obtener el contenido SVG como una cadena
            $svgContent = $dom->saveXML($svgElement);

            // Imprimir el contenido SVG con la etiqueta de cierre eliminada
            $svgContent = substr($svgContent, 0, -6);
            echo wp_kses($svgContent,
                    array(
                        'svg' => array(
                            'xmlns' => array(),
                            'id' => array(),
                            'baseProfile' => array(),
                            'fill' => array(),
                            'stroke' => array(),
                            'stroke-linecap' => array(),
                            'stroke-linejoin' => array(),
                            'stroke-width' => array(),
                            'version' => array(),
                            'class' => array(),
                            'viewBox' => array(),
                        ),
                        'path' => array(
                            'id' => array(),
                            'title' => array(),
                            'class' => array(),
                            'd' => array(),
                        ),
                        'circle' => array(
                            'style' => array(),
                            'id' => array(),
                            'cx' => array(),
                            'cy' => array(),
                            'r' => array(),
                            'fill' => array(),
                        )
                    ),
                );
        } else {
            echo esc_html__('No se encontró el SVG en la página.', 'interactive-map-widget');
        }

        $index_ori = 1;
        //jo 6 may
        $arr_elementor_id = array();
        $arr_colors = array();
        $arr_coordinates = array();
        foreach ($circle_coordinates as $coordinate) {
            $circle_x = esc_js($coordinate['cx']['size']);
            $circle_y = esc_js($coordinate['cy']['size']);

            if ($coordinate['circle_hide'] == 'yes') {
                $circle_color = 'transparent';
                $circle_radius = '1';
            } else {
                $circle_color = sanitize_hex_color($coordinate['circle_color']);
                $circle_radius = absint($coordinate['circle_ratio']);
            }

            if (!isset($circle_radius) || $circle_radius == "") {
                $circle_radius = '1';
                $circle_color = 'transparent';
            }
            if ($circle_radius == "0") {
                $circle_radius = '1';
                $circle_color = 'transparent';
            }
            //jo 6 may
            $arr_elementor_id[] = $coordinate['_id'];
            $arr_colors[] = $circle_color;
            $arr_coordinates[] = array(
                'cx' => $circle_x, 
                'cy' => $circle_y,
                'r' => $circle_radius
            );

            $circle = '<circle style="stroke:' . esc_attr($circle_color) . ';" id="' . esc_attr($index_ori . '_' . $un_id) . '" cx="' . esc_attr($circle_x) . '" cy="' . esc_attr($circle_y) . '" r="' . esc_attr($circle_radius) . '" stroke="' . esc_attr($circle_color) . '" fill="' . esc_attr($circle_color) . '" />';

            $index_ori = $index_ori + 1;
        }
        $this->add_render_attribute(
                'circle_one',
                [
                    'id' => esc_attr('1_' . $un_id),
                    'cx' => $arr_coordinates[0]['cx'],
                    'cy' => $arr_coordinates[0]['cy'],
                    'r' => $arr_coordinates[0]['r'],
                    'stroke' => esc_attr($arr_colors[0]),
                    'stroke-width' => esc_attr($arr_colors[0]),
                    'fill' => esc_attr($arr_colors[0]),
                ]
            );
            $this->add_render_attribute(
                'circle_two',
                [
                    'id' => esc_attr('2_' . $un_id),
                    'cx' => $arr_coordinates[1]['cx'],
                    'cy' => $arr_coordinates[1]['cy'],
                    'r' => $arr_coordinates[1]['r'],
                    'stroke' => esc_attr($arr_colors[1]),
                    'stroke-width' => esc_attr($arr_colors[1]),
                    'fill' => esc_attr($arr_colors[1]),
                ]
            );

        ?>
        <circle <?php esc_attr($this->print_render_attribute_string('circle_one')); ?>/>
        <circle <?php esc_attr($this->print_render_attribute_string('circle_two')); ?>/>
        </svg>
        <!-- FIN DIV CONTENEDOR-->
    </div>
    <?php

    // Calcular el valor de percent en PHP
    $percent = 1 + ((100 - $settings['map_size_relation']) / 1000);
    //$percent = 1.01;
}


    public static function add_dynamic_styles() {
        if (!empty(self::$dynamic_styles)) {
            $dont_allow_tags = [];
            wp_add_inline_style('nahiro_map_dynamic_css', wp_kses(self::$dynamic_styles, $dont_allow_tags) );
        }
    }


}

add_action('wp_enqueue_scripts', ['Nahiro_Interactive_Map_Widget', 'add_dynamic_styles']);