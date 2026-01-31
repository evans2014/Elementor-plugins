jQuery(document).ready(function($){
    function reload_loop_grid(category, order, page=1){
        $.ajax({
            url: pg_ajax.ajaxurl,
            type: 'POST',
            data: { action:'filter_loop_grid', category:category, order:order, page:page },
            beforeSend: function(){ $('#my-loop-grid').fadeTo(150,0.5); },
            success: function(response){
                $('#my-loop-grid').html(response);
                $('#my-loop-grid .grid-item').css('opacity',0).each(function(i){
                    $(this).delay(i*100).animate({opacity:1},300);
                });
                $('#my-loop-grid').fadeTo(150,1);
            }
        });
    }

    $('#category-filter, #sort-filter').on('change', function(){
        reload_loop_grid($('#category-filter').val(), $('#sort-filter').val());
    });

    $(document).on('click','#my-loop-grid .page-numbers a',function(e){
        e.preventDefault();
        var page = $(this).text();
        reload_loop_grid($('#category-filter').val(), $('#sort-filter').val(), page);
    });
});
