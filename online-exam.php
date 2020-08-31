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
    return;
}
ob_clean();
ob_start();
/**
 * defining all the constant to use across the plugin
 */
define('BASE_PATH', plugin_dir_path(__FILE__));
define('BASE_URL', plugin_dir_url(__FILE__));

require_once BASE_PATH . 'vendor/autoload.php';

class OE_Initializer
{
    public function __construct()
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        if ($this->version_check() == 'version_low') {
            return;
        }
        $this->register_active_deactive_hooks();
        $this->plugins_check();
    }

    public function version_check()
    {
        if (version_compare(PHP_VERSION, '7.0.0') < 0) {
            if (is_plugin_active(plugin_basename(__FILE__))) {
                deactivate_plugins(plugin_basename(__FILE__));
                add_action('admin_notices', [$this, 'show_notice']);
            }
            return 'version_low';
        }
    }

    public function show_notice()
    {
        echo '<div class="notice notice-error is-dismissible"><h3><strong>Plugin </strong></h3><p> cannot be activated - requires at least  PHP 7.0.0 Plugin automatically deactivated.</p></div>';
        return;
    }

    public function plugins_check()
    {
        if (is_plugin_active(plugin_basename(__FILE__))) {
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
            flush_rewrite_rules();
            new \OE\includes\classes\DB_Tables;
            new \OE\includes\classes\User_Role;
        });
        register_activation_hook(__FILE__, function () {
            flush_rewrite_rules();
        });
    }

    /**
     * @requiring all the classes once
     * @return void
     */
    public function including_class()
    {

        new \OE\includes\classes\Scripts_Styles;
        new \OE\includes\classes\Admin_Menu;
        new \OE\includes\classes\Delete_Role;
        new \OE\includes\classes\Update_Role;
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
