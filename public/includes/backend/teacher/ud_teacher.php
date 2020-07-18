<?php
require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))) . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/user.php';
class UD_teacher
{
    private $post_data;
    private $table;
    public function __construct()
    {
        $this->post_data = $_POST;
        global $wpdb;
        $this->table = $wpdb->prefix . 'teacher';
        if ($this->post_data['action'] == 'update-teacher') {
            if ($this->post_data['teacher_dept'] != "" && $this->post_data['teacher_dept'] != null) {
                $this->handle_action_update();
            } else {
                echo "empty_dept";
            }
        }
        if ($this->post_data['action'] == 'delete-teacher') {
            if ($this->post_data['teacher_id'] != "" && is_int(intval($this->post_data['teacher_id']))) {
                $this->handle_action_delete();
            } else {
                echo 'wrong action';
            }
        }
        if ($this->post_data['action'] == 'restrict-teacher') {
            if ($this->post_data['teacher_id'] != "" && is_int(intval($this->post_data['teacher_id']))) {
                $this->handle_restrict_action();
            } else {
                echo 'wrong action';
            }
        }
        if ($this->post_data['action'] == 'allow-teacher') {
            if ($this->post_data['teacher_id'] != "" && is_int(intval($this->post_data['teacher_id']))) {
                $this->handle_allow_action();
            } else {
                echo 'wrong action';
            }
        }
    }

    private function update_teacher()
    {
        $user_id = wp_update_user([
            'ID' => sanitize_text_field($this->post_data['teacher_id']),
            'display_name' => sanitize_text_field($this->post_data['teacher_name']),
        ]);
        if (!is_int($user_id)) {
            echo 'user_not_updated';
        } else {
            $this->update_post($user_id);
        }
    }
    public function delete_teacher()
    {

        $res = wp_delete_user(intval($this->post_data['teacher_id']));
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
                'teacher_name' => sanitize_text_field($this->post_data['teacher_name']),
                'teacher_dept' => sanitize_text_field($this->post_data['teacher_dept']),
                'teacher_phn' => sanitize_text_field($this->post_data['teacher_phn']),
            ],
            [
                'teacher_id' => sanitize_text_field($this->post_data['teacher_id']),
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
            $this->update_teacher();
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
                'teacher_id' => $this->post_data['teacher_id'],
            ],
            [
                '%d',
            ]
        );
        if ($res) {
            $this->delete_teacher();
            $this->delete_post();
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
                'teacher_id' => sanitize_text_field($this->post_data['teacher_id']),
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
            $this->techer_permission('subscriber');
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
                'teacher_id' => sanitize_text_field($this->post_data['teacher_id']),
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
            $this->techer_permission('teacher');
            $this->output('allowed');
        } else {
            $this->output('not_allowed');
        }
    }
    public function techer_permission($role)
    {
        $user_id = wp_update_user([
            'ID' => sanitize_text_field($this->post_data['teacher_id']),
            'role' => '' . $role . '',
        ]);
        if (!is_int($user_id)) {
            echo 'user_not_allowed';
        }
    }
    public function update_post($user_id)
    {
        $arr = [
            'ID' => $user_id,
            'post_type' => 'teacher',
            'post_title' => sanitize_text_field($this->post_data['teacher_name']),
        ];
        wp_update_post($arr, true);
    }
    public function delete_post()
    {
        wp_delete_post($this->post_data['teacher_id'], true);
    }
    public function output($msg)
    {
        echo $msg;
    }
}
new UD_teacher();
