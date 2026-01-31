<?php
/**
 * Plugin Name: Project Grid
 * Description: AJAX dropdown select grid for custom post type "project".
 * Version: 1.0
 * Author: IBV
 * Text Domain: project-grid
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load textdomain for translations
function pg_load_textdomain() {
    load_plugin_textdomain('project-grid', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'pg_load_textdomain');

function pg_enqueue_scripts() {
    wp_enqueue_style('project-grid-css', plugin_dir_url(__FILE__).'assets/css/project-grid.css');
    wp_enqueue_script('project-grid-js', plugin_dir_url(__FILE__).'assets/js/project-grid.js', array('jquery'), false, true);
    wp_localize_script('project-grid-js', 'pg_ajax', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'pg_enqueue_scripts');

function pg_project_grid_shortcode($atts) {
    ob_start();
    $categories = get_terms(array(
        'taxonomy' => 'project_project',
        'hide_empty' => false,
    ));
	
    ?>

    <div class="my-loop-filters">
	   <div class="filter category-filter">
	   <label for="category-filter"><?php echo __('Лаборатория','project-grid'); ?></label>

        <select id="category-filter">
            <option value=""><?php echo __('Всички категории','project-grid'); ?></option>
            <?php foreach($categories as $cat): ?>
                <option value="<?php echo $cat->term_id; ?>">
				<?php echo $cat->name; ?>
				
				</option>
            <?php endforeach; ?>
        </select>
		</div>
		<div class="filter sort-filter">
		  <label for="sort-filte"><?php echo __('Сортирайте по','project-grid'); ?></label>
        <select id="sort-filter">
            <option value="DESC"><?php echo __('Най-нови','project-grid'); ?></option>
            <option value="ASC"><?php echo __('Най-стари','project-grid'); ?></option>
        </select>
		</div>
    </div>
    <div id="my-loop-grid">
        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'project',
            'posts_per_page' => 8,
            'orderby' => 'date',
            'order' => 'DESC',
            'paged' => $paged
        );
        $query = new WP_Query($args);
        if($query->have_posts()):
            echo '<div class="grid-wrapper">';
            while($query->have_posts()): $query->the_post();
                pg_render_project_card();
            endwhile;
            echo '</div>';
            
			echo '<div class="grid-pagination">';
			  echo paginate_links( array(
				 'next_text' => ('<img src="' . plugins_url( '/assets/svg/arrow-left-white.svg', __FILE__ ) . '" alt="Next">'),
				 'prev_text' => ('<img src="' . plugins_url( '/assets/svg/prev-arros.png', __FILE__ ) . '" alt="Prev">'),
				 'total' => $query->max_num_pages
			   ) );
		echo '</div>';
        else: ?>
		   <?php echo __('Няма проекти','project-grid'); ?>
        <?php endif;
        wp_reset_postdata();
        ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('project_grid', 'pg_project_grid_shortcode');

function pg_render_project_card() { ?>
    <a href="<?php the_permalink(); ?>" class="grid-item two-column">
	<?php if(has_post_thumbnail()): ?>
            <div class="grid-thumb">
                <?php the_post_thumbnail('medium'); ?>
            </div>
        <?php endif; ?>
        <div class="grid-content">
		<h5 class="grid-title"><?php the_title(); ?></h5>
            <div class="grid-categories">
                <?php 
                $terms = get_the_terms(get_the_ID(), 'project_project');
				
                if($terms && !is_wp_error($terms)):
                    foreach($terms as $term):
                        echo '<span class="grid-cat '. esc_attr($term->slug) .'">'. esc_html($term->name) .'</span>';
                    endforeach;
                endif;
                ?>
            </div>
            
            <div class="grid-excerpt">
                <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
            </div>
            <div class="grid-button"><?php echo __('Повече','project-grid'); ?><svg xmlns="http://www.w3.org/2000/svg" width="16.914" height="25.828" viewBox="0 0 16.914 25.828"><g id="Group_100" data-name="Group 100" transform="translate(-5668 2129.914) rotate(180)"><g id="Ellipse_4" data-name="Ellipse 4" transform="translate(-5676 2113)" fill="none" stroke-width="2" stroke="#fff"><circle cx="4" cy="4" r="4" stroke="none"/><circle cx="4" cy="4" r="3" fill="none"/></g><line id="Line_3" data-name="Line 3" x1="9" y2="9" transform="translate(-5683.5 2105.5)" fill="none" stroke-linecap="round" stroke-width="2" stroke="#fff"/><line id="Line_4" data-name="Line 4" x1="9" y1="9" transform="translate(-5683.5 2119.5)" fill="none" stroke-linecap="round" stroke-width="2" stroke="#fff"/></g></svg></div>
        </div>
        
    </a>
<?php }

add_action('wp_ajax_filter_loop_grid', 'pg_filter_loop_grid');
add_action('wp_ajax_nopriv_filter_loop_grid', 'pg_filter_loop_grid');
function pg_filter_loop_grid() {
    $category = isset($_POST['category']) ? intval($_POST['category']) : '';
    $order = isset($_POST['order']) ? $_POST['order'] : 'DESC';
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $args = array(
        'post_type' => 'project',
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => $order,
        'paged' => $paged
    );
    if($category) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'project_project',
                'field' => 'term_id',
                'terms' => $category
            )
        );
    }
    $query = new WP_Query($args);
    if($query->have_posts()):
        echo '<div class="grid-wrapper">';
        while($query->have_posts()): $query->the_post();
            pg_render_project_card();
        endwhile;
        echo '</div>';
		echo '<div class="grid-pagination">';
        			  echo paginate_links( array(
				 'next_text' => ('<img src="' . plugins_url( '/assets/svg/arrow-left-white.svg', __FILE__ ) . '" alt="Next">'),
				 'prev_text' => ('<img src="' . plugins_url( '/assets/svg/arrow-left-black.svg', __FILE__ ) . '" alt="Prev">'),
				 'total' => $query->max_num_pages
			   ) );

		echo '</div>';
    else: ?>
    <?php echo __('Няма проекти.','project-grid'); ?>
	<?php   	
   endif;
    wp_reset_postdata();
    wp_die();
}
