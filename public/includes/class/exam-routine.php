<?php
class Exam_routine
{
    public function __construct()
    {
        add_action('init', [$this, 'routine_cpt'], 0);
    }
    public function routine_cpt()
    {

        $labels = array(
            'name' => _x('Routines', 'Post Type General Name', 'oe-exam'),
            'singular_name' => _x('Routine', 'Post Type Singular Name', 'oe-exam'),
            'menu_name' => _x('Routines', 'Admin Menu text', 'oe-exam'),
            'name_admin_bar' => _x('Routine', 'Add New on Toolbar', 'oe-exam'),
            'archives' => __('Routine Archives', 'oe-exam'),
            'attributes' => __('Routine Attributes', 'oe-exam'),
            'parent_item_colon' => __('Parent Routine:', 'oe-exam'),
            'all_items' => __('All Routines', 'oe-exam'),
            'add_new_item' => __('Add New Routine', 'oe-exam'),
            'add_new' => __('Add New', 'oe-exam'),
            'new_item' => __('New Routine', 'oe-exam'),
            'edit_item' => __('Edit Routine', 'oe-exam'),
            'update_item' => __('Update Routine', 'oe-exam'),
            'view_item' => __('View Routine', 'oe-exam'),
            'view_items' => __('View Routines', 'oe-exam'),
            'search_items' => __('Search Routine', 'oe-exam'),
            'not_found' => __('Not found', 'oe-exam'),
            'not_found_in_trash' => __('Not found in Trash', 'oe-exam'),
            'featured_image' => __('Featured Image', 'oe-exam'),
            'set_featured_image' => __('Set featured image', 'oe-exam'),
            'remove_featured_image' => __('Remove featured image', 'oe-exam'),
            'use_featured_image' => __('Use as featured image', 'oe-exam'),
            'insert_into_item' => __('Insert into Routine', 'oe-exam'),
            'uploaded_to_this_item' => __('Uploaded to this Routine', 'oe-exam'),
            'items_list' => __('Routines list', 'oe-exam'),
            'items_list_navigation' => __('Routines list navigation', 'oe-exam'),
            'filter_items_list' => __('Filter Routines list', 'oe-exam'),
        );
        $args = array(
            'label' => __('Routine', 'oe-exam'),
            'description' => __('', 'oe-exam'),
            'labels' => $labels,
            'menu_icon' => 'dashicons-clock',
            'supports' => array('title'),
            'taxonomies' => array(),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'hierarchical' => true,
            'exclude_from_search' => false,
            'show_in_rest' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
        );
        register_post_type('routine', $args);
    }
}
new Exam_routine();
