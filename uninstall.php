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
        $this->unregister_oe_settings();
    }
    public function unregister_oe_settings()
    {
        $settings = array(
            /* Department array */
            array(
                'parent_option_group' => 'department',
                'option_name' => 'dept_name',
            ),

            /* Teacher array */
            array(
                'parent_option_group' => 'oe-teacher',
                'option_name' => 'teacher_name',
            ),
            array(
                'parent_option_group' => 'oe-teacher',
                'option_name' => 'teacher_dept',
            ),
            array(
                'parent_option_group' => 'oe-teacher',
                'option_name' => 'teacher_username',
            ),
            array(
                'parent_option_group' => 'oe-teacher',
                'option_name' => 'teacher_email',
            ),
            array(
                'parent_option_group' => 'oe-teacher',
                'option_name' => 'teacher_pass',
            ),
            array(
                'parent_option_group' => 'oe-teacher',
                'option_name' => 'teacher_phn',
            ),

            /* Qustion Folder array */
            array(
                'parent_option_group' => 'oe-question',
                'option_name' => 'exam_folder',
            ),
            array(
                'parent_option_group' => 'oe-question',
                'option_name' => 'quantity',
            ),
            array(
                'parent_option_group' => 'oe-question',
                'option_name' => 'est_time',
            ),
            array(
                'parent_option_group' => 'oe-question',
                'option_name' => 'pass_percentage',
            ),

            /* Routine array */
            array(
                'parent_option_group' => 'oe-exam-routine',
                'option_name' => 'department',
            ),
            array(
                'parent_option_group' => 'oe-exam-routine',
                'option_name' => 'exam_name',
            ),
            array(
                'parent_option_group' => 'oe-exam-routine',
                'option_name' => 'exam_date',
            ),
        );
        foreach ($settings as $setting) {
            unregister_setting($setting['parent_option_group'], $setting['option_name']);
        }
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
