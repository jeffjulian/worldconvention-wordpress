<?php

namespace Etn;

defined('ABSPATH') || exit;
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
/**
 * Plugin final Class.
 * Handles dynamically loading classes only when needed. CheFck Elementor Plugin.
 *
 * @since 1.0.0
 */
final class Plugin
{
    private static $instance;
    private $event;
    private $speaker;
    private $schedule;

    /**
     * __construct function
     * @since 1.0.0
     */
    public function __construct()
    {
        // load autoload method 
        Autoloader::run();
    }
    /**
     * Public function init.
     * call function for all
     *
     * @since 1.0.0
     */
    public function init()
    {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            add_action('admin_notices', array($this, 'mep_admin_notice_wc_not_active'));
        }

        // check permission for manage user
        if (current_user_can('manage_options')) {
            add_action('admin_menu', [$this, 'admin_menu']);
        }

        add_action('admin_enqueue_scripts', [$this, 'js_css_admin']);
        add_action('wp_enqueue_scripts', [$this, 'js_css_public']);
        add_action('elementor/frontend/before_enqueue_scripts', [$this, 'elementor_js']);

        $this->event = Core\Event\Base::instance();
        Core\Event\Base::instance()->init();

        // working schedule module
        $this->schedule = Core\Schedule\Base::instance();
        Core\Schedule\Base::instance()->init();

        // working speaker module
        $this->speaker = Core\Speaker\Base::instance();
        Core\Speaker\Base::instance()->init();

        Core\Woocommerce\Base::instance()->init();
        Core\Shortcodes\Hooks::instance()->init();

        // working get instance of elementor widget
        Widgets\Manifest::get_instance()->init();

