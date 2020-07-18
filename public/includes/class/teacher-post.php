<?php
class Teacher_post
{
    public function __construct()
    {
        add_action('init', [$this, 'create_teacher_cpt'], 0);
        add_action('save_post', [$this, 'update_teacher'], 10, 3);
    }
    public function create_teacher_cpt()
    {
        $labels = array(
            'name' => _x('Teachers', 'Post Type General Name', 'oe-exam'),
            'singular_name' => _x('teacher', 'Post Type Singular Name', 'oe-exam'),
            'menu_name' => _x('Teachers', 'Admin Menu text', 'oe-exam'),
            'name_admin_bar' => _x('teacher', 'Add New on Toolbar', 'oe-exam'),
            'archives' => __('teacher Archives', 'oe-exam'),
            'attributes' => __('teacher Attributes', 'oe-exam'),
            'parent_item_colon' => __('Parent teacher:', 'oe-exam'),
            'all_items' => __('All Teachers', 'oe-exam'),
            'add_new_item' => __('Add New teacher', 'oe-exam'),
            'add_new' => __('Add New', 'oe-exam'),
            'new_item' => __('New teacher', 'oe-exam'),
            'edit_item' => __('Edit teacher', 'oe-exam'),
            'update_item' => __('Update teacher', 'oe-exam'),
            'view_item' => __('View teacher', 'oe-exam'),
            'view_items' => __('View Teachers', 'oe-exam'),
            'search_items' => __('Search teacher', 'oe-exam'),
            'not_found' => __('Not found', 'oe-exam'),
            'not_found_in_trash' => __('Not found in Trash', 'oe-exam'),
            'featured_image' => __('Featured Image', 'oe-exam'),
            'set_featured_image' => __('Set featured image', 'oe-exam'),
            'remove_featured_image' => __('Remove featured image', 'oe-exam'),
            'use_featured_image' => __('Use as featured image', 'oe-exam'),
            'insert_into_item' => __('Insert into teacher', 'oe-exam'),
            'uploaded_to_this_item' => __('Uploaded to this teacher', 'oe-exam'),
            'items_list' => __('Teachers list', 'oe-exam'),
            'items_list_navigation' => __('Teachers list navigation', 'oe-exam'),
            'filter_items_list' => __('Filter Teachers list', 'oe-exam'),
        );
        $args = array(
            'label' => __('teacher', 'oe-exam'),
            'description' => __('', 'oe-exam'),
            'labels' => $labels,
            'menu_icon' => 'dashicons-businessman',
            'supports' => array('title', 'editor', 'thumbnail'),
            'taxonomies' => array(),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'exclude_from_search' => false,
            'show_in_rest' => true,
            'publicly_queryable' => true,
            'capability_type' => 'post',
        );
        register_post_type('teacher', $args);
    }
    public function update_teacher($post_ID, $post, $update)
    {
        if ('teacher' !== $post->post_type) {
            return;
        }
        global $wpdb;
        $table = $wpdb->prefix . 'teacher';
        $res = $wpdb->update(
            $table,
            [
                'teacher_name' => $post->post_title,
            ],
            [
                'teacher_id' => $post_ID,
            ],
            [
                '%s',
            ],
            [
                '%d',
            ],
        );
        if ($res) {
            self::update_from_admin($post_ID, $post->post_title);
        } else {
            return;
        }
    }

    public static function update_from_admin($user_id, $name)
    {
        wp_update_user([
            'ID' => $user_id,
            'display_name' => $name,
        ]);
    }
}
new Teacher_post();
