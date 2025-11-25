<?php
if (!defined('ABSPATH')) exit;

class XAI_Video_Lightbox_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'xai_video_lightbox'; }
    public function get_title() { return 'XAI Видео Lightbox'; }
    public function get_icon() { return 'eicon-youtube'; }
    public function get_categories() { return ['general']; }

    protected function register_controls() {
        $this->start_controls_section('source', ['label' => 'Видео']);
        $this->add_control('video_type', ['label' => 'Тип', 'type' => 'select', 'default' => 'youtube', 'options' => ['youtube' => 'YouTube', 'vimeo' => 'Vimeo']]);
        $this->add_control('video_url', ['label' => 'Линк', 'type' => 'url', 'placeholder' => 'https://youtube.com/watch?v=ID']);
        $this->end_controls_section();

        $this->start_controls_section('settings', ['label' => 'Настройки']);
        $this->add_control('aspect_ratio', ['label' => 'Съотношение', 'type' => 'select', 'default' => '16-9', 'options' => ['16-9'=>'16:9', '4-3'=>'4:3', '1-1'=>'1:1', '21-9'=>'21:9']]);
        $this->add_control('lightbox', ['label' => 'Lightbox', 'type' => 'switcher', 'default' => 'yes']);
        $this->add_control('autoplay', ['label' => 'Autoplay', 'type' => 'switcher', 'default' => 'yes', 'condition' => ['lightbox' => 'yes']]);
        $this->add_control('show_controls', ['label' => 'Контроли', 'type' => 'switcher', 'default' => 'yes']);
        $this->end_controls_section();

        $this->start_controls_section('play_button', ['label' => 'Play Button', 'condition' => ['lightbox' => 'yes']]);
        $this->add_control('play_button_source', ['label' => 'Източник', 'type' => 'select', 'default' => 'default', 'options' => ['default'=>'Default', 'upload'=>'Upload SVG']]);
        $this->add_control('play_button_svg', ['label' => 'SVG', 'type' => 'media', 'media_type' => 'image', 'condition' => ['play_button_source' => 'upload']]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();

        // ТУК Е КЛЮЧЪТ: $s['video_url'] е масив → вземаме ['url']
        $video_url = is_array($s['video_url']) ? ($s['video_url']['url'] ?? '') : $s['video_url'];
        if (empty($video_url)) {
            echo '<p>Моля, въведете линк към видео.</p>';
            return;
        }

        $id = $this->extract_id($video_url, $s['video_type']);
        if (!$id) {
            echo '<p>Невалиден видео линк.</p>';
            return;
        }

        $thumb = $this->get_thumb($id, $s['video_type']);
        $pb = $this->padding_bottom($s['aspect_ratio']);

        if ($s['lightbox'] === 'yes') {
            $this->render_lightbox($id, $s['video_type'], $thumb, $s, $pb);
        } else {
            $url = $this->embed_url($id, $s['video_type'], $s, false);
            echo "<div style='position:relative;padding-bottom:$pb;overflow:hidden;'><iframe src='$url' frameborder='0' allowfullscreen style='position:absolute;top:0;left:0;width:100%;height:100%;'></iframe></div>";
        }
    }

    private function render_lightbox($id, $type, $thumb, $s, $pb) {
        $wid = $this->get_id();
        $url = $this->embed_url($id, $type, $s, true);
        ?>
        <div class="xai-video-wrapper" id="xai-video-<?php echo esc_attr($wid); ?>">
            <div class="xai-video-container" style="position:relative;padding-bottom:<?php echo esc_attr($pb); ?>;overflow:hidden;cursor:pointer;">
                <img src="<?php echo esc_url($thumb); ?>" class="xai-thumbnail" loading="lazy" alt="Video thumbnail">
                <div class="xai-play-button">
                    <?php 
                    if ($s['play_button_source'] === 'upload' && !empty($s['play_button_svg']['url'])) {
                        echo '<img src="'.esc_url($s['play_button_svg']['url']).'" alt="Play">';
                    } else {
                        echo '<svg viewBox="0 0 68 48"><path d="M0 0h68v48H0z" fill="none"/><path d="M6 6v36l56-18z" fill="#fff"/></svg>';
                    }
                    ?>
                </div>
                <a href="<?php echo esc_url($url); ?>" class="xai-lightbox-link xai-link-<?php echo esc_attr($wid); ?>"></a>
            </div>
        </div>
        <script>
        jQuery(function($){
            var $link = $('#xai-video-<?php echo esc_js($wid); ?> .xai-lightbox-link');
            $link.magnificPopup({
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: true,
                fixedContentPos: false
            });
            $('#xai-video-<?php echo esc_js($wid); ?>').on('click', function(e){
                if ($(window).width() <= 767) {
                    $link.trigger('click');
                }
            });
        });
        </script>
        <?php
    }

    private function extract_id($url, $type) {
        if ($type === 'youtube') {
            if (preg_match('/(?:v=|youtu\.be\/|youtube\.com\/embed\/)([^&\?]+)/', $url, $m)) {
                return $m[1];
            }
        } elseif ($type === 'vimeo') {
            if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $url, $m)) {
                return $m[1];
            }
        }
        return false;
    }

    private function get_thumb($id, $type) {
        if ($type === 'youtube') {
            return "https://img.youtube.com/vi/{$id}/maxresdefault.jpg";
        }
        if ($type === 'vimeo') {
            $key = "vthumb_{$id}";
            $thumb = get_transient($key);
            if (false === $thumb) {
                $resp = wp_remote_get("https://vimeo.com/api/v2/video/{$id}.json", ['timeout' => 10]);
                if (!is_wp_error($resp) && wp_remote_retrieve_response_code($resp) === 200) {
                    $data = json_decode(wp_remote_retrieve_body($resp), true);
                    $thumb = $data[0]['thumbnail_large'] ?? '';
                    set_transient($key, $thumb, DAY_IN_SECONDS);
                } else {
                    $thumb = '';
                }
            }
            return $thumb;
        }
        return '';
    }

    private function embed_url($id, $type, $s, $lb) {
        $ap = ($lb && $s['autoplay'] === 'yes') ? 1 : 0;
        $c = $s['show_controls'] === 'yes' ? 1 : 0;
        if ($type === 'youtube') {
            return "https://www.youtube.com/embed/{$id}?autoplay={$ap}&controls={$c}&rel=0&modestbranding=1";
        }
        return "https://player.vimeo.com/video/{$id}?autoplay={$ap}&controls={$c}";
    }

    private function padding_bottom($r) {
        $map = ['16-9'=>'56.25%', '4-3'=>'75%', '1-1'=>'100%', '21-9'=>'42.86%'];
        return $map[$r] ?? '56.25%';
    }
}