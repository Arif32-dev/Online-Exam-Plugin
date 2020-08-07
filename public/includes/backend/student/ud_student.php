<?php
require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/user.php';
class UD_student
{
    private $post_data;
    private $table;
    public function __construct()
    {
        $this->post_data = $_POST;
        global $wpdb;
        $this->table = $wpdb->prefix . 'students';
        if ($this->post_data['action'] == 'update-student') {
            if ($this->post_data['std_dept'] != "" && $this->post_data['std_dept'] != null) {
                $this->handle_action_update();
            } else {
                echo "empty_dept";
            }
        }
        if ($this->post_data['action'] == 'delete-student') {
            if ($this->post_data['std_id'] != "" && is_int(intval($this->post_data['std_id']))) {
                $this->handle_action_delete();
            } else {
                echo 'wrong action';
            }
        }
        if ($this->post_data['action'] == 'restrict-student') {
            if ($this->post_data['std_id'] != "" && is_int(intval($this->post_data['std_id']))) {
                $this->handle_restrict_action();
            } else {
                echo 'wrong action';
            }
        }
        if ($this->post_data['action'] == 'allow-student') {
            if ($this->post_data['std_id'] != "" && is_int(intval($this->post_data['std_id']))) {
                $this->handle_allow_action();
            } else {
                echo 'wrong action';
            }
        }
    }

    public function update_std_from_admin()
    {
        $user_id = wp_update_user([
            'ID' => sanitize_text_field($this->post_data['std_id']),
            'display_name' => sanitize_text_field($this->post_data['std_name']),
        ]);
        if (!is_int($user_id)) {
            echo 'user_not_updated';
        }
    }
    public function delete_student_from_admin()
    {

        $res = wp_delete_user(intval($this->post_data['std_id']));
        if (!$res) {
            echo "user_not_deleted";
        }
    }
    public function handle_action_update()
    {
        global $wpdb;
        $res = $wpdb->update(
            $this->table,
            [
                'std_name' => sanitize_text_field($this->post_data['std_name']),
                'dept_id' => sanitize_text_field($this->post_data['std_dept']),
                'std_phone' => sanitize_text_field($this->post_data['std_phn']),
            ],
            [
                'std_id' => sanitize_text_field($this->post_data['std_id']),
            ],
            [
                '%s',
                '%d',
                '%s',
            ],
            [
                '%d',
            ],
        );
        if ($res) {
            $this->update_std_from_admin();
            $this->output('updated');
        } else {
            $this->output('not_updated');
        }
    }
    public function handle_action_delete()
    {
        global $wpdb;
        $res = $wpdb->delete(
            $this->table,
            [
                'std_id' => $this->post_data['std_id'],
            ],
            [
                '%d',
            ]
        );
        if ($res) {
            $this->delete_student_from_admin();
            $this->output('deleted', 'failed');
        } else {
            $this->output('failed');
        }
    }
    public function handle_restrict_action()
    {
        global $wpdb;
        $res = $wpdb->update(
            $this->table,
            [
                'restriction' => true,
                'status' => false,
                'restrict_date' => sanitize_text_field($this->post_data['restrict_date']),
            ],
            [
                'std_id' => sanitize_text_field($this->post_data['std_id']),
            ],
            [
                '%d',
                '%d',
                '%d',
            ],
            [
                '%d',
            ],
        );
        if ($res) {
            $this->student_permission('subscriber');
            $this->output('restricted');
        } else {
            $this->output('not_restricted');
        }
    }

    public function handle_allow_action()
    {
        global $wpdb;
        $res = $wpdb->update(
            $this->table,
            [
                'restriction' => false,
                'status' => true,
                'restrict_date' => 0,
            ],
            [
                'std_id' => sanitize_text_field($this->post_data['std_id']),
            ],
            [
                '%d',
                '%d',
                '%d',
            ],
            [
                '%d',
            ],
        );
        if ($res) {
            $this->student_permission('student');
            $this->output('allowed');
        } else {
            $this->output('not_allowed');
        }
    }

    public function student_permission($role)
    {
        $user_id = wp_update_user([
            'ID' => sanitize_text_field($this->post_data['std_id']),
            'role' => '' . $role . '',
        ]);
        if (!is_int($user_id)) {
            echo 'user_not_allowed';
        }
    }
    public function output($msg)
    {
        echo $msg;
    }
}
new UD_student();
