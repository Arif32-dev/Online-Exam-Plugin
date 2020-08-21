<?php
class OE_uninstall
{
    public function __construct()
    {
        $all_sql = $this->all_delete_sql();
        if (!empty($all_sql)) {
            foreach ($all_sql as $sql) {
                $this->delete_tables($sql);
            }
        }
        $this->delete_options();
    }
    public function delete_options()
    {
        $settings = array(
            /* theme setting array */
            array(
                'parent_option_group' => 'oe-theme-set',
                'option_name' => 'mailer_gmail',
            ),
            array(
                'parent_option_group' => 'oe-theme-set',
                'option_name' => 'mailer_pass',
            ),
        );
        foreach ($settings as $setting) {
            unregister_setting($setting['parent_option_group'], $setting['option_name']);
        }
        delete_option('mailer_gmail');
        delete_option('mailer_pass');
    }
    public function db_connection()
    {
        $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        return $connection;
    }
    public function delete_tables($sql)
    {
        $connection = $this->db_connection();
        if ($connection) {
            $connection->query($sql);
        }
    }
    public function db_tables()
    {
        global $wpdb;
        $tables = [
            $wpdb->prefix . 'department',
            $wpdb->prefix . 'teacher',
            $wpdb->prefix . 'question_folder',
            $wpdb->prefix . 'qustions',
            $wpdb->prefix . 'exam_routine',
            $wpdb->prefix . 'result',
        ];
        return $tables;
    }
    public function all_delete_sql()
    {
        $sql = [];
        $db_tables = $this->db_tables();
        if (!empty($db_tables)) {
            foreach ($db_tables as $table) {
                $single_sql = "DROP TABLE IF EXISTS `{$table}`";
                array_push($sql, $single_sql);
            }
            return $sql;
        }
    }
}
new OE_uninstall;
