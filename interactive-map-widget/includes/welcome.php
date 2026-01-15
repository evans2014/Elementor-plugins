<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php esc_html_e('Seleccionar País del Mapa', 'interactive-map-widget'); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('wordpress_location_options_group');
        do_settings_sections('wordpress_location_options_group');
        submit_button(__('Guardar Cambios', 'interactive-map-widget'));
        ?>
    </form>
</div>