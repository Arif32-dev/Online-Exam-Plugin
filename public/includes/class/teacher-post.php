<?php

class Teacher_post
{
    public function __construct()
    {
        /* wordpress initialization hook createing cpt */
        add_action('init', [$this, 'create_teacher_cpt'], 0);
        /* when save post click then this hook will run */
        add_action('save_post', [$this, 'update_teacher'], 10, 3);
        /* when a post is sent into trash then this hook will run */
        add_action('wp_trash_post', [$this, 'delete_teacher']);
    }
    /**
     * @method is going to create cpt teacher post
     * @return void
     */
    public function create_teacher_cpt()
    {
        /* the cpt teacher's labels */
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
        /* the cpt teacher arguments */
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

    /**
     * @method is going to update teacher from wp_teacher table
     * @param init $post_ID @param object $post @param bool $update
     * @return void
     */
    public function update_teacher($post_ID, $post, $update)
    {
        /* if post type isn't teacher type then return */
        if ('teacher' != $post->post_type) {
            return;
        }
        global $wpdb;
        $table = $wpdb->prefix . 'teacher';
        /* updating teacher from wp_teacher */
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
        /* if wp_teacher is updated then update from wp_users table */
        if ($res) {
            self::update_from_admin($post_ID, $post->post_title);
        }
    }

    /**
     * @method is going to update teacher from wp_users table
     * @param init $user_id @param string $name
     * @return void
     */
    public static function update_from_admin($user_id, $name)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'users';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE ID=" . $user_id . "");
        if ($res) {
            $wpdb->update($table,
                [
                    'display_name' => $name,
                ],
                [
                    'ID' => $user_id,
                ],
                [
                    '%s',
                ],
                [
                    '%d',
                ]
            );
        }
    }

    /**
     * @method is going to delete teacher from wp_teacher table
     * @param init $user_id
     * @return void
     */
    public function delete_teacher($user_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'teacher';
        $res = $wpdb->get_results("SELECT teacher_id FROM " . $table . " WHERE teacher_id=" . $user_id . "");
        if ($res) {
            $delete_res = $wpdb->delete($table,
                [
                    'teacher_id' => $user_id,
                ],
                [
                    '%d',
                ]
            );
            /* if teacher is deleted from wp_teaher table then delete user from wp_users table */
            if ($delete_res) {
                self::delete_user_from_admin($user_id);
            }
        }
    }

    /**
     * @method is going to delete teacher from wp_users table
     * @param init $user_id
     * @return void
     */
    public static function delete_user_from_admin($user_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'users';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE ID=" . $user_id . "");
        if ($res) {
            $delete_res = $wpdb->delete($table,
                [
                    'ID' => $user_id,
                ],
                [
                    '%d',
                ]
            );
            /* if user is deleted from wp_users table then delete usermeta from wp_usermeta table */
            if ($delete_res) {
                self::delete_user_from_users_meta($user_id);
            }
        }
    }

    /**
     * @method is going to delete teacher from wp_usermeta table
     * @param init $user_id
     * @return void
     */
    public static function delete_user_from_users_meta($user_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'usermeta';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE user_id=" . $user_id . "");
        if ($res) {
            $wpdb->delete($table,
                [
                    'user_id' => $user_id,
                ],
                [
                    '%d',
                ]
            );
        }
    }
}
new Teacher_post();
