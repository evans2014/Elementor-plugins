<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class Hover_Card_Single_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'hover_card_single';
    }

    public function get_title() {
        return __( 'Hover Card Single', 'hover-card' );
    }

    public function get_icon() {
        return 'eicon-image-rollover';
    }

    public function get_categories() {
        return [ 'basic' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'card_section',
            [
                'label' => __( 'Hover Card Single', 'hover-card' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __( 'Image', 'hover-card' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'hover-card' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Card Title', 'hover-card' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'hover-card' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __( 'This is the description text.', 'hover-card' ),
            ]
        );

        $this->add_control(
            'overlay_color',
            [
                'label' => __( 'Overlay Color', 'hover-card' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.6)',
            ]
        );

        $this->add_control(
            'title_bar_top_color',
            [
                'label' => __( 'Title Bar Top Color', 'hover-card' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0)',
            ]
        );

        $this->add_control(
            'title_bar_bottom_color',
            [
                'label' => __( 'Title Bar Bottom Color', 'hover-card' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0,0,0,0.8)',
            ]
        );

        // Button 1
        $this->add_control(
            'button_1_text',
            [
                'label' => __( 'Button 1 Text', 'hover-card' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'София', 'hover-card' ),
            ]
        );

        $this->add_control(
            'button_1_link',
            [
                'label' => __( 'Button 1 Link', 'hover-card' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'hover-card' ),
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        // Button 2
        $this->add_control(
            'button_2_text',
            [
                'label' => __( 'Button 2 Text', 'hover-card' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Велико Търново', 'hover-card' ),
            ]
        );

        $this->add_control(
            'button_2_link',
            [
                'label' => __( 'Button 2 Link', 'hover-card' ),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __( 'https://your-link.com', 'hover-card' ),
                'default' => [
                    'url' => '',
                    'is_external' => false,
                    'nofollow' => false,
                ],
            ]
        );

        // Button 3
        $this->add_control(
            'button_3_text',
            [
                'label' => __( 'Button 3 Text', 'hover-card' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Научи повече', 'hover-card' ),
            ]
        );

		 $this->add_control(
			'button_3_link',
			[
				'label' => __( 'Button 3 Link', 'hover-card' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'hover-card' ),
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
				'dynamic' => [
					'active' => true, // <--- Това добавя динамични етикети
				],
			]
		);
        /*$this->add_responsive_control(
            'box_height',
            [
                'label' => __('Box Height', 'hover-reveal-widget'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 400,
                ],
                'selectors' => [
                    '{{WRAPPER}} .hover-box' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );*/

        $this->end_controls_section();
		$this->start_controls_section(
            'icon_section',
            [
                'label' => __( 'Corner Icon', 'hover-card' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'icon_default',
            [
                'label' => __( 'Default Icon', 'hover-card' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
               'default' => [], 
            ]
        );

        $this->add_control(
            'icon_hover',
            [
                'label' => __( 'Hover Icon', 'hover-card' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
               'default' => [], 
            ]
        );

        $this->end_controls_section();
		
    }

    protected function render() {

        wp_enqueue_style('hover-card-single-style');

        $card = $this->get_settings_for_display();

        echo '<div class="hover-card-wrapper">';
        ?>
        <div class="hover-card" >

            <img class="load-image" src="<?php echo esc_url($card['image']['url']); ?>" alt="<?php echo esc_attr($card['title']); ?>">
			 <div class="hover-card__icon">
                <?php if ( ! empty( $card['icon_default']['url'] ) ) : ?>
                    <img class="icon-default" src="<?php echo esc_url( $card['icon_default']['url'] ); ?>" alt="icon">
                <?php endif; ?>
                <?php if ( ! empty( $card['icon_hover']['url'] ) ) : ?>
                    <img class="icon-hover" src="<?php echo esc_url( $card['icon_hover']['url'] ); ?>" alt="icon hover">
                <?php endif; ?>
            </div>

            <div class="hover-card__title-bar" style="background: linear-gradient(to bottom, <?php echo esc_attr($card['title_bar_top_color']); ?>, <?php echo esc_attr($card['title_bar_bottom_color']); ?>);">
                <h5><?php echo esc_html($card['title']); ?></h5>
            </div>

            <div class="hover-card__overlay"
                 style="background: <?php echo esc_attr($card['overlay_color']); ?>;"
            >
                <h5><?php echo esc_html($card['title']); ?></h5>
                <?php echo $card['description']; ?>

                <div class="hover-card__buttons-wrapper">
                    <div class="hover-card__buttons-top">
                        <?php if (!empty($card['button_1_text']) && !empty($card['button_1_link']['url'])): ?>
                            <a class="hover-card__button" href="<?php echo esc_url($card['button_1_link']['url']); ?>"
                                <?php echo $card['button_1_link']['is_external'] ? 'target="_blank"' : ''; ?>
                                <?php echo $card['button_1_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>>
                                <?php echo esc_html($card['button_1_text']); ?>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($card['button_2_text']) && !empty($card['button_2_link']['url'])): ?>
                            <a class="hover-card__button" href="<?php echo esc_url($card['button_2_link']['url']); ?>"
                                <?php echo $card['button_2_link']['is_external'] ? 'target="_blank"' : ''; ?>
                                <?php echo $card['button_2_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>>
                                <?php echo esc_html($card['button_2_text']); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="hover-card__buttons-bottom">
                        <?php if ( ! empty( $card['button_3_link']['url'] ) ) : ?>
						<a class="hover-card__button_white" href="<?php echo esc_url( $card['button_3_link']['url'] ); ?>"  >
							<?php echo esc_html( $card['button_3_text'] ); ?>
						</a>
					<?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
        <?php
        echo '</div>';
    }
}
