<?php
/**
 * Plugin Name: Jobs Filter Plugin (ACF)
 * Description: AJAX filter ACF
 * Version: 1.0
 * Author: ICT-Strypes
 */

add_shortcode('jobs_filter', function () {

    $jobs = get_posts([
        'post_type' => 'vacancies',
        'posts_per_page' => -1,
        'post_status' => 'publish',
    ]);

    $tech_values = [];

     foreach ($jobs as $job) {
        $value = get_field('job_technology', $job->ID);

        if (is_array($value)) {
            foreach ($value as $v) {
                if (!isset($tech_counts[$v])) {
                    $tech_counts[$v] = 0;
                }
                $tech_counts[$v]++;
            }
        } elseif ($value) {
            if (!isset($tech_counts[$value])) {
                $tech_counts[$value] = 0;
            }
            $tech_counts[$value]++;
        }
    }
    sort($tech_values);

    ob_start(); ?>
    <div class="elementor-shortcode">
        <div class="elementor-container elementor-column-gap-default">
            <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-2da5f5ad facet-box-jobs" data-id="2da5f5ad" data-element_type="column">
                <div class="elementor-widget-wrap elementor-element-populated">
                    <div class="elementor-element elementor-element-2cb8884d elementor-widget elementor-widget-heading" data-id="2cb8884d" data-element_type="widget" data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <h2 class="heading-title">
                                We are hiring</h2>		</div>
                    </div>
                    <div class="elementor-element elementor-element-1c7092aa elementor-widget elementor-widget-heading" data-id="1c7092aa" data-element_type="widget" data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <h2 class="color-heading-title">Search open positions</h2>		</div>
                    </div>
                    <div class="elementor-element elementor-element-64ef724b elementor-widget elementor-widget-shortcode" data-id="64ef724b" data-element_type="widget" data-widget_type="shortcode.default">
                        <div class="elementor-widget-container">
                            <div class="jobs-filter-wrapper">
                                <select id="job-technology">
                                    <option value="">All technology</option>
                                    <?php foreach ($tech_counts as $tech => $count): ?>
                                        <option value="<?php echo esc_attr($tech); ?>">
                                            <?php echo esc_html($tech); ?> (<?php echo $count; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div id="job-loader" class="job-loader">

                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-1c476d0c" data-id="1c476d0c" data-element_type="column">
                <div class="elementor-widget-wrap elementor-element-populated">
                    <div class="elementor-element elementor-element-79e64906 elementor-widget elementor-widget-image" data-id="79e64906" data-element_type="widget" data-widget_type="image.default">
                        <div class="elementor-widget-container">
                            <img decoding="async" width="442" height="481" src="https://strypes.loc/wp-content/uploads/2021/11/job-images.png" class="attachment-large size-large wp-image-1785" alt="" loading="lazy" srcset="https://strypes.loc/wp-content/uploads/2021/11/job-images.png 442w, https://strypes.loc/wp-content/uploads/2021/11/job-images-276x300.png 276w" sizes="(max-width: 442px) 100vw, 442px">															</div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div id="jobs-list"></div>
    <div id="pagination-container"></div>
    <?php return ob_get_clean();
});

add_action('wp_enqueue_scripts', function () {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'jobs_filter')) {
        wp_enqueue_script('jobs-filter-js', plugin_dir_url(__FILE__) . '/js/jobs-filter.js', ['jquery'], null, true);
        wp_localize_script('jobs-filter-js', 'jobs_ajax', ['ajax_url' => admin_url('admin-ajax.php')]);
        wp_register_script( 'jobs-filter-js', plugins_url('/js/jobs-filter.js', __FILE__), array('jquery'), '2.5.1' );
        wp_register_style( 'jobs-filter-css', plugins_url('/css/jobs-filter.css', __FILE__), false, '1.0.0', 'all');
        wp_enqueue_style('jobs-filter-css');
    }
});

add_action('wp_ajax_filter_jobs', 'filter_jobs_callback');
add_action('wp_ajax_nopriv_filter_jobs', 'filter_jobs_callback');

/**
 * @return void
 */
function filter_jobs_callback() {
    $tech = isset($_POST['technology']) ? sanitize_text_field($_POST['technology']) : '';
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $args = [
        'post_type' => 'vacancies',
        'posts_per_page' => 6,
        'paged' => $paged,
    ];

    if (!empty($tech)) {
        $args['meta_query'] = [[
            'key' => 'job_technology',
            'value' => $tech,
            'compare' => 'LIKE',
        ]];
    }

    $q = new WP_Query($args);

    ob_start();
    if ($q->have_posts()):
        while ($q->have_posts()): $q->the_post(); ?>
            <div class="job-item">
                <div class="title-main">
                    <h3><a class="title-link" href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h3>
                </div>
                <div class="button-main">
                    <div class="elementor-button-wrapper">
                        <a class="elementor-button elementor-button-link elementor-size-sm" href="<?php echo get_permalink(); ?>">
                            <span class="elementor-button-content-wrapper-btn">
                                <span class="elementor-button-text">View</span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile;
    else:
        echo '<p>No results.</p>';
    endif;
    wp_reset_postdata();
    $html = ob_get_clean();

    $pagination = get_ajax_pagination($q, $paged);

    wp_send_json([
        'jobs_html' => $html,
        'pagination_html' => $pagination,
    ]);
    wp_die();
}

/**
 * @param $query
 * @param $current_page
 * @return string
 */
function get_ajax_pagination($query, $current_page) {
    $total = $query->max_num_pages;
    if ($total <= 1) return '';

    $html = '<div class="ajax-pagination">';

    // Лява стрелка (chevron-left)
    if ($current_page > 1) {
        $prev_page = $current_page - 1;
        $html .= '<a href="#" class="ajax-page-link chevron chevron-left" data-page="' . $prev_page . '"></a>';
    }

    // Номера на страниците
    for ($i = 1; $i <= $total; $i++) {
        if ($i == $current_page) {
            $html .= '<span class="current-page">' . $i . '</span>';
        } else {
            $html .= '<a href="#" class="ajax-page-link" data-page="' . $i . '">' . $i . '</a>';
        }
    }

    // Дясна стрелка (chevron-right)
    if ($current_page < $total) {
        $next_page = $current_page + 1;
        $html .= '<a href="#" class="ajax-page-link chevron chevron-right" data-page="' . $next_page . '"></a>';
    }

    $html .= '</div>';
    return $html;
}



add_action('wp_footer', function () {
    if (!is_singular()) return;
    global $post;
    if (!has_shortcode($post->post_content, 'jobs_filter')) return;
    ?>
    <?php
});
