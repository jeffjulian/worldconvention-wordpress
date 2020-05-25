<?php
defined('ABSPATH') || exit;

/**
 * Plugin Name:       WP Event Solution
 * Plugin URI:        https://product.themewinter.com/eventin
 * Description:       Simple and Easy to use Event Management Solution
 * Version:           1.0.3
 * Author:            Themewinter
 * Author URI:        http://themewinter.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       eventin
 * Domain Path:       /languages
 */

require_once 'autoloader.php';
require_once 'plugin.php';

define('ETN_PATH', plugin_dir_url(__FILE__));
define('ETN_DIR', untrailingslashit(plugin_dir_path(__FILE__)));
define('ETN_ASSETS', ETN_PATH . 'assets/');

// load hook for post url flush rewrites
register_activation_hook(__FILE__, [Etn\Plugin::instance(), 'flush_rewrites']);

// load plugin
add_action('plugins_loaded', function () {
	
	do_action('eventin/before_load');
	
	// action plugin instance class
	Etn\Plugin::instance()->init();
	include_once ETN_DIR . '/core/woocommerce/etn_woocommerce.php';
	
    do_action('eventin/after_load');
}, 999);
