<?php
if (!defined('ABSPATH')) exit;

class XAI_Video_Lightbox_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'xai_video_lightbox'; }
    public function get_title() { return 'Video Lightbox'; }
    public function get_icon() { return 'eicon-youtube'; }
    public function get_categories() { return ['general']; }

    protected function register_controls() {
        $this->start_controls_section('source', ['label' => 'Video']);
        $this->add_control('video_type', ['label' => 'Type', 'type' => 'select', 'default' => 'youtube', 'options' => ['youtube' => 'YouTube', 'vimeo' => 'Vimeo']]);
        $this->add_control('video_url', ['label' => 'Link', 'type' => 'url', 'placeholder' => 'https://youtube.com/watch?v=dQw4w9WgXcQ']);
        $this->end_controls_section();

        $this->start_controls_section('settings', ['label' => 'Settings']);
        $this->add_control('aspect_ratio', ['label' => 'Ratio', 'type' => 'select', 'default' => '16-9', 'options' => ['16-9'=>'16:9', '4-3'=>'4:3', '1-1'=>'1:1', '21-9'=>'21:9']]);
        $this->add_control('lightbox', ['label' => 'Lightbox', 'type' => 'switcher', 'default' => 'yes']);
        $this->add_control('autoplay', ['label' => 'Autoplay', 'type' => 'switcher', 'default' => 'yes', 'condition' => ['lightbox' => 'yes']]);
        $this->add_control('show_controls', ['label' => 'Controls', 'type' => 'switcher', 'default' => 'yes']);
        $this->end_controls_section();

        $this->start_controls_section('play_button', ['label' => 'Play Button', 'condition' => ['lightbox' => 'yes']]);
        $this->add_control('play_button_source', ['label' => 'Source', 'type' => 'select', 'default' => 'default', 'options' => ['default'=>'Default', 'upload'=>'Upload SVG']]);
        $this->add_control('play_button_svg', ['label' => 'SVG', 'type' => 'media', 'media_type' => 'image', 'condition' => ['play_button_source' => 'upload']]);
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $video_url = is_array($settings['video_url']) ? ($settings['video_url']['url'] ?? '') : $settings['video_url'];
        if (empty($video_url)) {
            echo '<p>Missing video link.</p>';
            return;
        }

        $id = $this->extract_id($video_url, $settings['video_type']);
        if (!$id) {
            echo '<p>Invalid video link.</p>';
            return;
        }

        $thumb = $this->get_thumb($id, $settings['video_type']);
        $pb = $this->padding_bottom($settings['aspect_ratio']);
        $widget_id = $this->get_id(); // Уникален ID от Elementor

        if ($settings['lightbox'] === 'yes') {
            $this->render_lightbox($id, $settings['video_type'], $thumb, $settings, $pb, $widget_id);
        } else {
            $url = $this->embed_url($id, $settings['video_type'], $settings, false);
            echo "<div style='position:relative;padding-bottom:$pb;overflow:hidden;'><iframe src='$url' frameborder='0' allowfullscreen style='position:absolute;top:0;left:0;width:100%;height:100%;'></iframe></div>";
        }
    }

    private function render_lightbox($id, $type, $thumb, $s, $pb, $widget_id) {
        $url = $this->embed_url($id, $type, $s, true);
        $unique_class = 'xai-video-' . $widget_id;
        ?>
        <div class="xai-video-wrapper <?php echo esc_attr($unique_class); ?>">
            <div class="xai-video-container" style="position:relative;padding-bottom:0; ?>;overflow:hidden;cursor:pointer;">
                <img src="<?php echo esc_url($thumb); ?>" class="xai-thumbnail" loading="lazy" alt="Video">
                <div class="xai-play-button">
                    <?php echo $s['play_button_source'] === 'upload' && !empty($s['play_button_svg']['url']) 
                        ? '<img src="'.esc_url($s['play_button_svg']['url']).'" alt="Play">' 
                        : '<svg viewBox="0 0 68 48"><path d="M0 0h68v48H0z" fill="none"/><path d="M6 6v36l56-18z" fill="#fff"/></svg>'; ?>
                </div>
                <a href="<?php echo esc_url($url); ?>" class="xai-lightbox-trigger" style="position:absolute;top:0;left:0;width:100%;height:100%;z-index:10;"></a>
            </div>
        </div>

       
        <script>
        (function() {
            var wrapper = document.querySelector('.<?php echo esc_js($unique_class); ?>');
            if (!wrapper) return;

            var trigger = wrapper.querySelector('.xai-lightbox-trigger');
            if (!trigger) return;

            wrapper.addEventListener('click', function(e) {
                e.preventDefault();
                openPopup(trigger.href);
            });

            function openPopup(url) {
                var popup = document.createElement('div');
                popup.id = 'xai-lightbox-popup';
                popup.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.95);z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px;box-sizing:border-box;';

                var iframe = document.createElement('iframe');
                iframe.src = url;
                iframe.style.cssText = 'width:100%;height:100%;max-width:1200px;max-height:675px;border:none;border-radius:8px;';
                iframe.allowFullscreen = true;
                iframe.allow = 'autoplay; fullscreen';

                var closeBtn = document.createElement('div');
                closeBtn.innerHTML = '×';
                closeBtn.style.cssText = 'position:absolute;top:15px;right:20px;color:white;font-size:40px;font-weight:bold;cursor:pointer;z-index:1000;';
                closeBtn.onclick = function() {
                    document.body.removeChild(popup);
                };

                popup.appendChild(closeBtn);
                popup.appendChild(iframe);
                document.body.appendChild(popup);

                // ESC за затваряне
                document.addEventListener('keydown', function escHandler(e) {
                    if (e.key === 'Escape') {
                        if (document.getElementById('xai-lightbox-popup')) {
                            document.body.removeChild(popup);
                            document.removeEventListener('keydown', escHandler);
                        }
                    }
                });
            }
        })();
        </script>

        <?php
    }

    private function extract_id($url, $type) {
        if ($type === 'youtube') {
            return preg_match('/(?:v=|youtu\.be\/|youtube\.com\/embed\/)([^&\?]+)/i', $url, $m) ? $m[1] : false;
        }
        if ($type === 'vimeo') {
            return preg_match('/vimeo\.com\/(\d+)/i', $url, $m) ? $m[1] : false;
        }
        return false;
    }

    private function get_thumb($id, $type) {
        if ($type === 'youtube') return "https://img.youtube.com/vi/{$id}/maxresdefault.jpg";
        if ($type === 'vimeo') {
            $key = "vthumb_{$id}";
            $thumb = get_transient($key);
            if (false === $thumb) {
                $r = wp_remote_get("https://vimeo.com/api/v2/video/{$id}.json", ['timeout' => 5]);
                if (!is_wp_error($r) && ($body = wp_remote_retrieve_body($r))) {
                    $data = json_decode($body, true);
                    $thumb = $data[0]['thumbnail_large'] ?? '';
                    set_transient($key, $thumb, DAY_IN_SECONDS);
                }
            }
            return $thumb ?: '';
        }
        return '';
    }

    private function embed_url($id, $type, $s, $lb) {
        $ap = ($lb && $s['autoplay'] === 'yes') ? 1 : 0;
        $c = $s['show_controls'] === 'yes' ? 1 : 0;
        return $type === 'youtube'
            ? "https://www.youtube.com/embed/{$id}?autoplay={$ap}&controls={$c}&rel=0&modestbranding=1&playsinline=1"
            : "https://player.vimeo.com/video/{$id}?autoplay={$ap}&controls={$c}";
    }

    private function padding_bottom($r) {
        return ['16-9'=>'56.25%', '4-3'=>'75%', '1-1'=>'100%', '21-9'=>'42.86%'][$r] ?? '56.25%';
    }
}