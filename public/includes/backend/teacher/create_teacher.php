<?php
require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))) . '/wp-load.php';
class Create_teacher
{
    private $post_data;
    private $user_id;
    public function __construct()
    {
        $this->post_data = $_POST;
        if (!$this->post_data) {
            return;
        }
        if (isset($this->post_data['teacher_dept'])) {
            if (is_numeric($this->post_data['teacher_phn'])) {
                if (get_role('teacher')) {
                    $this->create_teacher();
                } else {
                    echo 'user_role_invalid';
                }
            } else {
                echo 'invalid_number';
            }
        } else {
            echo 'invalid_dept';
        }
    }
    private function create_teacher()
    {
        $user_id = wp_insert_user([
            'user_pass' => sanitize_text_field($this->post_data['teacher_pass']),
            'user_login' => sanitize_text_field($this->post_data['teacher_username']),
            'display_name' => sanitize_text_field($this->post_data['teacher_name']),
            'user_nicename' => sanitize_text_field($this->post_data['teacher_username']),
            'user_email' => sanitize_text_field($this->post_data['teacher_email']),
            'rich_editing' => false,
            'syntax_highlighting' => false,
            'role' => 'teacher',
        ]);
        if (is_int($user_id)) {
            $this->user_id = intval($user_id);
            $this->insert_data();
        } else {
            echo 'user_not_created';
        }
    }
    private function insert_data()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'teacher';
        $res = $wpdb->insert($table,
            [
                'teacher_id' => $this->user_id,
                'teacher_name' => sanitize_text_field($this->post_data['teacher_name']),
                'teacher_dept' => sanitize_text_field($this->post_data['teacher_dept']),
                'teacher_phn' => sanitize_text_field($this->post_data['teacher_phn']),
                'teacher_email' => sanitize_text_field($this->post_data['teacher_email']),
                'appoint_date' => sanitize_text_field($this->post_data['appoint_date']),
                'status' => true,
                'restriction' => false,
                'restrict_date' => 0,
            ],
            [
                '%d',
                '%s',
                '%d',
                '%s',
                '%s',
                '%d',
                '%d',
                '%d',
                '%d',
            ]
        );
        if ($res) {
            echo 'success';
        } else {
            echo 'failed';
        }
    }
}
new Create_teacher();
