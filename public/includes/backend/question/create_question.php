<?php
require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))) . '/wp-load.php';
class Create_qustion
{
    private $post_data;
    public function __construct()
    {
        $this->post_data = $_POST;
        if (empty($this->post_data)) {
            echo "data_is_empty";
            return;
        }
        if (!isset($this->post_data['dept_id'])) {
            echo 'empty_dept';
            return;
        }
        if (!$this->post_data['exam_folder'] && !$this->post_data['quantity'] && !$this->post_data['est_time'] && !$this->post_data['pass_percentage']) {
            echo 'failed';
            return;
        }
        $this->insert_data();
    }
    private function insert_data()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'question_folder';
        $res = $wpdb->insert($table,
            [
                'dept_id' => sanitize_text_field($this->post_data['dept_id']),
                'exam_folder_name' => sanitize_text_field($this->post_data['exam_folder']),
                'exam_folder_id' => time(),
                'quantity' => sanitize_text_field($this->post_data['quantity']),
                'exam_time' => sanitize_text_field($this->post_data['est_time']),
                'remaining_time' => 0,
                'per_qus_mark' => sanitize_text_field($this->post_data['per_qus_mark']),
                'total_mark' => (sanitize_text_field($this->post_data['per_qus_mark']) * sanitize_text_field($this->post_data['quantity'])),
                'pass_percentage' => sanitize_text_field($this->post_data['pass_percentage']),
                'publish_exam' => false,
                'published_date' => 0,
                'terminate_exam' => false,
                'termination_date' => 0,
                'examined_by' => get_current_user_id(),
                'exam_status' => 'Inactive',
            ],
            [
                '%d',
                '%s',
                '%d',
                '%d',
                '%d',
                '%d',
                '%d',
                '%d',
                '%d',
                '%d',
                '%d',
                '%d',
                '%d',
                '%d',
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
new Create_qustion();
