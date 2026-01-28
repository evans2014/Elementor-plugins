<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

class CE_Map_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'ce_google_map';
    }

    public function get_title()
    {
        return 'Elementor Google Map';
    }

    public function get_icon()
    {
        return 'eicon-google-maps';
    }

    public function get_categories()
    {
        return ['general'];
    }

    protected function register_controls()
    {

        // Map settings
        $this->start_controls_section('map_settings', ['label' => 'Map Settings']);
        $this->add_control('height', ['label' => 'Map Height', 'type' => Controls_Manager::NUMBER, 'default' => 400]);
        $this->add_control('zoom', ['label' => 'Zoom', 'type' => Controls_Manager::NUMBER, 'default' => 6]);
        $this->add_control(
            'map_style',
            [
                'label' => 'Map Style',
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'cegm'),
                    'silver' => __('Silver', 'cegm'),
                    'retro' => __('Retro', 'cegm'),
                    'dark' => __('Dark', 'cegm'),
                    'night' => __('Night', 'cegm'),
                    'aubergine' => __('Aubergine', 'cegm'),
                    'custom' => __('Custom JSON', 'cegm'),
                ],
            ]
        );
        $this->add_control(
            'custom_json',
            [
                'label' => 'Custom JSON',
                'type' => Controls_Manager::CODE,
                'language' => 'json',
                'condition' => ['map_style' => 'custom'],
            ]
        );
        $this->end_controls_section();

        // Repeater markers
        $this->start_controls_section('markers_section', ['label' => 'Markers']);
        $repeater = new Repeater();
        $repeater->add_control('lat', ['label' => 'Latitude', 'type' => Controls_Manager::TEXT, 'default' => '42.6977']);
        $repeater->add_control('lng', ['label' => 'Longitude', 'type' => Controls_Manager::TEXT, 'default' => '23.3219']);
        $repeater->add_control('title', ['label' => 'Popup Title', 'type' => Controls_Manager::TEXT, 'default' => 'Marker title']);
        $repeater->add_control('content', ['label' => 'Popup Content', 'type' => Controls_Manager::WYSIWYG, 'default' => 'Popup content']);
        $repeater->add_control(
            'icon',
            [
                'label' => 'Custom Marker Icon',
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => ''],
            ]
        );
        $this->add_control('markers', ['type' => Controls_Manager::REPEATER, 'fields' => $repeater->get_controls(),
            'default' => [['lat' => '42.6977', 'lng' => '23.3219', 'title' => 'София', 'content' => 'Примерен popup', 'icon' => ['url' => '']]], 'title_field' => '{{{ title }}}']);
        $this->end_controls_section();
    }

    protected function render()
    {
        $s = $this->get_settings_for_display();
        $map_id = 'ce-map-' . $this->get_id();
        $height = (int)$s['height'];
        $zoom = (int)$s['zoom'];

        // Markers
        $markers = [];
        if (!empty($s['markers'])) {
            foreach ($s['markers'] as $m) {
                $markers[] = [
                    'lat' => (float)$m['lat'],
                    'lng' => (float)$m['lng'],
                    'title' => esc_html($m['title']),
                    'content' => wp_kses_post($m['content']),
                    'icon' => isset($m['icon']['url']) ? $m['icon']['url'] : '',
                ];
            }
        }

        // Map styles
               $styles = [
            'silver' => [
                ["elementType"=>"geometry","stylers"=>[["color"=>"#f5f5f5"]]],
                ["elementType"=>"labels.text.fill","stylers"=>[["color"=>"#616161"]]],
                ["elementType"=>"labels.text.stroke","stylers"=>[["color"=>"#f5f5f5"]]],
                ["featureType"=>"administrative.land_parcel","elementType"=>"labels.text.fill","stylers"=>[["color"=>"#bdbdbd"]]],
                ["featureType"=>"poi","elementType"=>"geometry","stylers"=>[["color"=>"#eeeeee"]]],
                ["featureType"=>"poi","elementType"=>"labels.text.fill","stylers"=>[["color"=>"#757575"]]],
                ["featureType"=>"road","elementType"=>"geometry","stylers"=>[["color"=>"#cccccc"]]],
                ["featureType"=>"road.arterial","elementType"=>"labels.text.fill","stylers"=>[["color"=>"#757575"]]],
                ["featureType"=>"road.highway","elementType"=>"geometry","stylers"=>[["color"=>"#dadada"]]],
                ["featureType"=>"road.highway","elementType"=>"labels.text.fill","stylers"=>[["color"=>"#616161"]]],
                ["featureType"=>"road.local","elementType"=>"labels.text.fill","stylers"=>[["color"=>"#9e9e9e"]]],
                ["featureType"=>"transit.line","elementType"=>"geometry","stylers"=>[["color"=>"#e5e5e5"]]],
                ["featureType"=>"transit.station","elementType"=>"geometry","stylers"=>[["color"=>"#eeeeee"]]],
                ["featureType"=>"water","elementType"=>"geometry","stylers"=>[["color"=>"#c9c9c9"]]],
                ["featureType"=>"water","elementType"=>"labels.text.fill","stylers"=>[["color"=>"#9e9e9e"]]],
            ],

            'retro' => [
                ["elementType"=>"geometry","stylers"=>[["color"=>"#ebe3cd"]]],
                ["elementType"=>"labels.text.fill","stylers"=>[["color"=>"#523735"]]],
                ["elementType"=>"labels.text.stroke","stylers"=>[["color"=>"#f5f1e6"]]],
                ["featureType"=>"administrative","elementType"=>"geometry.stroke","stylers"=>[["color"=>"#c9b2a6"]]],
                ["featureType"=>"administrative.land_parcel","elementType"=>"geometry.stroke","stylers"=>[["color"=>"#dcd2be"]]],
                ["featureType"=>"administrative.land_parcel","elementType"=>"labels.text.fill","stylers"=>[["color"=>"#ae9e90"]]],
                ["featureType"=>"landscape.natural","elementType"=>"geometry","stylers"=>[["color"=>"#dfd2ae"]]],
                ["featureType"=>"poi","elementType"=>"geometry","stylers"=>[["color"=>"#dfd2ae"]]],
                ["featureType"=>"poi","elementType"=>"labels.text.fill","stylers"=>[["color"=>"#93817c"]]],
                ["featureType"=>"road","elementType"=>"geometry","stylers"=>[["color"=>"#f5f1e6"]]],
                ["featureType"=>"road.arterial","elementType"=>"geometry","stylers"=>[["color"=>"#fdfcf8"]]],
                ["featureType"=>"road.highway","elementType"=>"geometry","stylers"=>[["color"=>"#f8c967"]]],
                ["featureType"=>"road.highway","elementType"=>"geometry.stroke","stylers"=>[["color"=>"#e9bc62"]]],
                ["featureType"=>"road.highway.controlled_access","elementType"=>"geometry","stylers"=>[["color"=>"#e98d58"]]],
                ["featureType"=>"road.highway.controlled_access","elementType"=>"geometry.stroke","stylers"=>[["color"=>"#db8555"]]],
                ["featureType"=>"road.local","elementType"=>"labels.text.fill","stylers"=>[["color"=>"#806b63"]]],
                ["featureType"=>"transit.line","elementType"=>"geometry","stylers"=>[["color"=>"#dfd2ae"]]],
                ["featureType"=>"transit.line","elementType"=>"labels.text.fill","stylers"=>[["color"=>"#8f7d77"]]],
                ["featureType"=>"transit.line","elementType"=>"labels.text.stroke","stylers"=>[["color"=>"#ebe3cd"]]],
                ["featureType"=>"transit.station","elementType"=>"geometry","stylers"=>[["color"=>"#dfd2ae"]]],
                ["featureType"=>"water","elementType"=>"geometry","stylers"=>[["color"=>"#b9d3c2"]]],
                ["featureType"=>"water","elementType"=>"labels.text.fill","stylers"=>[["color"=>"#92998d"]]],
            ],

            'dark' => [
                ["elementType"=>"geometry","stylers"=>[["color"=>"#212121"]]],
                ["elementType"=>"labels.text.fill","stylers"=>[["color"=>"#757575"]]],
                ["elementType"=>"labels.text.stroke","stylers"=>[["color"=>"#212121"]]],
                ["featureType"=>"administrative","elementType"=>"geometry","stylers"=>[["color"=>"#757575"]]],
                ["featureType"=>"poi","elementType"=>"geometry","stylers"=>[["color"=>"#181818"]]],
                ["featureType"=>"road","elementType"=>"geometry.fill","stylers"=>[["color"=>"#2c2c2c"]]],
                ["featureType"=>"road","elementType"=>"geometry.stroke","stylers"=>[["color"=>"#212121"]]],
                ["featureType"=>"water","elementType"=>"geometry","stylers"=>[["color"=>"#000000"]]],
            ],

            'night' => [
                ["elementType"=>"geometry","stylers"=>[["color"=>"#242f3e"]]],
                ["elementType"=>"labels.text.stroke","stylers"=>[["color"=>"#242f3e"]]],
                ["elementType"=>"labels.text.fill","stylers"=>[["color"=>"#746855"]]],
                ["featureType"=>"administrative.locality","elementType"=>"labels.text.fill","stylers"=>[["color"=>"#d59563"]]],
                ["featureType"=>"poi","elementType"=>"labels.text.fill","stylers"=>[["color"=>"#d59563"]]],
                ["featureType"=>"poi.park","elementType"=>"geometry","stylers"=>[["color"=>"#263c3f"]]],
                ["featureType"=>"road","elementType"=>"geometry","stylers"=>[["color"=>"#38414e"]]],
                ["featureType"=>"road","elementType"=>"geometry.stroke","stylers"=>[["color"=>"#212a37"]]],
                ["featureType"=>"water","elementType"=>"geometry","stylers"=>[["color"=>"#17263c"]]],
            ],

            'aubergine' => [
                ["elementType"=>"geometry","stylers"=>[["color"=>"#1d2c4d"]]],
                ["elementType"=>"labels.text.fill","stylers"=>[["color"=>"#8ec3b9"]]],
                ["elementType"=>"labels.text.stroke","stylers"=>[["color"=>"#1a3646"]]],
                ["featureType"=>"administrative.country","elementType"=>"geometry.stroke","stylers"=>[["color"=>"#4b6878"]]],
                ["featureType"=>"road","elementType"=>"geometry","stylers"=>[["color"=>"#304a7d"]]],
                ["featureType"=>"water","elementType"=>"geometry","stylers"=>[["color"=>"#0e1626"]]],
            ],
        ];



        $selected_style = 'default';
        if ($s['map_style'] !== 'default') {
            if ($s['map_style'] === 'custom' && !empty($s['custom_json'])) {
                $custom_json = json_decode($s['custom_json'], true);
                $selected_style = $custom_json ?: [];
            } else {
                $selected_style = $styles[$s['map_style']] ?? [];
            }
        }
        ?>
        <div id="<?php echo esc_attr($map_id); ?>" style="width:100%;height:<?php echo esc_attr($height); ?>px;"></div>
		
	    <style>		
			.gm-svpc {
				background-size:24px 24px !important;
				background:no-repeat !important;	
			}				
			.gm-svpc div {
				position:relative !important;
				left: 0!important;
				top: 0!important;
			}
			.gm-style-mtc ul li {
				list-style:none !important;
			}

		</style>
        <script>
         (function ($) {
			function initCEMap($scope) {
				const mapContainer = $scope.find('#<?php echo esc_js($map_id); ?>')[0];
				if (!mapContainer || typeof google === 'undefined') return;

				const markersData = <?php echo json_encode($markers); ?>;
				const mapStyles = <?php echo json_encode($selected_style); ?>;
				const defaultCenter = {lat: 42.6977, lng: 23.3219};

				const map = new google.maps.Map(mapContainer, {
					zoom: <?php echo $zoom; ?>,
					center: markersData.length ? {lat: markersData[0].lat, lng: markersData[0].lng} : defaultCenter,
					styles: mapStyles,
					streetViewControl:true , 
					fullscreenControl: true ,
				});

				const infoWindow = new google.maps.InfoWindow();
				const bounds = new google.maps.LatLngBounds();
				const markers = [];

				markersData.forEach(function (m) {
					const position = {lat: m.lat, lng: m.lng};
					bounds.extend(position);

					const marker = new google.maps.Marker({
						position: position,
						map: map,
						icon: m.icon ? m.icon : null
					});

					marker.addListener('click', function () {
						infoWindow.setContent(`
							<div style="width:200px;">
								<div style="display:flex;justify-content:space-between;align-items:center;">
									<strong style="font-size:18px !important;">${m.title}</strong>
								</div>
								<div style="font-size:16px !important;">${m.content}</div>
							</div>
						`);
						infoWindow.open(map, marker);
					});

					markers.push(marker);
				});

				// MarkerClusterer
				if (typeof MarkerClusterer !== 'undefined' && markers.length > 1) {
					new MarkerClusterer(map, markers, {
						imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m',
						gridSize: 60,
						minimumClusterSize: 2
					});
				}

				// Zoom / fitBounds
				if (markers.length > 1) {
					map.fitBounds(bounds);
					google.maps.event.addListenerOnce(map, 'idle', function () {
						map.setZoom(Math.min(map.getZoom(), <?php echo $zoom; ?>));
					});
				} else {
					map.setZoom(<?php echo $zoom; ?>);
				}
			}

			if (typeof elementorFrontend !== 'undefined') {
				elementorFrontend.hooks.addAction(
					'frontend/element_ready/ce_google_map.default',
					function ($scope, $) {
						initCEMap($scope);
					}
				);
			} else {
				jQuery(document).ready(function () {
					initCEMap(jQuery(document));
				});
			}
			

		})(jQuery);

        </script>
        <?php
    }
}
