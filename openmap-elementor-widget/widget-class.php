<?php
if (!defined('ABSPATH')) exit;

class OpenMap_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'openmap'; }
    public function get_title() { return 'OpenMap – Clustering + Styles'; }
    public function get_icon() { return 'eicon-google-maps'; }
    public function get_categories() { return ['general']; }
    public function get_keywords() { return ['map', 'openstreetmap', 'location', 'pin', 'cluster']; }

    protected function register_controls() {

        $this->start_controls_section('section_map', [
            'label' => 'Map settings',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('map_height', [
            'label' => 'Height',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh'],
            'range' => ['px' => ['min' => 200, 'max' => 1000]],
            'default' => ['unit' => 'px', 'size' => 400],
        ]);

        $this->add_control('zoom', [
            'label' => 'Zoom level',
            'type' => \Elementor\Controls_Manager::SLIDER,
            'default' => ['size' => 12],
            'range' => ['px' => ['min' => 1, 'max' => 18]],
        ]);

        $this->add_control('center_lat', [
            'label' => 'Center (latitude)',
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '42.6977',
            'placeholder' => '42.6977',
        ]);

        $this->add_control('center_lng', [
            'label' => 'Center (longitude)',
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => '23.3219',
            'placeholder' => '23.3219',
        ]);

        // ——— СТИЛ НА КАРТАТА ———
        $map_styles = [
            'osm' => 'Standard (OSM)',
            'light' => 'Light (CartoDB)',
            'dark' => 'Dark (CartoDB)',
            'topo' => 'Topographic (OpenTopoMap)',
            'cycle' => 'Cycling (CyclOSM)',
            'satellite' => 'Satellite (Esri)',
            'gray' => 'Gray (HOT)',
        ];

        $this->add_control('map_style', [
            'label' => 'Map style',
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'osm',
            'options' => $map_styles,
            'separator' => 'before',
        ]);

        $this->end_controls_section();
        $repeater = new \Elementor\Repeater();

        $repeater->add_control('address', [
            'label' => 'Address',
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'Sofia, Vitosha str. 1',
        ]);

        $repeater->add_control('lat', [
            'label' => 'Width (lat)',
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => '42.6977',
            'description' => 'Required',
        ]);

        $repeater->add_control('lng', [
            'label' => 'Length (lng)',
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => '23.3219',
            'description' => 'Required',
        ]);

        $repeater->add_control('popup', [
            'label' => 'Popup content',
            'type' => \Elementor\Controls_Manager::WYSIWYG,
            'default' => '<strong>Офис</strong><br>бул. Витоша 1',
        ]);

        $repeater->add_control('icon', [
            'label' => 'Pin icon',
            'type' => \Elementor\Controls_Manager::MEDIA,
            'default' => [
                'url' => plugin_dir_url(__FILE__) . 'assets/marker-icon.png',
            ],
        ]);

        $this->start_controls_section('section_locations', [
            'label' => 'Locations',
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('locations', [
            'label' => 'Add locations',
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => $repeater->get_controls(),
            'default' => [
                [
                    'address' => 'Teatro alla Scala',
                    'lat' => '45.467604',
                    'lng' => '9.189114',
                    'popup' => '<strong>Teatro alla Scala</strong><br>Piazza della Scala,<br> 20121 Milan MI, Italy',
                ],
                [
                    'address' => 'Alfa Romeo Museum',
                    'lat' => '45.557578',
                    'lng' => '9.046787',
                    'popup' => '<strong>Alfa Romeo Museum</strong><br>Autostrada dei Laghi,<br> 20045 Rho MI, Italy',
                ],
                [
                    'address' => 'Museo Leonardo da Vinci',
                    'lat' => '41.902102',
                    'lng' => '12.461576',
                    'popup' => '<strong>Museo Leonardo da Vinci</strong><br>AVia della Conciliazione, <br> 00193 Rome RM, Italy',
                ],
            ],
            'title_field' => '{{{ address }}}',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id = 'openmap-' . $this->get_id();
        $height = $settings['map_height']['size'] . $settings['map_height']['unit'];

        $this->load_leaflet_with_cluster();

        if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            $this->render_editor_preview($settings, $id, $height);
        } else {
            $this->render_frontend_map_with_cluster($settings, $id, $height);
        }
    }

    private function load_leaflet_with_cluster() {
        wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4');
        wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], '1.9.4', true);

        wp_enqueue_style('markercluster-css', 'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css');
        wp_enqueue_style('markercluster-default-css', 'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css');
        wp_enqueue_script('markercluster-js', 'https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js', ['leaflet-js'], '1.4.1', true);

        // Search by address
        wp_enqueue_style('leaflet-geocoder-css', 'https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.css');
        wp_enqueue_script('leaflet-geocoder-js', 'https://unpkg.com/leaflet-control-geocoder@2.4.0/dist/Control.Geocoder.js', ['leaflet-js'], '2.4.0', true);

        // Search object
        wp_enqueue_style('leaflet-search-css', 'https://unpkg.com/leaflet-search@ 四.0.0/dist/leaflet-search.min.css');
        wp_enqueue_script('leaflet-search-js', 'https://unpkg.com/leaflet-search@4.0.0/dist/leaflet-search.min.js', ['leaflet-js'], '4.0.0', true);

        // Fullscreen
        wp_enqueue_style('leaflet-fullscreen-css', 'https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css', [], '1.0.1');
        wp_enqueue_script('leaflet-fullscreen-js', 'https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js', ['leaflet-js'], '1.0.1', true);

    }
    private function render_editor_preview($settings, $id, $height) {
        $count = count($settings['locations']);
        ?>
        <div style="border: 2px dashed #ccd0d4; padding: 20px; text-align: center; background: #f9f9f9; height: <?php echo esc_attr($height); ?>; display: flex; align-items: center; justify-content: center; flex-direction: column; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; color: #3c434a;">
            <div style="font-size: 18px; font-weight: 600; margin-bottom: 12px;">
                OpenMap – <?php echo esc_html($count); ?> locations (with clustering)
            </div>
            <div style="font-size: 12px; color: #646970; max-height: 210px; overflow-y: auto; text-align: left; width: 100%; padding: 0 20px;">
                <?php foreach (array_slice($settings['locations'], 0, 8) as $loc): ?>
                    <div style="margin: 0;">
                        <strong>• <?php echo esc_html(wp_trim_words($loc['address'] ?: strip_tags($loc['popup']), 6)); ?></strong>
                    </div>
                <?php endforeach; ?>
                <?php if ($count > 8): ?>
                    <em>...and more <?php echo $count - 8; ?> locations</em>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    private function render_frontend_map_with_cluster($settings, $id, $height) {
        $map_styles = [
            'osm'       => ['url' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 'attr' => '&copy; OpenStreetMap contributors'],
            'light'     => ['url' => 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', 'attr' => '&copy; OpenStreetMap &amp; CartoDB'],
            'dark'      => ['url' => 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', 'attr' => '&copy; OpenStreetMap &amp; CartoDB'],
            'topo'      => ['url' => 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', 'attr' => 'Map data: &copy; OpenStreetMap, SRTM | Style: &copy; OpenTopoMap'],
            'cycle'     => ['url' => 'https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', 'attr' => '&copy; OpenStreetMap &amp; CyclOSM'],
            'satellite' => ['url' => 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', 'attr' => 'Tiles &copy; Esri'],
            'gray'      => ['url' => 'https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', 'attr' => '&copy; OpenStreetMap, HOT'],
        ];

        $style = $map_styles[$settings['map_style']] ?? $map_styles['osm'];
        ?>
        <div id="<?php echo esc_attr($id); ?>" style="height: <?php echo esc_attr($height); ?>; width: 100%;"></div>

        <script>


        document.addEventListener('DOMContentLoaded', function () {
          const map = L.map('<?php echo esc_attr($id); ?>', { zoomControl: false })
            .setView([<?php echo esc_js($settings['center_lat']); ?>, <?php echo esc_js($settings['center_lng']); ?>], <?php echo esc_js($settings['zoom']['size']); ?>);

          L.tileLayer('<?php echo esc_js($style['url']); ?>', {
            attribution: '<?php echo esc_js($style['attr']); ?>'
          }).addTo(map);

          const markers = L.markerClusterGroup({
            maxClusterRadius: 50,
            iconCreateFunction: function(cluster) {
              return L.divIcon({
                html: '<div style="background:#2271b1;color:#fff;border-radius:50%;width:40px;height:40px;line-height:40px;text-align:center;font-weight:bold;">' + cluster.getChildCount() + '</div>',
                className: '',
                iconSize: [40, 40]
              });
            }
          });

          const locations = <?php echo wp_json_encode($settings['locations']); ?>;

          locations.forEach(loc => {
            const lat = parseFloat(loc.lat);
            const lng = parseFloat(loc.lng);
            if (isNaN(lat) || isNaN(lng)) return;

            const icon = L.icon({
              iconUrl: loc.icon.url || '<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/marker-icon.png'); ?>',
              iconSize: [32, 32],
              iconAnchor: [16, 32],
              popupAnchor: [0, -32]
            });

            const marker = L.marker([lat, lng], { icon });
            if (loc.popup) {
              marker.bindPopup(loc.popup);
              const cleanText = loc.popup.replace(/<[^>]*>/g, ' ').substring(0, 100);
              marker.options.title = cleanText.trim() || 'Обект';
            }
            markers.addLayer(marker);
          });

          map.addLayer(markers);
          if (locations.length > 0) map.fitBounds(markers.getBounds(), { padding: [50, 50] });

          // ========== CONTROLS ==========
          L.control.zoom({ position: 'bottomright' }).addTo(map);
          L.control.scale({ imperial: false }).addTo(map);
          L.control.fullscreen({ position: 'topright', title: 'Цял екран', titleCancel: 'Изход' }).addTo(map);

          // Search by address
          L.Control.geocoder({
            position: 'topleft',
            placeholder: 'Търси адрес...',
            collapsed: false
          }).addTo(map);

          // Search in objects
          new L.Control.Search({
            layer: markers,
            propertyName: 'title',
            position: 'topright',
            collapsed: false,
            textPlaceholder: 'Търси обект...',
            zoom: 17
          }).addTo(map);
        });
        </script>
        <?php
    }
}