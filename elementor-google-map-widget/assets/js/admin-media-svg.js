jQuery(document).ready(function($){
    var frame;
    $('#egmw_select_svg').on('click', function(e){
        e.preventDefault();
        // If the media frame already exists, reopen it.
        if (frame) {
            frame.open();
            return;
        }
        frame = wp.media({
            title: egmwMedia.title,
            button: { text: egmwMedia.button },
            multiple: false,
            library: { type: 'image/svg+xml' }
        });

        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            // ensure it's an SVG
            var url = attachment.url || attachment.sizes?.full?.url || '';
            var mime = attachment.mime || attachment.type || '';
            if ( mime !== 'image/svg+xml' && ! url.match(/\.svg$/i) ) {
                alert('Моля, изберете SVG файл.');
                return;
            }
            $('#egmw_svg_id').val(attachment.id);
            $('#egmw_svg_url').val(url);
            $('#egmw_svg_preview').html('<img src="'+url+'" style="width:48px;height:48px;vertical-align:middle" />');
            $('#egmw_remove_svg').show();
        });

        frame.open();
    });

    $('#egmw_remove_svg').on('click', function(e){
        e.preventDefault();
        $('#egmw_svg_id').val('');
        $('#egmw_svg_url').val('');
        $('#egmw_svg_preview').html('<em>Няма избрана иконка</em>');
        $(this).hide();
    });
});