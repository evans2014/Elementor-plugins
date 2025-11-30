<?php
/**
 * Plugin Name: Dynamic acf hotels filters
 * Description: Dynamic filter for the ACF filters on Places, grid in its post and OpenStreetMap - shortcode [dynamic_acf_filters].
 * Version: 1.0
 * Author: IVB
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function daf_register_cpt_places() {
    $labels = [
        'name' => 'Place',
        'singular_name' => 'Place',
        'add_new' => 'Add Place',
        'add_new_item' => 'Ad new Place',
        'edit_item' => 'Edit Place',
        'new_item' => 'New Place',
        'all_items' => 'All Palces',
        'view_item' => 'See place',
        'search_items' => 'Search for places',
        'not_found' => 'No results found',
        'not_found_in_trash' => 'There are no spaces in the bin.о',
        'menu_name' => 'Place',
    ];
    $args = [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'places'],
        'supports' => ['title','editor','thumbnail','excerpt','custom-fields'],
        'show_in_rest' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-location-alt'
    ];
    register_post_type('places', $args);
}
add_action('init','daf_register_cpt_places');

function daf_enqueue_assets() {
    wp_enqueue_style('daf-styles', plugin_dir_url(__FILE__).'assets/css/styles.css');
    wp_enqueue_script('daf-scripts', plugin_dir_url(__FILE__).'assets/js/filters.js',['jquery'],null,true);

    wp_enqueue_style('leaflet-css','https://unpkg.com/leaflet@1.9.4/dist/leaflet.css');
    wp_enqueue_script('leaflet-js','https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',[],null,true);

    wp_localize_script('daf-scripts','daf_ajax',['ajax_url'=>admin_url('admin-ajax.php')]);
}
add_action('wp_enqueue_scripts','daf_enqueue_assets');

function daf_shortcode($atts) {
    ob_start();
    include plugin_dir_path(__FILE__).'templates/grid-template.php';
    return ob_get_clean();
}
add_shortcode('dynamic_acf_filters','daf_shortcode');


add_action('wp_ajax_daf_filter', 'daf_filter_ajax');
add_action('wp_ajax_nopriv_daf_filter', 'daf_filter_ajax');

function daf_filter_ajax() {

    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;


    $args = [
        'post_type' => 'places',
        'posts_per_page' => 6,
        'paged' => $paged,
        'post_status' => 'publish',
    ];


    $acf_fields = [];
    $field_groups = acf_get_field_groups(['post_type'=>'places']);
    foreach($field_groups as $group){
        $fields = acf_get_fields($group);
        if($fields) foreach($fields as $f){
            $acf_fields[$f['name']] = $f['key']; // key => field_key
        }
    }

    $meta_query = ['relation'=>'AND'];

    foreach($_POST as $key => $value){
        if($key === 'paged' || empty($value)) continue;
        if(isset($acf_fields[$key])){
            $meta_query[] = [
                'key' => $key,
                'value' => sanitize_text_field($value),
                'compare' => 'LIKE'
            ];
        }
    }

    if(count($meta_query) > 1) $args['meta_query'] = $meta_query;

    $query = new WP_Query($args);


    $html = '';
    $markers = [];

    if($query->have_posts()){
        while($query->have_posts()){
            $query->the_post();

            $featured_image_src = get_the_post_thumbnail(get_the_ID(),'medium');

            $html .= '<div class="daf-item">';
            if (has_post_thumbnail()) {
                $html .= get_the_post_thumbnail(get_the_ID(), 'medium', ['class' => 'daf-thumb']);
            }
            $html .= '<a href="'.get_permalink(get_the_ID()).'"> <h3 class="daf-title">' . esc_html(get_the_title()) . '</h3></a>';

            $fields = get_fields(get_the_ID());
            if ($fields) {
                $html .= '<div class="daf-fields">';
                foreach ($fields as $key => $value) {
                    
                    if (in_array($key, ['lat', 'lng', 'geocode', 'location', 'map'])) continue;
                    if (is_array($value)) {
                        if (!empty($value['address'])) {
                            $label = ucfirst(str_replace('_', ' ', $key));
                            $html .= '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($value['address']) . '</p>';
                        }
                        continue;
                    }
                    if (!empty($value)) {
                        $label = ucfirst(str_replace('_', ' ', $key));
                        $html .= '<p><strong>' . esc_html($label) . ':</strong> ' . esc_html($value) . '</p>';
                    }
                }
                $html .= '</div>';
            }
            $html .= '</div>';

            $location = get_field('open_map_location');
            if($location && isset($location['lat']) && isset($location['lng'])){
                $category = get_field('category');


                if(empty($category)){
                    $category = 'default';
                }

                $markers[] = [
                    'title' => get_the_title(),
                    'image' =>$featured_image_src,
                    'lat' => floatval($location['lat']),
                    'lng' => floatval($location['lng']),
                    'category' => $category
                ];
            }
        }
        $post_count = $query->found_posts;

        $pagination_class = ($post_count == 1) ? 'daf-pagination-single' : 'daf-pagination-multiple';
        $total_pages = $query->max_num_pages;
        if ($total_pages > 1) {
            $html .= '<div class="daf-pagination '.$pagination_class.'">';
            for ($i = 1; $i <= $total_pages; $i++) {
                $active = ($i == $paged) ? 'active' : '';
                $html .= '<button class="daf-page ' . $active . '" data-page="' . $i . '">' . $i . '</button>';
            }
            $html .= '</div>';
        }
    } else {
        $html = '<p>No results found</p>';
    }

    wp_reset_postdata();

    wp_send_json([
        'html' => $html,
        'markers' => $markers
    ]);
}