        //make admin menu open if any custom taxonomy is selected
        add_action('parent_file', [$this, 'keep_taxonomy_menu_open']);
    }
    
    public function keep_taxonomy_menu_open($parent_file) {
        global $current_screen;
        $taxonomy = $current_screen->taxonomy;
        if ($taxonomy == 'etn_category' || $taxonomy == 'etn_tags' || $taxonomy == 'etn_speaker_category')
          $parent_file = 'etn-events-manager';
        return $parent_file;
      }


    public function mep_admin_notice_wc_not_active()
    {
        if (file_exists(WP_PLUGIN_DIR . '/woocommerce/woocommerce.php')) {
            $btn['label'] = esc_html__('Activate WooCommerce', 'eventin');
            $btn['url'] = wp_nonce_url('plugins.php?action=activate&plugin=woocommerce/woocommerce.php&plugin_status=all&paged=1', 'activate-plugin_woocommerce/woocommerce.php');
        } else {
            $btn['label'] = esc_html__('Install WooCommerce', 'eventin');
            $btn['url'] = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=woocommerce'), 'install-plugin_woocommerce');
        }

        Utils\Notice::push(
            [
                'id'          => 'unsupported-woocommerce-version',
                'type'        => 'error',
                'dismissible' => true,
                'btn'          => $btn,
                'message'     => sprintf(esc_html__('Eventin requires WooCommerce to get all features, which is currently NOT RUNNING.', 'eventin')),
            ]
        );
    }

    /**
     * Public function version.
     * set for plugin version 
     *
     * @since 1.0.0
     */
    public function version()
    {
        return '1.0.0';
    }
    /**
     * Public function package_type.
     * set for plugin package type 
     *
     * @since 1.0.0
     */
    public function package_type()
    {
        return 'free';
    }

    /**
     * Public function plugin_url.
     * set for plugin url 
     *
     * @since 1.0.0
     */
    public function plugin_url()
    {
        return trailingslashit(plugin_dir_url(__FILE__));
    }

    /**
     * Public function plugin_dir.
     * set for plugin dir 
     *
     * @since 1.0.0
     */
    public function plugin_dir()
    {
        return trailingslashit(plugin_dir_path(__FILE__));
    }

    /**
     * Public function core_url .
     * set for plugin  core folder url 
     *
     * @since 1.0.0
     */
    public function core_url()
    {
        return $this->plugin_url() . 'core/';
    }

    /**
     * Public function core_dir .
     * set for plugin  core folder dir 
     *
     * @since 1.0.0
     */
    public function core_dir()
    {
        return $this->plugin_dir() . 'core/';
    }

    /**
     * Public function base_url .
     * set for plugin  base folder url 
     *
     * @since 1.0.0
     */
    public function base_url()
    {
        return $this->plugin_url() . 'base/';
    }

    /**
     * Public function base_dir .
     * set for plugin  base folder dir 
     *
     * @since 1.0.0
     */
    public function base_dir()
    {
        return $this->plugin_dir() . 'base/';
    }

    /**
     * Public function utils_url .
     * set for plugin  utils folder url 
     *
     * @since 1.0.0
     */
    public function utils_url()
    {
        return $this->plugin_url() . 'utils/';
    }

    /**
     * Public function utils_dir .
     * set for plugin  utils folder dir 
     *
     * @since 1.0.0
     */
    public function utils_dir()
    {
        return $this->plugin_dir() . 'utils/';
    }

    /**
     * Public function widgets_url .
     * set for plugin  widget folder url 
     *
     * @since 1.0.0
     */
    public function widgets_url()
    {
        return $this->plugin_url() . 'widgets/';
    }

    /**
     * Public function widgets_dir .
     * set for plugin  widget folder dir 
     *
     * @since 1.0.0
     */
    public function widgets_dir()
    {
        return $this->plugin_dir() . 'widgets/';
    }

    public function text_domain()
    {
        return 'eventin';
    }

    /**
     * Public function js_css_public .
     * Include public function
     *
     * @since 1.0.0
     */
    public function js_css_public()
    {
        wp_enqueue_style('etn-public-css', ETN_ASSETS . 'css/event-manager-public.css', array(), $this->version(), 'all');
        wp_enqueue_style('fontawesome', ETN_ASSETS . 'css/font-awesome.css', array(), '5.0', 'all');

        wp_enqueue_script('etn-public', ETN_ASSETS . 'js/event-manager-public.js',array('jquery'), $this->version(), true);
    }

    public function elementor_js()
    {
        wp_enqueue_script('etn-elementor-inputs', ETN_ASSETS . 'js/elementor.js', array('elementor-frontend'), $this->version(), true);
    }

    public function js_css_admin()
    {

        // get screen id
        $screen = get_current_screen();
        $form_cpt = $this->event;

        wp_enqueue_style('thickbox');
        wp_enqueue_style('select2', ETN_ASSETS . 'css/select2.min.css', array(), '4.0.10', 'all');

        wp_enqueue_style('fontawesome', ETN_ASSETS . 'css/font-awesome.css', array(), '5.0', 'all');
        wp_enqueue_style('etn-ui', ETN_ASSETS . 'css/etn-ui.css', array(), $this->version(), 'all');
        wp_enqueue_style('jquery-ui', ETN_ASSETS . 'css/jquery-ui.css', array(), $this->version(), 'all');
        wp_enqueue_style('', ETN_ASSETS . 'css/event-manager-admin.css', array(), $this->version(), 'all');



        if (!did_action('wp_enqueue_media')) {
            wp_enqueue_media();
        }

        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_script('jquery-ui-datepicker');

        // wp_enqueue_style('jquery-ui');
        wp_enqueue_script('jquery-ui', ETN_ASSETS . 'js/etn-ui.min.js', array('jquery'), '4.0.10', true);
        wp_enqueue_script('popper', ETN_ASSETS . 'js/Popper.js', array('jquery'), '4.0.10', false);
        wp_enqueue_script('etn', ETN_ASSETS . 'js/event-manager-admin.js', array('jquery'), $this->version(), false);
        wp_enqueue_script('select2', ETN_ASSETS . 'js/select2.min.js', array('jquery'), '4.0.10', false);
        wp_enqueue_script('jquery-repeater', ETN_ASSETS . 'js/jquery.repeater.min.js', array('jquery'), '4.0.10', true);
    }

    function admin_menu()
    {
        $admin_page = new \Etn\Core\Event\Pages\Event_Admin_Page();
        $admin_page->add_admin_pages();
        $settings = new Core\Event\Settings('etn', $this->version());
    }

    public function flush_rewrites()
    {
        $event = new Core\Event\Cpt();
        $event->flush_rewrites();
        $speaker = new Core\Speaker\Cpt();
        $speaker->flush_rewrites();
        $schedule = new Core\Schedule\Cpt();
        $schedule->flush_rewrites();
        $this->_action_create_table();
    }


    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function _action_create_table()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'etn_events';
        $charset_collate = $wpdb->get_charset_collate();
        // create table for donation 
        if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            // create fundraising table
            $wdp_sql = "CREATE TABLE IF NOT EXISTS `$tableName` (
			  `event_id` mediumint(9) NOT NULL AUTO_INCREMENT,
			  `post_id` bigint(20) NOT NULL COMMENT 'This id is teh event id',
			  `form_id` bigint(20) NOT NULL COMMENT 'This id From wp post table',
			  `invoice` varchar(150) NOT NULL,
			  `event_amount` double NOT NULL DEFAULT '0',
			  `user_id` mediumint(9) NOT NULL,
			  `email` varchar(200) NOT NULL,
			  `event_type` ENUM('ticket') DEFAULT 'ticket',
			  `payment_type` ENUM('woocommerce') DEFAULT 'woocommerce',
			  `pledge_id` varchar(20) NOT NULL DEFAULT '0',
			  `payment_gateway` ENUM('offline_payment', 'online_payment', 'bank_payment', 'check_payment', 'stripe_payment', 'other_payment') default 'online_payment',
			  `date_time` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  `status` ENUM('Active', 'Pending', 'Review', 'Refunded', 'DeActive', 'Delete') DEFAULT 'Pending',
			  PRIMARY KEY (`event_id`)
			) $charset_collate;";

            dbDelta($wdp_sql);

            // create meta table	
            $tableNameMeta = $wpdb->prefix . 'etn_trans_meta';

            $wdp_meta = "
				CREATE TABLE IF NOT EXISTS `$tableNameMeta`(
					`meta_id` mediumint NOT NULL AUTO_INCREMENT,
					`event_id` mediumint NOT NULL,
					`meta_key` varchar(255),
					`meta_value` longtext,
					PRIMARY KEY(`meta_id`)
				) $charset_collate;
			";
            dbDelta($wdp_meta);

            update_option('etn_version', 1.1);
        }
    }
}
