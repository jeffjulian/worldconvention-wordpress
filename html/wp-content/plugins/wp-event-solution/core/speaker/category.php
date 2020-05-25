<?php

namespace Etn\Core\Speaker;

defined('ABSPATH') || exit;
/**
 * Taxonomy Class.
 * Taxonomy class for taxonomy of Event.
 * @extend Inherite class \Etn\Base\taxonomy Abstract Class
 *
 * @since 1.0.0
 */
class Category extends \Etn\Base\Taxonomy
{

    // set custom post type name
    public function get_name()
    {
        return 'etn_speaker_category';
    }

    public function get_cpt()
    {
        return 'etn-speaker';
    }

    // Operation custom post type
    public function flush_rewrites()
    {
    }


    function taxonomy()
    {

        $labels = array(
            'name'              => _x('Speaker Category', 'taxonomy general name', 'eventin'),
            'singular_name'     => _x('Speaker Category', 'taxonomy singular name', 'eventin'),
            'search_items'      => __('Search Category', 'eventin'),
            'all_items'         => __('All Category', 'eventin'),
            'parent_item'       => __('Parent Category', 'eventin'),
            'parent_item_colon' => __('Parent Category:', 'eventin'),
            'edit_item'         => __('Edit Category', 'eventin'),
            'update_item'       => __('Update Category', 'eventin'),
            'add_new_item'      => __('Add New Speaker Category', 'eventin'),
            'new_item_name'     => __('New Category Name', 'eventin'),
            'menu_name'         => __('Speaker Category', 'eventin'),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_admin_column' => true,
            'show_in_menu'      => 'etn-events-manager',
            'public'            => true,
            'show_ui'           => true,
            'show_in_nav_menus' => true,
            'show_in_menu'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'etn-speaker-category'),
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
                esc_html__('Speaker categories', 'eventin'),
                esc_html__('Speaker categories', 'eventin'),
                'edit_posts',
                'edit-tags.php?taxonomy=' . $name . '&post_type=' . $cpt,
                false,
               5
            );
        });
    }
}
