<?php

namespace Etn\Core\Event;

defined('ABSPATH') || exit;
/**
 * Cpt Class.
 * Cpt class for custom post type of Event.
 * @extend Inherite class \Etn\Base\Cpt Abstract Class
 *
 * @since 1.0.0
 */
class Cpt extends \Etn\Base\Cpt
{
    // set custom post type name
    public function get_name()
    {
        return 'etn';
    }

    // set custom post type options data
    public function post_type()
    {
        $options = $this->user_modifiable_option();
        $labels = array(
            'name'                  => esc_html_x('Eventin Events', 'Post Type General Name', 'eventin'),
            'singular_name'         => apply_filters('wp_eventlty_singular_name', $options['wp_eventlty_singular_name']),
            'menu_name'             => esc_html__('Event', 'eventin'),
            'name_admin_bar'        => esc_html__('Event', 'eventin'),
            'archives'              => apply_filters('etn_event_archive', $options['etn_event_archive']),
            'attributes'            => esc_html__('Event Attributes', 'eventin'),
            'parent_item_colon'     => esc_html__('Parent Item:', 'eventin'),
            'all_items'             => apply_filters('etn_all_items', $options['etn_event_all']),
            'add_new_item'          => apply_filters('etn_add_new_item', 'Add new Event'),
            'add_new'               => esc_html__('Add New', 'eventin'),
            'new_item'              => esc_html__('New Event', 'eventin'),
            'edit_item'             => esc_html__('Edit Event', 'eventin'),
            'update_item'           => esc_html__('Update Event', 'eventin'),
            'view_item'             => esc_html__('View Event', 'eventin'),
            'view_items'            => esc_html__('View Events', 'eventin'),
            'search_items'          => esc_html__('Search Events', 'eventin'),
            'not_found'             => esc_html__('Not found', 'eventin'),
            'not_found_in_trash'    => esc_html__('Not found in Trash', 'eventin'),
            'featured_image'        => esc_html__('Featured Image', 'eventin'),
            'set_featured_image'    => esc_html__('Set featured image', 'eventin'),
            'remove_featured_image' => esc_html__('Remove featured image', 'eventin'),
            'use_featured_image'    => esc_html__('Use as featured image', 'eventin'),
            'insert_into_item'      => esc_html__('Insert into Event', 'eventin'),
            'uploaded_to_this_item' => esc_html__('Uploaded to this Event', 'eventin'),
            'items_list'            => esc_html__('Events list', 'eventin'),
            'items_list_navigation' => esc_html__('Events list navigation', 'eventin'),
            'filter_items_list'     => esc_html__('Filter froms list', 'eventin'),
        );
        $rewrite = array(
            'slug'                  => apply_filters('wp_eventlty_slug', $options['etn_slug']),
            'with_front'            => true,
            'pages'                 => true,
            'feeds'                 => false,
        );
        $args = array(
            'label'                 => esc_html__('Events', 'eventin'),
            'description'           => esc_html__('Event', 'eventin'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail'),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_admin_column'     => false,
            'show_in_menu'          => 'etn-events-manager',
            'menu_icon'             => 'dashicons-text-page',
            'menu_position'         => 10,
            'show_in_admin_bar'     => false,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'publicly_queryable'    => true,
            'rewrite'               => $rewrite,
            'query_var' => true,
            'exclude_from_search'   =>  $options['etn_include_from_search'],
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => false,
            'rest_base'             => $this->get_name(),

        );

        return $args;
    }

    // Operation custom post type
    public function flush_rewrites()
    {

        $name = $this->get_name();
        $args = $this->post_type();
        $args = apply_filters('wp_eventlty_args', $args);
        register_post_type($name, $args);
        flush_rewrite_rules();
    }

    private function user_modifiable_option()
    {
        $settings_options = get_option('etn_event_options');

        $options = [
            'wp_eventlty_singular_name' => 'Event',
            'etn_event_archive' => 'Event Archive',
            'etn_event_all' => 'Events',
            'etn_slug' => 'etn',
            'etn_include_from_search' => true
        ];
        if (isset($settings_options['wp_eventlty_singular_name']) && $settings_options['wp_eventlty_singular_name'] != '') {
            $options['wp_eventlty_singular_name'] = $settings_options['wp_eventlty_singular_name'];
        }
        if (isset($settings_options['etn_event_archive']) && $settings_options['etn_event_archive'] != '') {
            $options['etn_event_archive'] = $settings_options['etn_event_archive'];
        }
        if (isset($settings_options['etn_event_all']) && $settings_options['etn_event_all'] != '') {
            $options['etn_event_all'] = $settings_options['etn_event_all'];
        }
        if (isset($settings_options['etn_slug']) && $settings_options['etn_slug'] != '') {
            $options['etn_slug'] = $settings_options['etn_slug'];
        }
        if (isset($settings_options['etn_include_from_search']) && $settings_options['etn_include_from_search'] != '') {
            $options['etn_include_from_search'] = (bool) $settings_options['etn_include_from_search'];
        }

        return $options;
    }

    function event_taxonomies()
    {
        $labels = array(
            'name'              => _x('Category', 'taxonomy general name', 'eventin'),
            'singular_name'     => _x('Category', 'taxonomy singular name', 'eventin'),
            'search_items'      => __('Search Category', 'eventin'),
            'all_items'         => __('All Category', 'eventin'),
            'parent_item'       => __('Parent Category', 'eventin'),
            'parent_item_colon' => __('Parent Category:', 'eventin'),
            'edit_item'         => __('Edit Category', 'eventin'),
            'update_item'       => __('Update Category', 'eventin'),
            'add_new_item'      => __('Add New Category', 'eventin'),
            'new_item_name'     => __('New Category Name', 'eventin'),
            'menu_name'         => __('Category', 'eventin'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'public'            => true,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'show_in_menu'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'etn-category'),
        );

        register_taxonomy('etn-category', $this->get_name(), $args);

        unset($args);
        unset($labels);
    }
}
