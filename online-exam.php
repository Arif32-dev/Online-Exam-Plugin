<?php
/**
 * Online Exam
 *
 * @package           PluginPackage
 * @author            Arifur Rahman Arif
 * @copyright         2019 Your Name or Company Name
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Online Exam
 * Plugin URI:        https://example.com/plugin-name
 * Description:       This plugin is responsible for wordpress theme Online Exam backend functionality.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Arifur Rahman Arif
 * Author URI:        https://www.facebook.com/profile.php?id=100023045749987
 * Text Domain:       online-exam
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * @plugin start here
 */
if (!defined('ABSPATH')) {
    die('Sorry! You are not allowed to run this plugin.');
}
if (version_compare(PHP_VERSION, '7.0.0') < 0) {
    die("Sorry! You have to upgrade php version to 7.0.0 or higher.");
}
if (!class_exists('OE_Initializer')) {
    return;
}
/**
 * defining all the constant to use across the plugin
 */
define('BASE_PATH', plugin_dir_path(__FILE__));
define('BASE_URL', plugin_dir_url(__FILE__));

class OE_Initializer
{
    public function __construct()
    {
        require_once BASE_PATH . 'public/includes/callbacks/callback.php';
        $this->register_active_deactive_hooks();
        $this->plugins_check();
    }

    public function plugins_check()
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        if (is_plugin_active('online-exam/online-exam.php')) {
            $this->including_class();
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'add_action_links']);
        }
    }
    /**
     * registering activation and deactivation Hooks
     * @return void
     */
    public function register_active_deactive_hooks()
    {
        register_activation_hook(__FILE__, function () {
            Callback::plugin_activation();
        });
        register_activation_hook(__FILE__, function () {
            Callback::plugin_deactivation();
        });
    }
    /**
     * @requiring all the classes once
     * @return void
     */
    public function including_class()
    {
        require_once BASE_PATH . 'public/includes/class/scripts-styles.php';
        require_once BASE_PATH . 'public/includes/class/admin_menu.php';
        require_once BASE_PATH . 'public/includes/class/base-tab.php';
        require_once BASE_PATH . 'public/includes/class/register_settings.php';
        require_once BASE_PATH . 'public/includes/class/delete_user_role.php';
        require_once BASE_PATH . 'public/includes/class/update_user_role.php';
        require_once BASE_PATH . 'public/includes/class/teacher-post.php';

    }
    /**
     * Add plugin action links.
     * @param  array  $links List of existing plugin action links.
     * @return array         List of modified plugin action links.
     */
    public function add_action_links($links)
    {
        $mylinks = array(
            '<a href="' . admin_url('admin.php?page=online_exam') . '">Department</a>',
            '<a href="' . admin_url('admin.php?page=manage_teachers') . '">Teachers</a>',
            '<a href="' . admin_url('admin.php?page=manage_questions') . '">Questions</a>',
            '<a href="' . admin_url('admin.php?page=manage_students') . '">Students</a>',
        );
        return array_merge($links, $mylinks);
    }
}
new OE_Initializer();
