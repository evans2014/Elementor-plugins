jQuery(document).ready(function($){
    $('#egmw_geocode_btn').on('click', function(e){
        e.preventDefault();
        var address = $('input[name="egmw_address"]').val();
        if (!address) {
            alert('Please enter an address first.');
            return;
        }
        var btn = $(this);
        btn.prop('disabled', true).text('Geocoding...');
        $.post(egmwAdmin.ajaxUrl, {
            action: 'egmw_geocode',
            address: address,
            nonce: egmwAdmin.nonce
        }, function(res){
            btn.prop('disabled', false).text('Geocode address');
            if (res.success) {
                $('#egmw_lat').val(res.data.lat);
                $('#egmw_lng').val(res.data.lng);
            } else {
                alert('Geocode error: ' + (res.data || res));
            }
        });
    });
});