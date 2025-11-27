
var EGMW_STYLES = {"silver": [{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}],
    "retro": [{"elementType": "geometry", "stylers": [{"color": "#ebe3cd"}]}, {"elementType": "labels.text.fill", "stylers": [{"color": "#523735"}]}],
    "dark": [{"elementType": "geometry", "stylers": [{"color": "#212121"}]}, {"elementType": "labels.text.fill", "stylers": [{"color": "#757575"}]}],
    "night": [{"elementType": "geometry", "stylers": [{"color": "#2c3e50"}]}, {"elementType": "labels.text.fill", "stylers": [{"color": "#ecf0f1"}]}],
    "aubergine": [{"elementType": "geometry", "stylers": [{"color": "#3b1c3b"}]}, {"elementType": "labels.text.fill", "stylers": [{"color": "#e0d7f0"}]}]};
(function($){
    function initEGMWMap(container, data, settings) {
        var opts = {
            zoom: settings.zoom || 10,
            center: { lat: 0, lng: 0 },
            zoomControl: true,
            mapTypeControl: false,
            streetViewControl: true,
            fullscreenControl: true,

        };

        // determine center: if provided in widget settings use that, else compute average
        if ( settings.center_lat && settings.center_lng ) {
            opts.center.lat = parseFloat(settings.center_lat);
            opts.center.lng = parseFloat(settings.center_lng);
        } else if ( data.locations && data.locations.length ) {
            var sumLat = 0, sumLng = 0;
            data.locations.forEach(function(l){ sumLat += l.lat; sumLng += l.lng; });
            opts.center.lat = sumLat / data.locations.length;
            opts.center.lng = sumLng / data.locations.length;
        }

        if(settings.map_style){
    if(settings.map_style !== 'custom' && EGMW_STYLES[settings.map_style]){
        opts.styles = EGMW_STYLES[settings.map_style];
    } else if(settings.map_style === 'custom' && settings.map_style_json){
        try{ opts.styles = JSON.parse(settings.map_style_json); }catch(e){ console.warn('Invalid custom JSON'); }
    }
}
var map = new google.maps.Map(container, opts);
        var markers = [];
        var infowindow = new google.maps.InfoWindow();

        data.locations.forEach(function(loc){
            var iconUrl = '';
            if (loc.icon_url) iconUrl = loc.icon_url;
            else if (loc.icon) iconUrl = data.svgBaseUrl + loc.icon;
            var markerOpts = {
                position: { lat: loc.lat, lng: loc.lng },
                map: map,
                title: loc.title || '',
            };
            if (iconUrl) {
                markerOpts.icon = {
                    url: iconUrl,
                };
            }
            var marker = new google.maps.Marker(markerOpts);

            marker.addListener('click', function(){
                var content = '<div class="egmw-popup"><h4>' + (loc.title || '') + '</h4>';
                if (loc.address) content += '<div class="egmw-address">' + loc.address + '</div>';
                if (loc.popup) content += '<div class="egmw-content">' + loc.popup + '</div>';
                content += '</div>';
                infowindow.setContent(content);
                infowindow.open(map, marker);
            });

            markers.push(marker);
        });

        // clustering
        if ( settings.cluster ) {
            try {
                new MarkerClusterer(map, markers, {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
            } catch (e) {
                console.warn('MarkerClusterer error', e);
            }
        }

        return map;
    }

    function initAllEGMW() {
        $('.egmw-map-container').each(function(){
            var container = this;
            if ( $(container).data('egmw-initialized') ) return;
            $(container).data('egmw-initialized', true);

            var data = window.EGMW_DATA || {};
            var settings = data.settings || {};
            initEGMWMap(container, data, settings);
        });
    }

    if ( typeof google !== 'undefined' && google.maps ) {
        $(document).ready(initAllEGMW);
    } else {
        window.egmwOnMapsLoad = function(){ initAllEGMW(); };
        $(document).ready(function(){
            setTimeout(initAllEGMW, 1500);
        });
    }
})(jQuery);// Map Style Preview in Elementor editor
if(typeof elementor !== 'undefined'){
    jQuery(document).on('change','select[name="map_style"],textarea[name="map_style_json"]', function(){
        var style = jQuery('select[name="map_style"]').val();
        var json = jQuery('textarea[name="map_style_json"]').val();
        var previewDiv = document.getElementById('egmw-map-style-preview');
        if(previewDiv && typeof google !== 'undefined' && google.maps){
            var previewOpts = { zoom:1, center:{lat:0,lng:0} };
            if(style !== 'custom' && EGMW_STYLES[style]) previewOpts.styles = EGMW_STYLES[style];
            else if(style === 'custom'){ try{ previewOpts.styles = JSON.parse(json); }catch(e){ previewOpts.styles=null; } }
            var previewMap = new google.maps.Map(previewDiv, previewOpts);
        }
    });
}