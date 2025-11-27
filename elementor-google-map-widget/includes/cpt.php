<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function egmw_register_cpt() {
    $labels = array(
        'name' => __( 'Map Locations', 'egmw' ),
        'singular_name' => __( 'Map Location', 'egmw' ),
        'add_new_item' => __( 'Add Location', 'egmw' ),
    );
    $args = array(
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-location-alt',
        'supports' => array( 'title' ),
        'capability_type' => 'post',
    );
    register_post_type( 'map_location', $args );
}
add_action( 'init', 'egmw_register_cpt' );

/* Meta boxes for coordinates, address, icon, popup content */
function egmw_meta_boxes() {
    add_meta_box( 'egmw_location_meta', __( 'Location data', 'egmw' ), 'egmw_location_meta_callback', 'map_location', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'egmw_meta_boxes' );

function egmw_location_meta_callback( $post ) {
    wp_nonce_field( 'egmw_save_location', 'egmw_location_nonce' );
    $lat = get_post_meta( $post->ID, '_egmw_lat', true );
    $lng = get_post_meta( $post->ID, '_egmw_lng', true );
    $address = get_post_meta( $post->ID, '_egmw_address', true );
    $icon = get_post_meta( $post->ID, '_egmw_icon', true ); // legacy filename from assets/svg/
    $svg_id = get_post_meta( $post->ID, '_egmw_svg_id', true ); // attachment ID
    $svg_url = get_post_meta( $post->ID, '_egmw_svg_url', true ); // cached URL
    $content = get_post_meta( $post->ID, '_egmw_popup', true );

    // gather SVG files from assets folder as fallback
    $svg_dir = EGMW_PATH . 'assets/svg';
    $svgs = array();
    if ( is_dir( $svg_dir ) ) {
        $files = scandir( $svg_dir );
        foreach ( $files as $f ) {
            if ( preg_match( '/\.svg$/i', $f ) ) $svgs[] = $f;
        }
    }

    ?>
    <p>
        <label><?php _e('Address (optional)', 'egmw'); ?></label><br>
        <input type="text" name="egmw_address" value="<?php echo esc_attr($address); ?>" style="width:100%" />
    </p>
    <p>
        <label><?php _e('Latitude', 'egmw'); ?></label><br>
        <input type="text" name="egmw_lat" id="egmw_lat" value="<?php echo esc_attr($lat); ?>" />
        <label style="margin-left:10px"><?php _e('Longitude', 'egmw'); ?></label>
        <input type="text" name="egmw_lng" id="egmw_lng" value="<?php echo esc_attr($lng); ?>" />
        <button class="button" id="egmw_geocode_btn" style="margin-left:10px;"><?php _e('Geocode address', 'egmw'); ?></button>
    </p>
    <p>
        <label><?php _e('Popup HTML content', 'egmw'); ?></label><br>
        <textarea name="egmw_popup" rows="5" style="width:100%"><?php echo esc_textarea($content); ?></textarea>
    </p>

    <hr/>

    <p><strong><?php _e('SVG Icon (upload/select from Media Library)', 'egmw'); ?></strong></p>
    <p>
        <button type="button" class="button" id="egmw_select_svg"><?php _e('Избери SVG иконка', 'egmw'); ?></button>
        <button type="button" class="button" id="egmw_remove_svg" <?php if ( empty($svg_id) && empty($svg_url) && empty($icon) ) echo 'style="display:none"'; ?>><?php _e('Премахни', 'egmw'); ?></button>
    </p>
    <p id="egmw_svg_preview">
        <?php if ( $svg_id ) : 
            $att = wp_get_attachment_image_src( $svg_id );
            $url = $svg_url ? $svg_url : wp_get_attachment_url( $svg_id );
            if ( $url ) : ?>
                <img src="<?php echo esc_url( $url ); ?>" style="width:48px;height:48px;vertical-align:middle" />
            <?php endif;
        elseif ( $svg_url ) : ?>
            <img src="<?php echo esc_url( $svg_url ); ?>" style="width:48px;height:48px;vertical-align:middle" />
        <?php elseif ( $icon ) : ?>
            <img src="<?php echo esc_url( EGMW_URL . 'assets/svg/' . $icon ); ?>" style="width:48px;height:48px;vertical-align:middle" />
        <?php else: ?>
            <em><?php _e('Няма избрана иконка', 'egmw'); ?></em>
        <?php endif; ?>
    </p>

    <input type="hidden" name="egmw_svg_id" id="egmw_svg_id" value="<?php echo esc_attr( $svg_id ); ?>" />
    <input type="hidden" name="egmw_svg_url" id="egmw_svg_url" value="<?php echo esc_attr( $svg_url ); ?>" />
    <input type="hidden" name="egmw_icon" id="egmw_icon_legacy" value="<?php echo esc_attr( $icon ); ?>" />

    <hr/>

    <p><strong><?php _e('Or choose built-in SVG (fallback)', 'egmw'); ?></strong></p>
    <p>
        <?php foreach ( $svgs as $s ) : ?>
            <?php $checked = ($icon === $s) ? 'checked' : ''; ?>
            <label style="margin-right:10px;">
                <input type="radio" name="egmw_icon" value="<?php echo esc_attr($s); ?>" <?php echo $checked; ?> />
                <img src="<?php echo esc_url( EGMW_URL . 'assets/svg/' . $s ); ?>" style="width:28px;height:28px;vertical-align:middle" />
            </label>
        <?php endforeach; ?>
    </p>

    <?php
}

function egmw_save_location( $post_id ) {
    if ( ! isset( $_POST['egmw_location_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['egmw_location_nonce'], 'egmw_save_location' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( 'map_location' !== get_post_type( $post_id ) ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['egmw_lat'] ) ) update_post_meta( $post_id, '_egmw_lat', sanitize_text_field( $_POST['egmw_lat'] ) );
    if ( isset( $_POST['egmw_lng'] ) ) update_post_meta( $post_id, '_egmw_lng', sanitize_text_field( $_POST['egmw_lng'] ) );
    if ( isset( $_POST['egmw_address'] ) ) update_post_meta( $post_id, '_egmw_address', sanitize_text_field( $_POST['egmw_address'] ) );
    // legacy asset filename
    if ( isset( $_POST['egmw_icon'] ) ) update_post_meta( $post_id, '_egmw_icon', sanitize_text_field( $_POST['egmw_icon'] ) );

    // SVG attachment ID and URL (media upload)
    if ( isset( $_POST['egmw_svg_id'] ) ) {
        $svg_id = intval( $_POST['egmw_svg_id'] );
        if ( $svg_id ) {
            update_post_meta( $post_id, '_egmw_svg_id', $svg_id );
            $url = wp_get_attachment_url( $svg_id );
            if ( $url ) update_post_meta( $post_id, '_egmw_svg_url', esc_url_raw( $url ) );
        } else {
            delete_post_meta( $post_id, '_egmw_svg_id' );
            delete_post_meta( $post_id, '_egmw_svg_url' );
        }
    }

    if ( isset( $_POST['egmw_popup'] ) ) update_post_meta( $post_id, '_egmw_popup', wp_kses_post( $_POST['egmw_popup'] ) );
}
add_action( 'save_post', 'egmw_save_location' );