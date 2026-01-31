<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EHC_Hover_Card_Widget extends Widget_Base {

    public function get_name() {
        return 'ehc_hover_card';
    }

    public function get_title() {
        return __( 'EHC Hover Card', 'ehc-hover-card' );
    }

    public function get_icon() {
        return 'eicon-post-slider';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Content', 'ehc-hover-card' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'ehc-hover-card' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'In Vitro Lab, evaluation of biological activity and toxicity', 'ehc-hover-card' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description1',
            [
                'label' => __( 'Short Description', 'ehc-hover-card' ),
                'type' => Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => __( 'Лаборатория за разработване и тестване...', 'ehc-hover-card' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description2',
            [
                'label' => __( 'Long Description (on hover)', 'ehc-hover-card' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __( 'Content', 'ehc-hover-card' ),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

       /* $this->add_control(
            'svg_icon',
            [
                'label' => __( 'SVG Icon (HTML)', 'ehc-hover-card' ),
                'type' => Controls_Manager::CODE,
                'language' => 'html',
                'rows' => 5,
                'default' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5M2 12v5c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-5M2 17v2c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-2M12 11v3"/></svg>',
                'label_block' => true,
            ]
        );*/

        $this->add_control(
            'icon',
            [
                'label' => __( 'SVG Icon', 'ehc-hover-card' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-flask',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', 'ehc-hover-card' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Вижте още', 'ehc-hover-card' ),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => __( 'Link', 'ehc-hover-card' ),
                'type' => Controls_Manager::URL,
                'default' => [ 'url' => '#' ],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        // === Background Style ===
        $this->start_controls_section(
            'style_background',
            [
                'label' => __( 'Background', 'ehc-hover-card' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_image_custom',
            [
                'label' => __( 'Background Image', 'ehc-hover-card' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [],
            ]
        );

        $this->add_control(
            'bg_color_normal',
            [
                'label' => __( 'Overlay Color (Normal)', 'ehc-hover-card' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,34,26,0.8)',
            ]
        );

        $this->add_control(
            'bg_color_hover',
            [
                'label' => __( 'Overlay Color (Hover)', 'ehc-hover-card' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0,20,15,0.9)',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $title = $settings['title'];
        $desc1 = $settings['description1'];
        $desc2 = $settings['description2'];
        //$svg_icon = $settings['svg_icon'];
        $button_text = $settings['button_text'];
        $link_url = ! empty( $settings['link']['url'] ) ? $settings['link']['url'] : '#';
        $icon = $settings['icon'];
        $bg_image_url = ! empty( $settings['background_image_custom']['url'] ) ? $settings['background_image_custom']['url'] : '';

        $card_style = '';
        if ( $bg_image_url ) {
            $card_style = 'background-image: url(\'' . esc_url( $bg_image_url ) . '\'); background-size: cover; background-position: center;';
        }

        $bg_normal = sanitize_text_field( $settings['bg_color_normal'] );
        $bg_hover = sanitize_text_field( $settings['bg_color_hover'] );

        $widget_id = $this->get_id();
        $wrapper_class = 'ehc-hover-card-wrapper-' . $widget_id;
        ?>

        <!-- <div class="<?php echo esc_attr( $wrapper_class ); ?>">
            <div
                    class="ehc-card"
                    style="<?php echo esc_attr( $card_style ); ?>"
                    data-link="<?php echo esc_url( $link_url ); ?>"
                    data-bg-normal="<?php echo esc_attr( $bg_normal ); ?>"
                    data-bg-hover="<?php echo esc_attr( $bg_hover ); ?>"
            >
                <div class="ehc-logo">
                    <?php
                    if ( ! empty( $settings['icon'] ) ) {
                        \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                    }
                    ?>
                </div>

                <div class="ehc-content">
                    <div class="ehc-title"><?php echo esc_html( $title ); ?></div>
                    <div class="ehc-descriptions">
                        <div class="ehc-description ehc-short"><?php echo esc_html( $desc1 ); ?></div>
                        <div class="ehc-description ehc-long"><?php echo wp_kses_post( $settings['description2'] ); ?></div>
                    </div>
                </div>

                <a href="<?php echo esc_url( $link_url ); ?>" class="ehc-button ehc-small" aria-hidden="true">
                   <svg xmlns="http://www.w3.org/2000/svg" width="16.914" height="25.828" viewBox="0 0 16.914 25.828">
					<g id="Group_100" data-name="Group 100" transform="translate(-5668 2129.914) rotate(180)">
					<g id="Ellipse_4" data-name="Ellipse 4" transform="translate(-5676 2113)" fill="none" stroke-width="2" stroke="#fff">
					<circle cx="4" cy="4" r="4" stroke="none"/>
					<circle cx="4" cy="4" r="3" fill="none"/></g>
					<line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(-5683.5 2105.5)" fill="none" stroke-linecap="round" stroke-width="2" stroke="#fff"/>
					<line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(-5683.5 2119.5)" fill="none" stroke-linecap="round" stroke-width="2" stroke="#fff"/>
					</g>
					</svg>
                </a>

                <a href="<?php echo esc_url( $link_url ); ?>" class="ehc-button ehc-large" aria-hidden="true">
                    <?php echo esc_html( $button_text ); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16.914" height="25.828" viewBox="0 0 16.914 25.828">
						<g id="Group_100" data-name="Group 100" transform="translate(-5668 2129.914) rotate(180)">
						<g id="Ellipse_4" data-name="Ellipse 4" transform="translate(-5676 2113)" fill="none" stroke-width="2" stroke="#fff">
						<circle cx="4" cy="4" r="4" stroke="none"/>
						<circle cx="4" cy="4" r="3" fill="none"/></g>
						<line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(-5683.5 2105.5)" fill="none" stroke-linecap="round" stroke-width="2" stroke="#fff"/>
						<line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(-5683.5 2119.5)" fill="none" stroke-linecap="round" stroke-width="2" stroke="#fff"/>
						</g>
						</svg>
                </a>
            </div>
        </div> -->
		<div class="<?php echo esc_attr( $wrapper_class ); ?>">
            <a href="<?php echo esc_url( $link_url ); ?>" class="ehc-card-link">
                <div class="ehc-card"
                     style="<?php echo esc_attr( $card_style ); ?>"
                     data-link="<?php echo esc_url( $link_url ); ?>"
                     data-bg-normal="<?php echo esc_attr( $bg_normal ); ?>"
                     data-bg-hover="<?php echo esc_attr( $bg_hover ); ?>"
                >
                    <div class="ehc-logo">
                        <?php
                        if ( ! empty( $settings['icon'] ) ) {
                            \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                        }
                        ?>
                    </div>

                    <div class="ehc-content">
                        <div class="ehc-title"><?php echo esc_html( $title ); ?></div>
                        <div class="ehc-descriptions">
                            <div class="ehc-description ehc-short"><?php echo esc_html( $desc1 ); ?></div>
                            <div class="ehc-description ehc-long"><?php echo wp_kses_post( $settings['description2'] ); ?></div>
                        </div>
                    </div>
					<div class="ehc-button ehc-small" aria-hidden="true">
					   <svg xmlns="http://www.w3.org/2000/svg" width="16.914" height="25.828" viewBox="0 0 16.914 25.828">
						<g id="Group_100" data-name="Group 100" transform="translate(-5668 2129.914) rotate(180)">
						<g id="Ellipse_4" data-name="Ellipse 4" transform="translate(-5676 2113)" fill="none" stroke-width="2" stroke="#fff">
						<circle cx="4" cy="4" r="4" stroke="none"/>
						<circle cx="4" cy="4" r="3" fill="none"/></g>
						<line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(-5683.5 2105.5)" fill="none" stroke-linecap="round" stroke-width="2" stroke="#fff"/>
						<line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(-5683.5 2119.5)" fill="none" stroke-linecap="round" stroke-width="2" stroke="#fff"/>
						</g>
						</svg>
					</div>
                    <div class="ehc-button ehc-large" aria-hidden="true">
                        <?php echo esc_html( $button_text ); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16.914" height="25.828" viewBox="0 0 16.914 25.828">
                            <g id="Group_100" data-name="Group 100" transform="translate(-5668 2129.914) rotate(180)">
                                <g id="Ellipse_4" data-name="Ellipse 4" transform="translate(-5676 2113)" fill="none" stroke-width="2" stroke="#fff">
                                    <circle cx="4" cy="4" r="4" stroke="none"/>
                                    <circle cx="4" cy="4" r="3" fill="none"/>
                                </g>
                                <line id="Line_3" x1="9" y2="9" transform="translate(-5683.5 2105.5)" stroke-linecap="round" stroke-width="2" stroke="#fff"/>
                                <line id="Line_4" x1="9" y1="9" transform="translate(-5683.5 2119.5)" stroke-linecap="round" stroke-width="2" stroke="#fff"/>
                            </g>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
        <?php
    }
}