<?php
/**
 * Plugin Name: Elementor Posts Grid with Filter
 * Description: Posts grid with AJAX filter, Load More, Pagination, responsive filters.
 * Version: 1.4
 * Author: IVB
 * Text Domain: epgw
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'EPGW_PATH', plugin_dir_path( __FILE__ ) );
define( 'EPGW_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function() {
    if ( ! did_action( 'elementor/loaded' ) ) {
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-error"><p>' .
                __( 'Elementor Posts Grid Widget requires Elementor.', 'epgw' ) .
                '</p></div>';
        });
        return;
    }

    add_action( 'elementor/widgets/register', 'epgw_register_widget' );
    function epgw_register_widget( $widgets_manager ) {
        require_once EPGW_PATH . 'widgets/posts-grid.php';
        $widgets_manager->register( new \EPGW_Posts_Grid_Widget() );
    }

    add_action( 'wp_enqueue_scripts', 'epgw_enqueue_assets' );
    function epgw_enqueue_assets() {
        wp_enqueue_style( 'epgw-style', EPGW_URL . 'assets/style.css', [], '1.4' );
        wp_enqueue_script( 'epgw-script', EPGW_URL . 'assets/script.js', ['jquery'], '1.4', true );
        wp_localize_script( 'epgw-script', 'epgw_ajax', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
        ]);
    }

    // AJAX
    add_action( 'wp_ajax_epgw_filter', 'epgw_ajax_handler' );
    add_action( 'wp_ajax_nopriv_epgw_filter', 'epgw_ajax_handler' );
    function epgw_ajax_handler() {
        $post_type = sanitize_text_field( $_POST['post_type'] ?? 'post' );
        $taxonomy  = sanitize_text_field( $_POST['taxonomy'] ?? '' );
        $term_slug = sanitize_text_field( $_POST['term'] ?? 'all' );
        $per_page  = absint( $_POST['per_page'] ?? 6 );
        $paged     = absint( $_POST['paged'] ?? 1 );
        $pagination = sanitize_text_field( $_POST['pagination'] ?? 'load_more' );

        $args = [
            'post_type'      => $post_type,
            'posts_per_page' => $per_page,
            'paged'          => $paged,
            'post_status'    => 'publish',
        ];

        if ( $term_slug !== 'all' && $taxonomy && taxonomy_exists( $taxonomy ) ) {
            $args['tax_query'] = [[
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $term_slug,
            ]];
        }

        $query = new WP_Query( $args );
        ob_start();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post(); ?>

                <div class="epgw-grid-item">
                    <a href="<?php echo the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="epgw-thumb"><?php the_post_thumbnail( 'medium' ); ?></div>
                    <?php endif; ?>
                    <div class="epgw-info">
                        <h3 class="epgw-title"><?php the_title(); ?></h3>
                        <div class="epgw-readmore">Read More <i aria-hidden="true" class="fas fa-chevron-right"></i></div>
                    </div>
                    </a>
                </div>

                <?php
            }
        } else {
            echo '<p>' . __( 'No posts found.', 'epgw' ) . '</p>';
        }

        $posts_html = ob_get_clean();

        $load_more = '';
        if ( $pagination === 'load_more' && $query->max_num_pages > $paged ) {
            $load_more = '<div class="epgw-load-more">
                <button class="epgw-load-btn" data-paged="' . ($paged + 1) . '">
                    ' . __( 'Load More', 'epgw' ) . '
                </button>
            </div>';
        }

        $pagination_html = '';
        if ( $pagination === 'pagination' && $query->max_num_pages > 1 ) {
            $pagination_html = '<div class="epgw-pagination">' .
                paginate_links([
                    'total'     => $query->max_num_pages,
                    'current'   => $paged,
                    'type'      => 'list',
                    'prev_text' => '« Previous',
                    'next_text' => 'Next »',
                    'format'    => '?paged=%#%',  // Важно!
                    'base'      => '%_%',         // Важно!
                ]) . '</div>';
        }

        wp_send_json_success([
            'posts'       => $posts_html,
            'load_more'   => $load_more,
            'pagination'  => $pagination_html,
            'max_pages'   => $query->max_num_pages,
            'current_page'=> $paged,
        ]);
    }
});