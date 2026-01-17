<?php
if ( ! defined( 'ABSPATH' ) ) exit;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;

class Ebox_Info_Box extends Widget_Base {

    public function get_name() {
        return 'ebox_info_box';
    }

    public function get_title() {
        return __( 'Ebox Info Box', 'ebox' );
    }

    public function get_icon() {
        return 'eicon-info-box';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    public function get_style_depends() {
        return [ 'ebox-info-box' ];
    }

    protected function register_controls() {

        /* ================= CONTENT ================= */

        $this->start_controls_section(
            'section_content',
            [ 'label' => __( 'Content', 'ebox' ) ]
        );

        $this->add_control(
            'preset',
            [
                'label' => __( 'Preset', 'ebox' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'preset-5',
                'options' => [
                    'preset-1' => 'Preset 1',
                    'preset-2' => 'Preset 2',
                    'preset-3' => 'Preset 3',
                    'preset-4' => 'Preset 4',
                    'preset-5' => 'Preset 5',
                ],
            ]
        );

        $this->add_control(
            'media_type',
            [
                'label' => __( 'Media Type', 'ebox' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon'  => 'Icon',
                    'image' => 'Image',
                ],
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => __( 'Icon', 'ebox' ),
                'type' => Controls_Manager::ICONS,
                'condition' => [ 'media_type' => 'icon' ],
            ]
        );

        $this->add_control(
            'image',
            [
                'label' => __( 'Image', 'ebox' ),
                'type' => Controls_Manager::MEDIA,
                'condition' => [ 'media_type' => 'image' ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'ebox' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Info Box Title',
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'ebox' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Info box description goes here.',
            ]
        );

        $this->end_controls_section();

        /* ================= BUTTON ================= */

        $this->start_controls_section(
            'section_button',
            [ 'label' => __( 'Button', 'ebox' ) ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Text', 'ebox' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Read More',
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => __( 'Link', 'ebox' ),
                'type' => Controls_Manager::URL,
                'default' => [ 'url' => '#' ],
            ]
        );

        $this->add_control(
            'button_icon',
            [
                'label' => __( 'Icon', 'ebox' ),
                'type' => Controls_Manager::ICONS,
            ]
        );

        $this->add_control(
            'button_icon_position',
            [
                'label' => __( 'Icon Position', 'ebox' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'after',
                'options' => [
                    'before' => 'Before',
                    'after'  => 'After',
                ],
                'condition' => [ 'button_icon[value]!' => '' ],
            ]
        );

        $this->end_controls_section();

        /* ================= STYLE: MEDIA ================= */

        $this->start_controls_section(
            'style_media',
            [
                'label' => __( 'Media', 'ebox' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'media_size',
            [
                'label' => __( 'Size', 'ebox' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [ 'min' => 20, 'max' => 200 ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ebox-media img' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ebox-media svg' => 'width: {{SIZE}}{{UNIT}}; height:auto;',
                    '{{WRAPPER}} .ebox-media i'   => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /* ================= STYLE: TITLE ================= */

        $this->start_controls_section(
            'style_title',
            [
                'label' => __( 'Title', 'ebox' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'ebox' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ebox-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .ebox-title',
            ]
        );

        $this->end_controls_section();

        /* ================= STYLE: DESCRIPTION ================= */

        $this->start_controls_section(
            'style_description',
            [
                'label' => __( 'Description', 'ebox' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'desc_color',
            [
                'label' => __( 'Color', 'ebox' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ebox-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typography',
                'selector' => '{{WRAPPER}} .ebox-description',
            ]
        );

        $this->end_controls_section();

        /* ================= STYLE: BUTTON ================= */

        $this->start_controls_section(
            'style_button',
            [
                'label' => __( 'Button', 'ebox' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'button_tabs' );

        $this->start_controls_tab( 'button_normal', [ 'label' => 'Normal' ] );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __( 'Typography', 'ebox' ),
                'selector' => '{{WRAPPER}} .ebox-button',
            ]
        );


        $this->add_control(
            'button_color',
            [
                'label' => __( 'Text Color', 'ebox' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ebox-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg',
            [
                'label' => __( 'Background', 'ebox' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ebox-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab( 'button_hover', [ 'label' => 'Hover' ] );

        /* Typography for Hover */
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_hover_typography',
                'label' => __( 'Typography', 'ebox' ),
                'selector' => '{{WRAPPER}} .ebox-button:hover',
            ]
        );

        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Text Color', 'ebox' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ebox-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg',
            [
                'label' => __( 'Background', 'ebox' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ebox-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );



        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <div class="ebox-info-box <?php echo esc_attr( $s['preset'] ); ?>">

            <div class="ebox-title-wrapper">
                <div class="ebox-media">
                    <?php
                    if ( $s['media_type'] === 'icon' ) {
                        Icons_Manager::render_icon( $s['icon'], [ 'aria-hidden' => 'true' ] );
                    } elseif ( ! empty( $s['image']['url'] ) ) {
                        echo '<img src="'. esc_url( $s['image']['url'] ) .'">';
                    }
                    ?>
                </div>

                <h2 class="ebox-title"><?php echo esc_html( $s['title'] ); ?></h2>
            </div>

            <div class="ebox-description"><?php echo esc_html( $s['description'] ); ?></div>

            <?php if ( $s['button_text'] ) : ?>
                <a class="ebox-button" href="<?php echo esc_url( $s['button_link']['url'] ); ?>">
                    <?php if ( $s['button_icon_position'] === 'before' ) Icons_Manager::render_icon( $s['button_icon'] ); ?>
                    <span><?php echo esc_html( $s['button_text'] ); ?></span>
                    <?php if ( $s['button_icon_position'] === 'after' ) Icons_Manager::render_icon( $s['button_icon'] ); ?>
                </a>
            <?php endif; ?>

        </div>
        <?php
    }
}
