<?php
require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))) . '/wp-load.php';
class Create_routine
{
    private $post_data;
    public function __construct()
    {
        $this->post_data = $_POST;
        if (!isset($this->post_data['dept_id'])) {
            echo "empty_dept";
            return;
        }
        if (!$this->post_data['exam_name'] || !$this->post_data['exam_date']) {
            echo "empty_field";
            return;
        }
        $this->insert_data();
    }
    private function insert_data()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'exam_routine';
        $res = $wpdb->insert($table,
            [
                'dept_id' => sanitize_text_field($this->post_data['dept_id']),
                'user_id' => get_current_user_id(),
                'exam_name' => sanitize_text_field($this->post_data['exam_name']),
                'exam_date' => sanitize_text_field($this->post_data['exam_date']),
            ],
            [
                '%d',
                '%d',
                '%s',
                '%s',
            ]
        );
        if ($res) {
            echo 'success';
        } else {
            echo 'failed';
        }
    }
}
new Create_routine();
