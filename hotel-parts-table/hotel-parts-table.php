<?php
/*
Plugin Name: Hotel Parts Table
Description: Table ACF repeater hotel_parts with shortcode [hotel_parts_table].
Version: 1.0
Author: IBV
*/

if ( ! defined( 'ABSPATH' ) ) exit;

function hpt_enqueue_styles() {
    wp_enqueue_style(
        'hotel-parts-css',
        plugin_dir_url(__FILE__) . 'css/hotel-parts.css',
        array(),
        '1.0'
    );
}
add_action('wp_enqueue_scripts', 'hpt_enqueue_styles');


function hotel_parts_table_shortcode() {

    if( have_rows('hotel_parts') ) {
        ob_start(); ?>

        <div class="hotel-parts-wrapper">
            <table class="hotel-parts-table">
                <thead>
                    <tr>
                        <th>Част от хотела</th>
                        <th>Площ (m²)</th>
                        <th>Брой стаи</th>
                        <th>Редове столове</th>
                        <th>Парламентарно</th>
                        <th>Правостоящи</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ( have_rows('hotel_parts') ) : the_row(); ?>
                        <tr>
                            <td><?php the_sub_field('part_name'); ?></td>
                            <td><?php the_sub_field('room_size'); ?></td>
                            <td><?php the_sub_field('number_of_rooms'); ?></td>
                            <td><?php the_sub_field('rows_of_chairs'); ?></td>
                            <td><?php the_sub_field('parliamentary'); ?></td>
                            <td><?php the_sub_field('standing'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function(){
                const headers = Array.from(document.querySelectorAll(".hotel-parts-table th")).map(th => th.textContent.trim());
                document.querySelectorAll(".hotel-parts-table tbody tr").forEach(tr => {
                    tr.querySelectorAll("td").forEach((td, i) => {
                        td.setAttribute("data-label", headers[i]);
                    });
                });
            });
        </script>

        <?php
        return ob_get_clean();
    } else {
        return '<p>Няма добавени части към хотела.</p>';
    }
}
add_shortcode('hotel_parts_table', 'hotel_parts_table_shortcode');


function acf_stars_shortcode($atts) {
    
    $atts = shortcode_atts( array(
        'field'   => 'stars',       
        'post_id' => '',            
        'max'     => 5,             
    ), $atts, 'acf_stars' );

    
    if ( empty($atts['post_id']) ) {
        global $post;
        if ( isset($post->ID) ) {
            $atts['post_id'] = $post->ID;
        } else {
            return ''; 
        }
    }

 
    $stars = get_field($atts['field'], $atts['post_id']);
    if ( ! $stars || ! is_numeric($stars) ) return '';

 
    $svg_full = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="gold">
                    <path d="M12 .587l3.668 7.571L24 9.748l-6 5.843L19.335 24 12 19.897 4.665 24 6 15.591 0 9.748l8.332-1.59z"/>
                 </svg>';

    $svg_empty = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="gold" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15 9 22 9 17 14 19 21 12 17 5 21 7 14 2 9 9 9 12 2"/>
                  </svg>';


    $output = '<div class="acf-stars" data-post-id="' . esc_attr($atts['post_id']) . '">';

    for ($i = 1; $i <= $atts['max']; $i++) {
        $output .= ($i <= $stars) ? $svg_full : $svg_empty;
    }

    $output .= '</div>';

    return $output;
}
add_shortcode('acf_stars', 'acf_stars_shortcode');

