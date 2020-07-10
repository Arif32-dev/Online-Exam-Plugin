<?php
/**
 * @class callback class
 */
if (!class_exists('Callback')) {
    return;
}
class Callback
{
    public static function plugin_activation()
    {
        flush_rewrite_rules();
        require_once BASE_PATH . 'public/includes/class/db_tables.php';
        require_once BASE_PATH . 'public/includes/class/add_user_roles.php';
        add_option('exam_folder_id', "");
    }
    public static function plugin_deactivation()
    {
        flush_rewrite_rules();
    }
    public static function add_admin_menu()
    {
        require_once BASE_PATH . 'public/includes/html/manage-dept.php';
    }
    public static function submenu_teachers()
    {
        require_once BASE_PATH . 'public/includes/html/manage-teacher.php';
    }
    public static function submenu_questions()
    {
        require_once BASE_PATH . 'public/includes/html/manage-questions.php';
    }
    public static function submenu_students()
    {
        require_once BASE_PATH . 'public/includes/html/manage-students.php';
    }
    public static function submenu_create_qus()
    {
        require_once BASE_PATH . 'public/includes/html/create_qustion.php';
    }
}
