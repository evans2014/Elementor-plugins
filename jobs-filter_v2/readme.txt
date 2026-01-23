# AJAX Filter ACF Shortcode – jobs_filter
Contributors: IVB
Tags: elementor, plugin usage, audit, performance, optimization, elementor addons
Requires at least: 5.0
Tested up to: 6.7
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simple WordPress plugin that adds an AJAX filter via shortcode for the custom post type `vacancies`, based on a single ACF (Advanced Custom Fields) field.

## Description

The plugin provides the `[jobs_filter]` shortcode, which:

* Filters posts from the custom post type `vacancies`
* Filters by one ACF field
* Displays a select dropdown with field values
* Shows the number of posts for each value
* Loads filtered results using AJAX without reloading the page

## Requirements

* WordPress 5.0 or higher
* PHP 7.4 or higher
* Advanced Custom Fields (ACF) plugin activated
* Registered custom post type `vacancies`

## Installation

1. Upload the plugin folder to:
   `/wp-content/plugins/`
2. Activate the plugin from the WordPress admin panel
3. Ensure the ACF field and the `vacancies` post type exist

## Shortcode

[jobs_filter]

### How it works

* Outputs a select dropdown
* On value change, sends an AJAX request
* Updates the list of vacancies dynamically

## ACF Field

The plugin works with one ACF field.

Example configuration:

* Field type: Select
* Field name: job_technology
* Values: Technical Writer, Solution Architect, Scrum Master

Dropdown output example:

Technical Writer (5)
Solution Architect (2)
Scrum Master (7)

The number indicates how many `vacancies` posts exist for that value.

## AJAX

* Uses admin-ajax.php
* No page reload
* Optional loading indicator

## Output

After selecting a value:

* The vacancies list is updated
* Only matching posts are displayed

## Styling

The plugin outputs basic HTML markup and does not include styling.

You can add your own CSS as needed.

## Notes

* Supports only one ACF field
* Pagination is not included
* Intended for simple job listing filters


