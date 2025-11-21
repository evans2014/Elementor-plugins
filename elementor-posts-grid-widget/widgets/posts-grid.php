<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class EPGW_Posts_Grid_Widget extends \Elementor\Widget_Base {

    public function get_name() { return 'epgw_posts_grid'; }
    public function get_title() { return __( 'Posts Grid + Filter', 'epgw' ); }
    public function get_icon() { return 'eicon-posts-grid'; }
    public function get_categories() { return ['general']; }

    protected function register_controls() {
        $this->start_controls_section('section_query', [
            'label' => __( 'Query', 'epgw' ),
        ]);

        $post_types = get_post_types(['public' => true], 'objects');
        $pt_options = [];
        foreach ($post_types as $pt) $pt_options[$pt->name] = $pt->label;

        $this->add_control('post_type', [
            'label' => __( 'Post Type', 'epgw' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $pt_options,
            'default' => 'post',
        ]);

        $taxonomies = get_taxonomies(['public' => true], 'objects');
        $tax_options = [];
        foreach ($taxonomies as $tax) $tax_options[$tax->name] = $tax->label;

        $this->add_control('taxonomy', [
            'label' => __( 'Filter Taxonomy', 'epgw' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $tax_options,
            'default' => 'category',
        ]);
        $this->add_control(
            'selected_terms',
            [
                'label'       => __( 'Show Only These Terms', 'epgw' ),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_terms_options($s['taxonomy'] ?? 'category'),
                'label_block' => true,
                'description' => __( 'Leave empty to show all terms.', 'epgw' ),
            ]
        );

        $this->add_control('per_page', [
            'label' => __( 'Posts Per Page', 'epgw' ),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => 6,
        ]);

        $this->add_control('columns', [
            'label' => __( 'Columns', 'epgw' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [1=>1,2=>2,3=>3,4=>4,5=>5,6=>6],
            'default' => 3,
        ]);

        $this->add_control('pagination', [
            'label' => __( 'Pagination Type', 'epgw' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'none'       => __( 'None', 'epgw' ),
                'load_more'  => __( 'Load More', 'epgw' ),
                'pagination' => __( 'Pagination', 'epgw' ),
            ],
            'default' => 'pagination',
        ]);

        $this->end_controls_section();
    }

    private function get_terms_options($taxonomy) {
        $terms = get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => true,
        ]);

        $options = [];
        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                $options[$term->slug] = $term->name;
            }
        }
        return $options;
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $id = 'epgw-' . $this->get_id();

        // Get all terms
        $all_terms = get_terms([
            'taxonomy'   => $s['taxonomy'],
            'hide_empty' => true,
        ]);

        if (empty($all_terms) || is_wp_error($all_terms)) {
            echo '<p>' . __( 'No filter terms.', 'epgw' ) . '</p>';
            return;
        }

        // Filter selected terms
        $selected_slugs = !empty($s['selected_terms']) ? $s['selected_terms'] : wp_list_pluck($all_terms, 'slug');
        $terms = array_filter($all_terms, function($term) use ($selected_slugs) {
            return in_array($term->slug, $selected_slugs);
        });

        // Sort to match selected order
        $sorted_terms = [];
        foreach ($selected_slugs as $slug) {
            foreach ($terms as $term) {
                if ($term->slug === $slug) {
                    $sorted_terms[] = $term;
                    break;
                }
            }
        }
        $terms = !empty($sorted_terms) ? $sorted_terms : $terms;

        // Initial query for pagination
        $initial_query = new WP_Query([
            'post_type'      => $s['post_type'],
            'posts_per_page' => $s['per_page'],
            'paged'          => 1,
            'post_status'    => 'publish',
        ]);
        $max_pages = $initial_query->max_num_pages;
        wp_reset_postdata();

        ?>
        <div class="epgw-wrapper" id="<?php echo esc_attr($id); ?>">

            <!-- Filters -->
            <div class="epgw-filters" id="grid-filter">
                <ul class="epgw-filter-list">
                    <li class="epgw-filter active" data-term="all"><?php _e('All', 'epgw'); ?></li>
                    <?php foreach ($terms as $term): ?>
                        <li class="epgw-filter" data-term="<?php echo esc_attr($term->slug); ?>">
                            <?php echo esc_html($term->name); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <select class="epgw-filter-mobile">
                    <option value="all"><?php _e('All', 'epgw'); ?></option>
                    <?php foreach ($terms as $term): ?>
                        <option value="<?php echo esc_attr($term->slug); ?>">
                            <?php echo esc_html($term->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Grid -->
            <div class="epgw-grid" id="main-grid"
                 data-id="<?php echo esc_attr($id); ?>"
                 data-post-type="<?php echo esc_attr($s['post_type']); ?>"
                 data-taxonomy="<?php echo esc_attr($s['taxonomy']); ?>"
                 data-per-page="<?php echo esc_attr($s['per_page']); ?>"
                 data-pagination="<?php echo esc_attr($s['pagination']); ?>">
                <?php $this->render_posts($s, 1); ?>
            </div>

            <!-- Loader -->
            <div class="epgw-loader" style="display: none;">
                <div class="epgw-spinner"></div>
            </div>

            <!-- Pagination / Load More -->
            <div class="epgw-pagination-wrapper" id="pagination-wrapper">
                <?php $this->render_pagination($s, 1, $max_pages); ?>
            </div>
        </div>

        <?php
    }
    private function render_posts($s, $paged) {
        $query = new WP_Query([
            'post_type'      => $s['post_type'],
            'posts_per_page' => $s['per_page'],
            'paged'          => $paged,
            'post_status'    => 'publish',
        ]);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post(); ?>
                <div class="epgw-grid-item">
                    <a href="<?php echo the_permalink(); ?>">
                    <?php if (has_post_thumbnail()): ?>
                        <div class="epgw-thumb"><?php the_post_thumbnail('medium'); ?></div>
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

        wp_reset_postdata();
    }

    private function render_pagination($s, $current_page, $max_pages) {
        if ($s['pagination'] === 'load_more' && $max_pages > 1) {
            echo '<div class="epgw-load-more">
            <button class="epgw-load-btn" data-paged="2">' . __( 'Load More', 'epgw' ) . '</button>
        </div>';
        } elseif ($s['pagination'] === 'pagination' && $max_pages > 1) {
            echo '<div class="epgw-pagination" id="post-padgination">' .
                paginate_links([
                    'total'     => $max_pages,
                    'current'   => $current_page,
                    'type'      => 'list',
                    'prev_text' => '« Previous',
                    'next_text' => 'Next »',
                    'format'    => '?paged=%#%',
                    'base'      => '%_%',
                ]) . '</div>';
        }
    }
}