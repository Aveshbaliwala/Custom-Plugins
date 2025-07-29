<?php
/**
 * Plugin Name: Category Filter Plugin
 * Description: Displays a frontend category filter for posts or products.
 * Version: 23.08.0
 * Author: Avesh Baliwala
 * Text Domain: category-filter
 */

defined('ABSPATH') or exit;

// Constants
define('CFP_PATH', plugin_dir_path(__FILE__));
define('CFP_URL', plugin_dir_url(__FILE__));

// Includes
require_once CFP_PATH . 'assets/css/style.css';
require_once CFP_PATH . 'assets/js/custom.js';
require_once CFP_PATH . 'includes/activate-deactivate.php';
require_once CFP_PATH . 'includes/settings-page.php';
require_once CFP_PATH . 'includes/filter-display.php';

// Load textdomain
add_action('plugins_loaded', function () {
    load_plugin_textdomain('category-filter', false, dirname(plugin_basename(__FILE__)) . '/languages');
});

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cfp_add_settings_link');

function cfp_add_settings_link($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=cfp-settings') . '">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

