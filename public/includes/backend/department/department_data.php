<?php
require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))) . '/wp-load.php';
class Department_data
{
    private $dept_name;
    private $dept_create_date;
    private $dept_author;
    public function __construct()
    {
        $this->dept_name = sanitize_text_field($_POST['dept_name']);
        $this->dept_create_date = sanitize_text_field($_POST['dept_create_date']);
        $this->dept_author = sanitize_text_field($_POST['dept_author']);
        global $wpdb;
        $table = $wpdb->prefix . 'department';
        if ($this->dept_name != "") {
            $name_check = $wpdb->get_results("SELECT dept_name FROM " . $table . " WHERE dept_name='" . $this->dept_name . "' ");
            if (!$name_check) {
                $this->insert_data();
            } else {
                echo 'department_exists';
                return;
            }

        } else {
            echo 'empty_name';
            return;
        }
    }
    private function insert_data()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'department';
        $res = $wpdb->insert($table, array(
            'dept_id' => $this->dept_create_date,
            'dept_name' => $this->dept_name,
            'dept_create_date' => $this->dept_create_date,
            'dept_last_updated' => false,
            'dept_author' => $this->dept_author,
        ), array(
            '%d',
            '%s',
            '%d',
            '%d',
            '%d',
        ));
        if ($res) {
            echo 'success';
        } else {
            echo 'failed';
        }
    }
}
new Department_data();
