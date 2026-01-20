Custom Elementor Google Map Widget
======================================

Custom Elementor widget for Google Maps with support for multiple markers, popup content, custom icons, marker clustering and map styles. Works both in the Elementor editor and on the frontend.

Description
This plugin adds a custom Elementor widget for Google Maps.
The widget allows adding multiple markers via Repeater, each with coordinates, title, popup content and individual icon.
Supports marker clustering and different map styles.

Features
- Elementor widget for Google Maps
- Multiple markers (Repeater)
- Popup with title and content
- Custom marker icon for each marker
- Marker clustering
- Auto zoom / fitBounds
- Map styles:
    - Default
    - Silver
    - Retro
    - Dark
    - Night
    - Aubergine
- Custom JSON
- Works in Elementor editor and frontend
- Supports more than one map per page

Installation
1. Upload the plugin folder to:
wp-content/plugins/

2. Activate the plugin from WordPress Admin → Plugins

3. Make sure Elementor is active
4. Add Google Maps API Key (Maps JavaScript API)

Usage
1. Open a page with Elementor
2. Add the "Custom Google Map" widget
3. Add markers via Repeater:
- Latitude
- Longitude
- Title
- Content (popup)
- Custom Icon (optional)
4. Select zoom and map style
5. Optionally enable clustering

Map Styles
Map styles are defined in PHP as an array and are passed directly to the Google Maps styles option.

Marker Clustering
MarkerClusterer is used to automatically group nearby markers.
Zoom is automatically controlled when clicking on a cluster.

Requirements
- WordPress 6.0+
- Elementor 3.30+
- PHP 7.4+
- Google Maps API Key (Maps JavaScript API enabled)

Notes
- ​​Google Maps JavaScript API needs to be loaded only once
- MarkerClusterer is loaded separately
- Widget supports more than one map per page

Changelog

1.0.0
- Initial release

