<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* Settings page for API Key */
function egmw_add_admin_menu() {
    add_options_page( 'Google Maps Settings', 'Google Maps (EGMW)', 'manage_options', 'egmw-settings', 'egmw_settings_page' );
}
add_action( 'admin_menu', 'egmw_add_admin_menu' );

function egmw_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    if ( isset( $_POST['egmw_save'] ) ) {
        check_admin_referer( 'egmw_save_settings' );
        update_option( 'egmw_api_key', sanitize_text_field( $_POST['egmw_api_key'] ) );
        echo '<div class="updated"><p>Saved.</p></div>';
    }
    $key = get_option( 'egmw_api_key', '' );
    $api_key = 'AIzaSyBIcNrFHL2CckWa6OKlUlXOm_YRS465I38';
    ?>
    <div class="wrap">
        <h1>EGMW — Google Maps</h1>
        <form method="post">
            <?php wp_nonce_field( 'egmw_save_settings' ); ?>
            <table class="form-table">
                <tr>
                    <th>Google Maps API Key</th>
                    <td><input type="text" name="egmw_api_key" value="<?php echo esc_attr($key); ?>" style="width:400px" />
                    <p class="description">API key must have Maps JavaScript API and Geocoding API enabled.</p></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/* AJAX handler for geocoding address (server-side request to Google Geocoding API) */
add_action( 'wp_ajax_egmw_geocode', 'egmw_geocode_callback' );
function egmw_geocode_callback() {
    if ( ! current_user_can( 'edit_posts' ) ) wp_send_json_error( 'No permission' );
    check_ajax_referer( 'egmw-admin-nonce', 'nonce' );

    $address = isset( $_POST['address'] ) ? sanitize_text_field( wp_unslash( $_POST['address'] ) ) : '';
    if ( empty( $address ) ) wp_send_json_error( 'Empty address' );

    $api_key = get_option( 'egmw_api_key', '' );
    if ( empty( $api_key ) ) wp_send_json_error( 'API key not set' );

    $url = add_query_arg( array(
        'address' => rawurlencode( $address ),
        'key' => $api_key,
    ), 'https://maps.googleapis.com/maps/api/geocode/json' );

    $response = wp_remote_get( $url, array( 'timeout' => 15 ) );
    if ( is_wp_error( $response ) ) wp_send_json_error( $response->get_error_message() );

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );
    if ( ! $data || ! isset( $data['results'][0] ) ) wp_send_json_error( 'No results' );

    $loc = $data['results'][0]['geometry']['location'];
    wp_send_json_success( array( 'lat' => $loc['lat'], 'lng' => $loc['lng'] ) );
}

/* enqueue admin scripts */
function egmw_admin_scripts( $hook ) {
    global $post_type;
    if ( ( $hook === 'post-new.php' || $hook === 'post.php' ) && $post_type === 'map_location' ) {
        wp_enqueue_script( 'egmw-admin-js', EGMW_URL . 'assets/js/admin-geocode.js', array('jquery'), '1.0', true );
        wp_enqueue_script( 'egmw-admin-media', EGMW_URL . 'assets/js/admin-media-svg.js', array('jquery'), '1.0', true );
        wp_localize_script( 'egmw-admin-js', 'egmwAdmin', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'egmw-admin-nonce' ),
        ) );
        // pass some data to media script
        wp_localize_script( 'egmw-admin-media', 'egmwMedia', array(
            'title' => __('Select SVG icon','egmw'),
            'button' => __('Use this SVG','egmw'),
        ) );

        // ensure WP media scripts present
        wp_enqueue_media();
    }
}
add_action( 'admin_enqueue_scripts', 'egmw_admin_scripts' );