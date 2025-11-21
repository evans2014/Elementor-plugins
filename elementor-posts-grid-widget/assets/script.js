jQuery(function($) {
    const $doc = $(document);

    // Filter
    $doc.on('click', '.epgw-filter', function() {
        const $this = $(this);
        const $wrapper = $this.closest('.epgw-wrapper');
        const $grid = $wrapper.find('.epgw-grid');
        const term = $this.data('term');

        $wrapper.find('.epgw-filter').removeClass('active');
        $this.addClass('active');
        $wrapper.find('.epgw-filter-mobile').val(term);

        loadPosts($grid, 1, term);
    });

    $doc.on('change', '.epgw-filter-mobile', function() {
        const $this = $(this);
        const $wrapper = $this.closest('.epgw-wrapper');
        const $grid = $wrapper.find('.epgw-grid');
        const term = $this.val();

        $wrapper.find('.epgw-filter').removeClass('active');
        $wrapper.find(`[data-term="${term}"]`).addClass('active');

        loadPosts($grid, 1, term);
    });

    // Load More
    $doc.on('click', '.epgw-load-btn', function() {
        const $btn = $(this);
        const $grid = $btn.closest('.epgw-wrapper').find('.epgw-grid');
        const paged = parseInt($btn.data('paged'));
        const term = $grid.closest('.epgw-wrapper').find('.epgw-filter.active').data('term') || 'all';

        loadPosts($grid, paged, term, true);
    });

    // PAGINATION + SCROLL TO FILTERS
    $doc.on('click', '.epgw-pagination a', function(e) {
        e.preventDefault();
        const $link = $(this);
        const href = $link.attr('href');
        const paged = href.match(/paged=(\d+)/)?.[1] || 1;
        const $grid = $link.closest('.epgw-wrapper').find('.epgw-grid');
        const term = $grid.closest('.epgw-wrapper').find('.epgw-filter.active').data('term') || 'all';

        loadPosts($grid, paged, term);
    });

    function loadPosts($grid, paged, term, append = false) {
        const $wrapper = $grid.closest('.epgw-wrapper');
        const $loader = $wrapper.find('.epgw-loader');

        // Show loader
        $loader.show();
        if (!append) $grid.css('opacity', '0.3');

        const data = {
            action: 'epgw_filter',
            post_type: $grid.data('post-type'),
            taxonomy: $grid.data('taxonomy'),
            term: term,
            per_page: $grid.data('per-page'),
            columns: $grid.data('columns'),
            paged: paged,
            pagination: $grid.data('pagination')
        };

        $.post(epgw_ajax.ajax_url, data, function(res) {
            if (!res.success) {
                $loader.hide();
                return;
            }

            const $posts = $(res.data.posts);
            const $loadMore = $(res.data.load_more);
            const $pagination = $(res.data.pagination);

            if (append) {
                $grid.append($posts);
                $grid.siblings('.epgw-pagination-wrapper').find('.epgw-load-more').remove();
            } else {
                $grid.html($posts);
            }

            const $wrapperPag = $grid.siblings('.epgw-pagination-wrapper').empty();
            if ($loadMore.length) $wrapperPag.append($loadMore);
            if ($pagination.length) $wrapperPag.append($pagination);

            // Update Load More
            $wrapperPag.find('.epgw-load-btn').data('paged', paged + 1);

            // Hide loader
            $loader.hide();
            $grid.css('opacity', '1');

            // SCROLL TO FILTERS
            if (!append) {
                $('html, body').animate({
                    scrollTop: $wrapper.find('.epgw-filters').offset().top - 180
                }, 500);
            }
        });
    }
});