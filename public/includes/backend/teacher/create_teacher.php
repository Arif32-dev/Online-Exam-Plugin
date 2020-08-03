<?php
require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))) . '/wp-load.php';
class Create_teacher
{
    private $post_data;
    private $user_id;
    public function __construct()
    {
        $this->post_data = $_POST;
        /* if post data is empty then dont execute below code */
        if (!$this->post_data) {
            return;
        }
        /* if Department id is set */
        if (isset($this->post_data['teacher_dept'])) {
            // if phn number dosn't contain any other character then number
            if (is_numeric($this->post_data['teacher_phn'])) {
                // if teacher role is exists then create teacher
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
        /* insert user into wordpress database */
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
        /* if user is created successfully then create teacher into wordpress custom wp_teacher database */
        if (is_int($user_id)) {
            $this->user_id = intval($user_id);
            $this->insert_data();
        } else {
            echo 'user_not_created';
        }
    }
    private function insert_data()
    {
        /* create teacher into wp_teacher database */
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
            /* if teacher is created to wp_teacher database
            then create a teacher cpt post to wordrpesss database  with the teacher id as custom id */
            $this->create_teacher_post();
            echo 'success';
        } else {
            echo 'failed';
        }
    }
    private function create_teacher_post()
    {
        /* Create a cpt teacher post with newly created user id */
        wp_insert_post([
            'import_id' => $this->user_id,
            'post_title' => sanitize_text_field($this->post_data['teacher_name']),
            'post_type' => 'teacher',
            'post_status' => 'publish',
            'post_content' => "",
        ], true);
    }
}
new Create_teacher();
