<?php

namespace Etn\Core\Event;

defined('ABSPATH') || exit;
/**
 * Taxonomy Class.
 * Taxonomy class for taxonomy of Event.
 * @extend Inherite class \Etn\Base\taxonomy Abstract Class
 *
 * @since 1.0.0
 */
class Tags extends \Etn\Base\Taxonomy
{

    // set custom post type name
    public function get_name()
    {
        return 'etn_tags';
    }

    public function get_cpt()
    {
        return 'etn';
    }

    // Operation custom post type
    public function flush_rewrites()
    {
    }

    function taxonomy()
    {

        $labels = array(
            'name'              => _x('Tags', 'taxonomy general name', 'eventin'),
            'singular_name'     => _x('Tags', 'taxonomy singular name', 'eventin'),
            'search_items'      => __('Search Tags', 'eventin'),
            'all_items'         => __('All Tags', 'eventin'),
            'parent_item'       => __('Parent Tags', 'eventin'),
            'parent_item_colon' => __('Parent Tags:', 'eventin'),
            'edit_item'         => __('Edit Tags', 'eventin'),
            'update_item'       => __('Update Tags', 'eventin'),
            'add_new_item'      => __('Add New Tags', 'eventin'),
            'new_item_name'     => __('New Tags Name', 'eventin'),
            'menu_name'         => __('Tags', 'eventin'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'public'            => true,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'show_in_menu'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'etn-tags'),
        );

        return $args;
    }

    public function menu()
    {

        $parent = 'etn-events-manager';
        $name = $this->get_name();
        $cpt = $this->get_cpt();
        add_action('admin_menu', function () use ($parent, $name, $cpt) {
            add_submenu_page(
                $parent, 
                esc_html__('Event Tags', 'eventin'), 
                esc_html__('Event Tags', 'eventin'), 
                'edit_posts', 
                'edit-tags.php?taxonomy=' . $name . '&post_type=' . $cpt, 
                false,
                3
            );
        });
    }
}
