<?php
/**
 * Plugin Name: Elementor Plugins Usage Scanner
 * Description: Scans the usage of popular Elementor plugins and shows where their widgets are used.
 * Version: 1.0
 * Author: IBT
 */

if (!defined('ABSPATH')) exit;

class Elementor_Plugins_Usage_Scanner {

    private $transient_key = 'elementor_plugins_usage_results';

    // Configuration: plugin → prefixes + name
    private $plugin_config = [
        'Ultimate Addons (UAEL)' => ['uael-', 'uae-'],
        'Essential Addons'       => ['eael_'],
        'Happy Addons'           => ['ha_'],
        'JetElements / JetPlugins' => ['jet-'],
        'Premium Addons'         => ['premium-'],
        'Element Pack'           => ['epack_', 'bdt-'],
        'The Plus Addons'        => ['tp_', 'theplus_'],
        'Livemesh Addons'        => ['livemesh_'],
        'Royal Elementor Addons' => ['raven_'],
        'Dynamic.ooo'            => ['dynamic-'],
        'Other / Unknown'        => [] // will catch everything else with a prefix
    ];

    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_post_scan_elementor_plugins', [$this, 'handle_scan']);
        add_action('admin_post_clear_elementor_cache', [$this, 'handle_clear']);
        add_action('admin_post_export_elementor_csv', [$this, 'handle_export']);
    }

    public function add_admin_menu() {
        add_management_page(
            'Elementor Plugins Usage',
            'Scaner Elementor Widget',
            'manage_options',
            'elementor-plugins-usage',
            [$this, 'render_admin_page']
        );
    }

    public function handle_scan() {
        if (!current_user_can('manage_options') || !check_admin_referer('scan_elementor_nonce')) {
            wp_die('You have no rights.');
        }

        $results = $this->scan_all_plugins();
        set_transient($this->transient_key, $results, 2 * HOUR_IN_SECONDS);

        wp_safe_redirect(add_query_arg(['page' => 'elementor-plugins-usage'], admin_url('tools.php')));
        exit;
    }

    public function handle_clear() {
        if (!current_user_can('manage_options') || !check_admin_referer('clear_elementor_nonce')) {
            wp_die('You have no rights.');
        }
        delete_transient($this->transient_key);
        wp_safe_redirect(add_query_arg(['page' => 'elementor-plugins-usage'], admin_url('tools.php')));
        exit;
    }

    public function handle_export() {
        if (!current_user_can('manage_options') || !check_admin_referer('export_elementor_nonce')) {
            wp_die('You have no rights.');
        }

        $results = get_transient($this->transient_key);
        if ($results === false || empty($results)) {
            wp_die('No data to export.');
        }

        $filename = 'elementor-plugins-usage-' . date('Y-m-d') . '.csv';
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Plugin', 'Widget', 'PAage ID', 'Name', 'Type', 'URL']);

        foreach ($results as $plugin_name => $widgets) {
            foreach ($widgets as $widget_type => $pages) {
                foreach ($pages as $page) {
                    fputcsv($output, [
                        $plugin_name,
                        $widget_type,
                        $page['ID'],
                        $page['title'],
                        $page['type'],
                        $page['url'] ?: ''
                    ]);
                }
            }
        }

        fclose($output);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        exit;
    }

    private function get_plugin_for_widget($widget_type) {
        if (!is_string($widget_type)) return 'Other / Unknown';

        foreach ($this->plugin_config as $plugin_name => $prefixes) {
            if (empty($prefixes)) continue; // we're skipping "Other" for now
            foreach ($prefixes as $prefix) {
                if (strpos($widget_type, $prefix) === 0) {
                    return $plugin_name;
                }
            }
        }

        // If there is a prefix but it is not in the list
        if (preg_match('/^[a-z0-9_-]+?[-_]/', $widget_type)) {
            return 'Other / Unknown';
        }

        return null; // built-in Elementor widgets
    }

    private function extract_widget_types($elements, &$widgets) {
        if (!is_array($elements)) return;
        foreach ($elements as $el) {
            if (!empty($el['widgetType']) && is_string($el['widgetType'])) {
                $widgets[] = $el['widgetType'];
            }
            if (!empty($el['elements']) && is_array($el['elements'])) {
                $this->extract_widget_types($el['elements'], $widgets);
            }
            if (!empty($el['content']) && is_array($el['content'])) {
                $this->extract_widget_types($el['content'], $widgets);
            }
        }
    }

    private function scan_all_plugins() {
        $grouped = [];

        $posts = get_posts([
            'post_type'      => ['page', 'post','brands','case-study','brochure','blog'],
            'post_status'    => 'any',
            'numberposts'    => -1,
            'meta_query'     => [['key' => '_elementor_data', 'compare' => 'EXISTS']]
        ]);

        foreach ($posts as $post) {
            $json = get_post_meta($post->ID, '_elementor_data', true);
            if (!is_string($json) || empty($json)) continue;

            $data = json_decode($json, true);
            if (!is_array($data)) continue;

            $widgets = [];
            $this->extract_widget_types($data, $widgets);
            $widgets = array_unique($widgets);

            foreach ($widgets as $widget_type) {
                $plugin = $this->get_plugin_for_widget($widget_type);
                if ($plugin === null) continue; // we skip the built-ins

                if (!isset($grouped[$plugin])) {
                    $grouped[$plugin] = [];
                }
                if (!isset($grouped[$plugin][$widget_type])) {
                    $grouped[$plugin][$widget_type] = [];
                }

                $grouped[$plugin][$widget_type][] = [
                    'ID'    => $post->ID,
                    'title' => $post->post_title ?: '(Untitled)',
                    'type'  => $post->post_type,
                    'url'   => get_permalink($post->ID)
                ];
            }
        }

        // Sorting
        ksort($grouped);
        foreach ($grouped as $plugin => &$widgets) {
            ksort($widgets);
        }

        return $grouped;
    }

    public function render_admin_page() {
        $results = get_transient($this->transient_key);
        ?>
        <div class="wrap">
            <h1>🔍 Elementor Plugins Usage Scanner</h1>
            <p>Scans the usage of popular Elementor plugins and shows where they are used.</p>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline-block; margin-right: 10px;">
                <input type="hidden" name="action" value="scan_elementor_plugins">
                <?php wp_nonce_field('scan_elementor_nonce'); ?>
                <button type="submit" class="button button-primary">🔄 Scan now</button>
            </form>

            <?php if ($results !== false): ?>
                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline-block; margin-right: 10px;">
                    <input type="hidden" name="action" value="clear_elementor_cache">
                    <?php wp_nonce_field('clear_elementor_nonce'); ?>
                    <button type="submit" class="button">🗑️ Clear cache</button>
                </form>

                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline-block;">
                    <input type="hidden" name="action" value="export_elementor_csv">
                    <?php wp_nonce_field('export_elementor_nonce'); ?>
                    <button type="submit" class="button button-secondary">📤 Export as CSV</button>
                </form>
            <?php endif; ?>

            <?php if ($results !== false): ?>
                <hr>
                <h2>Results by plugin</h2>
                <?php if (!empty($results)): ?>
                    <?php foreach ($results as $plugin_name => $widgets): ?>
                        <h3>📦 <?php echo esc_html($plugin_name); ?></h3>
                        <table class="widefat fixed striped" style="margin-bottom: 30px;">
                            <thead>
                                <tr>
                                    <th>Widget</th>
                                    <th>Used in pages (ID / Title)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($widgets as $widget_type => $pages): ?>
                                    <tr>
                                        <td><code><?php echo esc_html($widget_type); ?></code></td>
                                        <td>
                                            <?php foreach ($pages as $page): ?>
                                                <div>
                                                    <strong><?php echo esc_html($page['ID']); ?>:</strong>
                                                    <?php if (!empty($page['url'])): ?>

                                                        <a href="<?php echo esc_url(get_permalink($page['ID'])); ?>" target="_blank">
                                                            <?php echo esc_html($page['title']); ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <?php echo esc_html($page['title']); ?>
                                                    <?php endif; ?>
                                                    (<em><?php echo esc_html($page['type']); ?></em>)
                                                </div>
                                            <?php endforeach; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="notice notice-info">
                        <p>❌ No pages found using supported Elementor plugins.</p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="notice notice-warning">
                    <p>🕗 Press "Scan Now".</p>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

new Elementor_Plugins_Usage_Scanner();