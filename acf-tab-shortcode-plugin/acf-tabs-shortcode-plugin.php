<?php
/**
 * Plugin Name: ACF repeater - Tabs Shortcode Plugin
 * Description: Plugin shortcode ACF - Tabs Elementor
 * Version: 1.0
 * Author: IVB 
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue CSS и JS
 */
function msp_enqueue_assets() {
    wp_enqueue_style(
        'msp-style',
        plugin_dir_url( __FILE__ ) . 'assets/css/style.css',
        [],
        '1.0'
    );

    wp_enqueue_script(
        'msp-script',
        plugin_dir_url( __FILE__ ) . 'assets/js/script.js',
        ['jquery'],
        '1.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'msp_enqueue_assets' );

/**
 * Shortcode функция
 */

add_shortcode('acf_repeater_tabs', 'display_acf_repeater_tabs');

function display_acf_repeater_tabs() {

    $post_id = get_the_ID();
    $repeater = get_field( 'images',$post_id  );

       if( empty( $repeater ) ) {
			echo '<p>No images found.</p>';
			return;
		}
    ?>
        <div class="custom-tabs">
            <div class="tab-buttons">
                <?php foreach ($repeater as $index => $row): ?>
                    <button
                        class="tab-btn <?php echo $index === 0 ? 'active' : ''; ?>"
                        data-tab="tab-<?php echo $index; ?>">
                        <?php echo esc_html($row['title']); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <?php foreach ($repeater as $index => $row):
                $image = $row['image_for_tabs'];
                    $image_url = '';
                    $image_alt = '';

                    if ( is_array( $image ) ) {
                        // If is array (Return Format: Image Array)
                        $image_url = $image['url'] ?? '';
                        $image_alt = $image['alt'] ?? '';
                    } elseif ( is_numeric( $image ) ) {
                        // If is ID (Return Format: Image ID)
                        $image_url = wp_get_attachment_image_url( $image, 'large' );
                        $image_alt = get_post_meta( $image, '_wp_attachment_image_alt', true );
                    } elseif ( is_string( $image ) ) {
                        // If it is direct URL (Return Format: Image URL)
                        $image_url = $image;
                        $image_alt = '';
                    }
            ?>

                <div class="tab-content <?php echo $index === 0 ? 'active' : ''; ?>" id="tab-<?php echo $index; ?>">
                    <a href="<?php echo esc_url($image_url); ?>"
					   data-elementor-open-lightbox="yes"
					   data-elementor-lightbox-slideshow="acf-tabs-<?php echo esc_attr($post_id); ?>"
					   data-elementor-lightbox-title="<?php echo esc_attr($row['title']); ?>">
					   <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" />
					</a>
                </div>
            <?php endforeach; ?>

        </div>
<?php
}
?>