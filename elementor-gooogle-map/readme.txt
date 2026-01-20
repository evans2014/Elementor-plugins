Custom Elementor Google Map Widget
==================================

Custom Elementor widget за Google Maps с поддръжка на множество маркери, popup съдържание, custom икони, marker clustering и map styles. Работи както в Elementor editor, така и на frontend.

Description
-----------

Този плъгин добавя custom Elementor widget за Google Maps.
Widget-ът позволява добавяне на множество маркери чрез Repeater, всеки с координати, заглавие, съдържание за popup и индивидуална икона.
Поддържа marker clustering и различни стилове на картата.

Features
--------

- Elementor widget за Google Maps
- Множество маркери (Repeater)
- Popup с title и content
- Custom marker icon за всеки маркер
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
- Работи в Elementor editor и frontend
- Поддържа повече от една карта на страница

Installation
------------

1. Качете папката на плъгина в:
   wp-content/plugins/
2. Активирайте плъгина от WordPress Admin → Plugins
3. Уверете се, че Elementor е активен
4. Добавете Google Maps API Key (Maps JavaScript API)

Usage
-----

1. Отворете страница с Elementor
2. Добавете widget-а "Custom Google Map"
3. Добавете маркери чрез Repeater:
   - Latitude
   - Longitude
   - Title
   - Content (popup)
   - Custom Icon (по желание)
4. Изберете zoom и map style
5. По желание активирайте clustering

Map Styles
----------

Map styles се дефинират в PHP като масив и се подават директно към Google Maps styles опцията.

Marker Clustering
-----------------

Използва се MarkerClusterer за автоматично обединяване на близки маркери.
Zoom-ът се управлява автоматично при клик върху клъстер.

Requirements
------------

- WordPress 6.0+
- Elementor 3.30+
- PHP 7.4+
- Google Maps API Key (Maps JavaScript API активиран)

Notes
-----

- Google Maps JavaScript API трябва да бъде зареден само веднъж
- MarkerClusterer се зарежда отделно
- Widget-ът поддържа повече от една карта на страница

Changelog
---------

1.0.0
- Initial release

Author
------

Custom Elementor Google Map Widget
