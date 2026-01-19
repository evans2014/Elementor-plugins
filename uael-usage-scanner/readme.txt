=== Elementor Plugins Usage Scanner ===
Contributors: IVB
Tags: elementor, plugin usage, audit, performance, optimization, elementor addons
Requires at least: 5.0
Tested up to: 6.7
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Scan your site to see exactly which Elementor plugins (UAEL, Essential Addons, JetElements, etc.) are used—and on which pages.

== Description ==

This plugin helps you **audit and optimize** Elementor-based websites by scanning all pages, templates, and posts to detect:

- Which **Elementor addon plugins** are actively used (Ultimate Addons, Essential Addons, Happy Addons, JetElements, and more)
- **Which specific pages** use each widget
- Whether any plugins are **unused** and can be safely deactivated

Perfect for:
- Web agencies managing multiple client sites
- Performance optimization (removing unused plugins)
- Site cleanup or migration projects

Supported plugins include:
- Ultimate Addons for Elementor (UAEL)
- Essential Addons for Elementor
- Happy Addons
- JetElements / JetPlugins (Crocoblock)
- Premium Addons for Elementor
- Element Pack
- The Plus Addons for Elementor
- Livemesh Addons for Elementor
- Royal Elementor Addons
- Dynamic.ooo
- And other plugins with known widget prefixes

== Features ==

✅ Scan all Elementor pages, posts, and templates  
✅ Group results by plugin vendor  
✅ Show exact pages using each widget  
✅ Clean vertical layout for easy reading  
✅ Export full report as CSV  
✅ Clear cache with one click  
✅ No external dependencies  

== Installation ==

1. Download and unzip the plugin.
2. Upload the `elementor-plugins-usage-scanner` folder to your `/wp-content/plugins/` directory.
3. Activate the plugin through the **Plugins** menu in WordPress.
4. Go to **Tools → Elementor Plugins** to start scanning.

== Frequently Asked Questions ==

= Does it work with the latest Elementor versions? =

Yes. The plugin is compatible with Elementor 3.0+ and all major addons.

= Why don’t some plugins appear in the report? =

The scanner only detects widgets added **via the Elementor editor**. Shortcodes used in the Classic Editor or Gutenberg blocks won’t be detected.

= Can I add support for a new plugin? =

Yes! Open `elementor-plugins-usage-scanner.php` and add the widget prefix to the `$plugin_config` array.

= Is this plugin safe? =

Yes. It runs only in the admin area, requires `manage_options` capability, and **never modifies** your content—only reads it.

== Changelog ==

= 1.0 =
- Initial release
- Supports 10+ popular Elementor addon plugins
- Scan, clear cache, and CSV export functionality

== Upgrade Notice ==

= 1.0 =
New plugin for auditing Elementor addon usage. Recommended for anyone using multiple Elementor extensions.