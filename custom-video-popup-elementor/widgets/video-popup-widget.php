<?php
namespace CVP\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit;

class Video_Popup_Widget extends Widget_Base {

    public function get_name() {
        return 'cvp_video_popup';
    }

    public function get_title() {
        return __( 'Video Popup', 'cvp-elementor' );
    }

    public function get_icon() {
        return 'eicon-youtube';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function register_controls() {

        // === Видео линк ===
        $this->start_controls_section(
            'section_video',
            [
                'label' => __( 'Video', 'cvp-elementor' ),
            ]
        );

        $this->add_control(
            'video_url',
            [
                'label' => __( 'Video URL', 'cvp-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'https://www.youtube.com/watch?v=... or .mp4 link',
                'default' => 'https://www.youtube.com/watch?v=iyZyl-rTGOc',
            ]
        );

        $this->end_controls_section();

        // === Thumbnail ===
        $this->start_controls_section(
            'section_thumbnail',
            [
                'label' => __( 'Thumbnail', 'cvp-elementor' ),
            ]
        );

        $this->add_control(
            'thumb_type',
            [
                'label' => __( 'Type', 'cvp-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'image',
                'options' => [
                    'image' => __( 'Image', 'cvp-elementor' ),
                    'svg'   => __( 'SVG', 'cvp-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'thumb_image',
            [
                'label' => __( 'Choose Image', 'cvp-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [],
                'condition' => [ 'thumb_type' => 'image' ],
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumb_image_size',
                'default' => 'large',
                'separator' => 'none',
                'condition' => [ 'thumb_type' => 'image' ],
            ]
        );

        $this->add_control(
            'thumb_svg',
            [
                'label' => __( 'SVG Code', 'cvp-elementor' ),
                'type' => Controls_Manager::CODE,
                'language' => 'html',
                'rows' => 10,
                'condition' => [ 'thumb_type' => 'svg' ],
            ]
        );

        $this->end_controls_section();

        // === Play Button ===
        $this->start_controls_section(
            'section_play_button',
            [
                'label' => __( 'Play Button', 'cvp-elementor' ),
            ]
        );

        $this->add_control(
            'play_btn_type',
            [
                'label' => __( 'Type', 'cvp-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __( 'Default Icon', 'cvp-elementor' ),
                    'image'   => __( 'Image', 'cvp-elementor' ),
                    'svg'     => __( 'SVG', 'cvp-elementor' ),
                    'none'    => __( 'None', 'cvp-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'play_btn_image',
            [
                'label' => __( 'Button Image', 'cvp-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'condition' => [ 'play_btn_type' => 'image' ],
            ]
        );

        $this->add_control(
            'play_btn_svg',
            [
                'label' => __( 'SVG Code', 'cvp-elementor' ),
                'type' => Controls_Manager::CODE,
                'language' => 'html',
                'rows' => 5,
                'condition' => [ 'play_btn_type' => 'svg' ],
            ]
        );

        $this->add_responsive_control(
            'play_btn_size',
            [
                'label' => __( 'Size (px)', 'cvp-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [ 'min' => 20, 'max' => 200 ],
                ],
                'default' => [ 'size' => 80 ],
                'selectors' => [
                    '{{WRAPPER}} .cvp-custom-play-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [ 'play_btn_type' => [ 'image', 'svg' ] ],
            ]
        );

        $this->end_controls_section();

        // === Popup Settings ===
        $this->start_controls_section(
            'section_popup',
            [
                'label' => __( 'Popup Settings', 'cvp-elementor' ),
            ]
        );

        $this->add_control(
            'enable_popup',
            [
                'label' => __( 'Enable Popup', 'cvp-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __( 'Yes', 'cvp-elementor' ),
                'label_off' => __( 'No', 'cvp-elementor' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $video_url = trim( $settings['video_url'] ?? '' );
        if ( ! $video_url ) return;

        $unique_id = 'cvp-' . $this->get_id();
        $is_popup = $settings['enable_popup'] === 'yes';

        $is_youtube = $is_vimeo = $is_mp4 = false;
        $video_id = '';

        if ( preg_match( '/youtube\.com|youtu\.be/', $video_url ) ) {
            $is_youtube = true;
            if ( preg_match( '%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match ) ) {
                $video_id = $match[1];
            }
        } elseif ( strpos( $video_url, 'vimeo.com' ) !== false ) {
            $is_vimeo = true;
            if ( preg_match( '/vimeo\.com\/(\d+)/', $video_url, $match ) ) {
                $video_id = $match[1];
            }
        } elseif ( preg_match( '/\.mp4(\?.*)?$/', $video_url ) ) {
            $is_mp4 = true;
        }

        // === THUMBNAIL ===
        $thumb_html = '';
        if ( $settings['thumb_type'] === 'image' && ! empty( $settings['thumb_image']['url'] ) ) {
            $thumb_img = wp_get_attachment_image(
                $settings['thumb_image']['id'],
                $settings['thumb_image_size_size'] ?? 'large',
                false,
                [ 'style' => 'position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; display:block;' ]
            );
            $thumb_html = $thumb_img;
        } elseif ( $settings['thumb_type'] === 'svg' && ! empty( $settings['thumb_svg'] ) ) {
            $allowed = [
                'svg' => [ 'xmlns' => [], 'viewBox' => [], 'width' => [], 'height' => [] ],
                'path' => [ 'd' => [], 'fill' => [] ],
                'circle' => [ 'cx' => [], 'cy' => [], 'r' => [], 'fill' => [] ],
            ];
            $svg = wp_kses( $settings['thumb_svg'], $allowed );
            $thumb_html = '<div style="position:absolute; top:0; left:0; width:100%; height:100%;">' . $svg . '</div>';
        } else {
            // Fallback thumbnail
            if ( $video_id && $is_youtube ) {

                $thumb_html = '<img src="https://i.ytimg.com/vi/' . esc_attr( $video_id ) . '/hqdefault.jpg" style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; display:block;">';
            } else {
                $thumb_html = '<div style="background:#333; position:absolute; top:0; left:0; width:100%; height:100%; display:flex;align-items:center;justify-content:center;color:white;">Video</div>';
            }
        }

        // === PLAY BUTTON ===
        $play_btn_html = '';
        if ( $settings['play_btn_type'] === 'image' && ! empty( $settings['play_btn_image']['url'] ) ) {
            $size = ! empty( $settings['play_btn_size']['size'] ) ? $settings['play_btn_size']['size'] : 80;
            $play_btn_html = '<img src="' . esc_url( $settings['play_btn_image']['url'] ) . '" class="cvp-custom-play-btn" style="width:' . $size . 'px; height:auto;">';
        } elseif ( $settings['play_btn_type'] === 'svg' && ! empty( $settings['play_btn_svg'] ) ) {
            $allowed = [ 'svg' => [ 'width' => [], 'height' => [] ], 'path' => [ 'd' => [], 'fill' => [] ] ];
            $svg = wp_kses( $settings['play_btn_svg'], $allowed );
            $size = ! empty( $settings['play_btn_size']['size'] ) ? $settings['play_btn_size']['size'] : 80;
            $play_btn_html = str_replace( '<svg', '<svg style="width:' . $size . 'px; height:' . $size . 'px;"', $svg );
        } elseif ( $settings['play_btn_type'] === 'default' ) {
                     $play_btn_html = '<img src="' . plugins_url('custom-video-popup-elementor/assets/images/pl.svg', dirname(__FILE__, 2)) . '" 
                       alt="Play" 
                       style="width:120px; height:auto; pointer-events:none;">';
        }

        if ( $play_btn_html && $settings['play_btn_type'] !== 'none' ) {
            $play_btn_html = '<div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); z-index:2; pointer-events:none;">' . $play_btn_html . '</div>';
        }

        // === INLINE MODE ===
        if ( ! $is_popup ) {
            if ( $is_youtube && $video_id ) {
                echo '<iframe src="https://www.youtube.com/embed/' . $video_id . '" width="100%" height="400" frameborder="0" allowfullscreen></iframe>';
            } elseif ( $is_vimeo && $video_id ) {
                echo '<iframe src="https://player.vimeo.com/video/' . $video_id . '" width="100%" height="400" frameborder="0" allowfullscreen></iframe>';
            } elseif ( $is_mp4 ) {
                echo '<video src="' . esc_url( $video_url ) . '" controls style="width:100%;"></video>';
            } else {
                echo '<div>Unsupported video format</div>';
            }
            return;
        }

        // === POPUP MODE ===
        ?>
        <style>
            #<?php echo $unique_id; ?>-thumb {
                overflow: hidden;
                border-radius: inherit;
            }
            #<?php echo $unique_id; ?>-thumb img,
            #<?php echo $unique_id; ?>-thumb svg {
                border-radius: inherit;
            }
            #<?php echo $unique_id; ?>-dialog {
                position: fixed;
                top: 0; left: 0;
                width: 100%; height: 100%;
                background: rgba(0,0,0,0.95);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s;
            }
            #<?php echo $unique_id; ?>-dialog.show {
                opacity: 1;
                visibility: visible;
            }
            #<?php echo $unique_id; ?>-dialog .cvp-video-wrapper {
                position: relative;
                width: 95vw;
                max-width: 1200px;
                aspect-ratio: 16 / 9;
            }
            #<?php echo $unique_id; ?>-dialog iframe,
            #<?php echo $unique_id; ?>-dialog video {
                position: absolute;
                top: 0; left: 0;
                width: 100%;
                height: 100%;
                border: none;
            }
            #<?php echo $unique_id; ?>-close {
                position: fixed;
                top: 20px;
                right: 20px;
                color: white;
                font-size: 40px;
                cursor: pointer;
                background: none;
                border: none;
                z-index: 10000;
                padding: 0;
                line-height: 1;
            }
        </style>

        <div id="<?php echo $unique_id; ?>-thumb" style="cursor:pointer; position:relative; width:100%; aspect-ratio:16/9; overflow:hidden;">
            <?php echo $thumb_html; ?>
            <?php echo $play_btn_html; ?>
        </div>

        <div id="<?php echo $unique_id; ?>-dialog">
            <button id="<?php echo $unique_id; ?>-close">&times;</button>
            <div class="cvp-video-wrapper">
                <?php if ( $is_youtube && $video_id ): ?>
                    <iframe src="https://www.youtube.com/embed/<?php echo $video_id; ?>?autoplay=1&mute=1"
                            allow="autoplay; encrypted-media" allowfullscreen></iframe>
                <?php elseif ( $is_vimeo && $video_id ): ?>
                    <iframe src="https://player.vimeo.com/video/<?php echo $video_id; ?>?autoplay=1&muted=1"
                            allow="autoplay" allowfullscreen></iframe>
                <?php elseif ( $is_mp4 ): ?>
                    <video src="<?php echo esc_url( $video_url ); ?>" autoplay muted controls></video>
                <?php else: ?>
                    <div style="color:white; display:flex;align-items:center;justify-content:center;height:100%;">Unsupported video</div>
                <?php endif; ?>
            </div>
        </div>

        <script>
          document.getElementById('<?php echo $unique_id; ?>-thumb').addEventListener('click', function() {
            document.getElementById('<?php echo $unique_id; ?>-dialog').classList.add('show');
          });
          document.getElementById('<?php echo $unique_id; ?>-close').addEventListener('click', function() {
            document.getElementById('<?php echo $unique_id; ?>-dialog').classList.remove('show');
          });
        </script>
        <?php
    }
}