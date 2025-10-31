jQuery(document).ready(function($){

    var map = L.map('daf-map').setView([42.7, 23.3], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    var markersLayer = L.layerGroup().addTo(map);

    const categoryIcons = {
        restaurant: L.icon({iconUrl:'/wp-content/uploads/2025/10/restaurant.png', iconSize:[32,32], iconAnchor:[16,32], popupAnchor:[0,-32]}),
        museum:     L.icon({iconUrl:'/wp-content/uploads/2025/10/museum.png', iconSize:[32,32], iconAnchor:[16,32], popupAnchor:[0,-32]}),
        park:       L.icon({iconUrl:'/wp-content/uploads/2025/10/park-location.png', iconSize:[32,32], iconAnchor:[16,32], popupAnchor:[0,-32]}),
        default:    L.icon({iconUrl:'/wp-content/uploads/2025/10/placeholder.png', iconSize:[32,32], iconAnchor:[16,32], popupAnchor:[0,-32]})
    };

     function updateMap(markers){
        markersLayer.clearLayers();
        markers.forEach(marker => {
            const icon = categoryIcons[marker.category] || categoryIcons['default'];
            L.marker([marker.lat, marker.lng], {icon: icon})
              .addTo(markersLayer)
              .bindPopup(`<strong>${marker.title}</strong>${marker.image}`);
        });
        if(markers.length){
            const group = new L.featureGroup(markersLayer.getLayers());
            map.fitBounds(group.getBounds().pad(0.2));
        }
    }

    function updateGrid(paged = 1){
        var data = $('.daf-filters').serialize();
        data += '&paged=' + paged + '&action=daf_filter';

        $.post(daf_ajax.ajax_url, data, function(res){
            if(res && res.html !== undefined && res.markers !== undefined){
                $('#daf-grid').html(res.html);
                updateMap(res.markers);
            } else {
                console.error('Невалиден отговор от AJAX', res);
            }
        }, 'json');
    }
    $('.daf-filters input, .daf-filters select').on('change', function(){
        updateGrid(1);
    });
    $(document).on('click', '.daf-pagination a, .daf-page', function(e){
        e.preventDefault();
        let paged = $(this).data('paged') || $(this).data('page');
        if(!paged) paged = $(this).attr('data-paged') || 1;
        updateGrid(paged);
    });

    updateGrid();
});
