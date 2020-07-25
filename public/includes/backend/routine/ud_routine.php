<?php
require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))) . '/wp-load.php';
class UD_Question
{
    private $post_data;
    private $table;
    public function __construct()
    {
        $this->post_data = $_POST;
        global $wpdb;
        $this->table = $wpdb->prefix . 'exam_routine';
        if ($this->post_data['action'] == 'update-routine') {
            if (!isset($this->post_data['dept_id'])) {
                echo 'empty_dept';
                return;
            }
            if (!$this->post_data['exam_name'] || !$this->post_data['id'] || !isset($this->post_data['exam_date'])) {
                echo 'empty_field';
                return;
            }
            $this->handle_action_update();
        }

        if ($this->post_data['action'] == 'delete-routine') {
            if (!$this->post_data['id']) {
                echo 'failed';
                return;
            }
            $this->handle_action_delete();
        }
    }
    public function handle_action_update()
    {
        global $wpdb;
        if (get_userdata(get_current_user_id())->roles[0] == 'administrator') {
            $res = $wpdb->update(
                $this->table,
                [
                    'dept_id' => sanitize_text_field($this->post_data['dept_id']),
                    'exam_name' => sanitize_text_field($this->post_data['exam_name']),
                    'exam_date' => sanitize_text_field($this->post_data['exam_date']),
                ],
                [
                    'ID' => sanitize_text_field($this->post_data['id']),
                ],
                [
                    '%d',
                    '%s',
                    '%s',
                ],
                [
                    '%d',
                ],
            );
            $this->output($res, 'updated', 'not_updated');
        } else {
            if (get_userdata(get_current_user_id())->roles[0] == 'teacher') {
                $res = $wpdb->update(
                    $this->table,
                    [
                        'dept_id' => sanitize_text_field($this->post_data['dept_id']),
                        'exam_name' => sanitize_text_field($this->post_data['exam_name']),
                        'exam_date' => sanitize_text_field($this->post_data['exam_date']),
                    ],
                    [
                        'ID' => sanitize_text_field($this->post_data['id']),
                        'user_id' => get_current_user_id(),
                    ],
                    [
                        '%d',
                        '%s',
                        '%s',
                    ],
                    [
                        '%d',
                        '%d',
                    ],
                );
                $this->output($res, 'updated', 'not_updated');
            }
        }

    }
    public function handle_action_delete()
    {

        if (get_userdata(get_current_user_id())->roles[0] == 'administrator') {
            global $wpdb;
            $res = $wpdb->delete(
                $this->table,
                [
                    'ID' => sanitize_text_field($this->post_data['id']),
                ],
                [
                    '%d',
                ]
            );
            $this->output($res, 'deleted', 'failed');

        } else {
            if (get_userdata(get_current_user_id())->roles[0] == 'teacher') {
                global $wpdb;
                $res = $wpdb->delete(
                    $this->table,
                    [
                        'ID' => sanitize_text_field($this->post_data['id']),
                        'user_id' => get_current_user_id(),
                    ],
                    [
                        '%d',
                        '%d',
                    ]
                );
                $this->output($res, 'deleted', 'failed');

            }
        }
    }
    public function output($res, $msg, $error)
    {
        if ($res) {
            echo $msg;
        } else {
            echo $error;
        }
    }
}
new UD_Question();
