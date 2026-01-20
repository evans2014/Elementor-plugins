(function($){
    function initAllMaps(){
        $('.mwe-map-container').each(function(){
            var container = this;
            var localized = $(container).data('localize');
            var dataObj = window[localized] || {};
            if(!dataObj.settings) dataObj.settings = {};
            var locations = dataObj.locations || [];
            var settings = dataObj.settings || {};

            var center = {lat:0,lng:0};
            var cnt = 0;
            locations.forEach(function(l){
                if(l.lat && l.lng){
                    center.lat += parseFloat(l.lat);
                    center.lng += parseFloat(l.lng);
                    cnt++;
                }
            });
            if(cnt>0){
                center.lat /= cnt;
                center.lng /= cnt;
            }

            var predefinedStyles = {
                silver: [{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}],

                dark: [
                    {elementType: 'geometry', stylers: [{color: '#212121'}]},
                    {elementType: 'labels.text.fill', stylers: [{color: '#757575'}]},
                    {elementType: 'labels.text.stroke', stylers: [{color: '#212121'}]},
                    {featureType: 'poi', elementType: 'geometry', stylers: [{color: '#424242'}]},
                    {featureType: 'road', elementType: 'geometry', stylers: [{color: '#383838'}]},
                    {featureType: 'road', elementType: 'labels.text.fill', stylers: [{color: '#8a8a8a'}]},
                    {featureType: 'water', elementType: 'geometry', stylers: [{color: '#000000'}]},
                ],

                night: [
                    {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
                    {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
                    {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
                    {featureType: 'road', elementType: 'geometry', stylers: [{color: '#38414e'}]},
                    {featureType: 'water', elementType: 'geometry', stylers: [{color: '#17263c'}]},
                ],

                retro: [
                    {elementType: 'geometry', stylers: [{color: '#ebe3cd'}]},
                    {elementType: 'labels.text.fill', stylers: [{color: '#523735'}]},
                    {elementType: 'labels.text.stroke', stylers: [{color: '#f5f1e6'}]},
                    {featureType: 'poi', elementType: 'geometry', stylers: [{color: '#dfd2ae'}]},
                    {featureType: 'road', elementType: 'geometry', stylers: [{color: '#f5f1e6'}]},
                    {featureType: 'water', elementType: 'geometry', stylers: [{color: '#b9d3c2'}]},
                ],

                aubergine: [
                    {elementType: 'geometry', stylers: [{color: '#1d2c4d'}]},
                    {elementType: 'labels.text.fill', stylers: [{color: '#8ec3b9'}]},
                    {elementType: 'labels.text.stroke', stylers: [{color: '#1a3646'}]},
                    {featureType: 'road', elementType: 'geometry', stylers: [{color: '#304a7d'}]},
                    {featureType: 'water', elementType: 'geometry', stylers: [{color: '#0e1626'}]},
                ],

                light: [
                    {elementType: 'geometry', stylers: [{color: '#f5f5f5'}]},
                    {elementType: 'labels.text.fill', stylers: [{color: '#616161'}]},
                    {elementType: 'labels.text.stroke', stylers: [{color: '#f5f5f5'}]},
                    {featureType: 'poi', elementType: 'geometry', stylers: [{color: '#eeeeee'}]},
                    {featureType: 'road', elementType: 'geometry', stylers: [{color: '#ffffff'}]},
                    {featureType: 'water', elementType: 'geometry', stylers: [{color: '#c9c9c9'}]},
                ],

                minimal: [
                    {elementType: 'geometry', stylers: [{color: '#f2f2f2'}]},
                    {elementType: 'labels.icon', stylers: [{visibility: 'off'}]},
                    {elementType: 'labels.text.fill', stylers: [{color: '#616161'}]},
                    {elementType: 'labels.text.stroke', stylers: [{color: '#f2f2f2'}]},
                    {featureType: 'road', elementType: 'geometry', stylers: [{color: '#ffffff'}]},
                    {featureType: 'water', elementType: 'geometry', stylers: [{color: '#c9c9c9'}]},
                ]
            };

            var opts = {
                zoom: settings.zoom || 10,
                center: center,
                zoomControl: settings.zoomControl !== false
            };

            if(settings.map_style && settings.map_style !== 'default'){
                if(settings.map_style !== 'custom' && predefinedStyles[settings.map_style]){
                    opts.styles = predefinedStyles[settings.map_style];
                } else if(settings.map_style === 'custom' && settings.map_style_json){
                    try {
                        opts.styles = JSON.parse(settings.map_style_json);
                    } catch(e){
                        console.warn('Invalid custom JSON style');
                    }
                }
            }


            // We create the map
            var map = new google.maps.Map(container, opts);

            // Popup and markers
            var infowindow = new google.maps.InfoWindow();
            var markers = [];

            locations.forEach(function(l){
                if(!(l.lat && l.lng)) return;
                var marker = new google.maps.Marker({
                    position: {lat:parseFloat(l.lat), lng:parseFloat(l.lng)},
                    map: map,
                    title: l.title || '',
                    icon: l.icon?.url || null
                });
                marker.addListener('click', function(){

                    var content = '<div class="mwe-popup">';
                    content += '<span class="mwe-popup-close">&times;</span>'; // custom close
                    if(l.title) content += '<div class="mwe-popup-title"><b>'+l.title+'</b></div>';
                   // if(l.address) content += '<div class="mwe-popup-address">'+l.address+'</div>';
                    if(l.popup) content += '<div class="mwe-popup-content">'+l.popup+'</div>';
                    content += '</div>';


                    infowindow.setContent(content);
                    infowindow.open(map, marker);
                    google.maps.event.addListener(infowindow, 'domready', function() {
                        var closeBtn = document.querySelector('.mwe-popup-close');
                        if(closeBtn){
                            closeBtn.addEventListener('click', function(){
                                infowindow.close();
                            });
                        }
                    });

                });
                markers.push(marker);
            });

            // Clusters
            if(settings.cluster){
                new MarkerClusterer(map, markers, {
                    imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
                });
            }
        });
    }

    $(document).ready(function(){
        if(typeof google==='object' && google.maps){
            initAllMaps();
        } else {
            console.warn('Google Maps API key missing or maps not loaded');
        }
    });
})(jQuery);
