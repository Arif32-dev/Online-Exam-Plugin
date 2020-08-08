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
        $this->table = $wpdb->prefix . 'question_folder';
        if ($this->post_data['action'] == 'update-folder') {
            if ((get_userdata(get_current_user_id())->roles[0] == 'teacher') || (get_userdata(get_current_user_id())->roles[0] == 'administrator')) {

                if (empty($this->post_data)) {
                    return;
                }
                if (!$this->post_data['dept_id'] || $this->post_data['dept_id'] == null) {
                    echo "empty_dept";
                    return;
                }
                if (!$this->post_data['exam_folder_name'] || !$this->post_data['exam_folder_id'] || !$this->post_data['quantity'] || !$this->post_data['exam_time'] || !$this->post_data['pass_percentage']) {
                    echo 'empty_field';
                    return;
                }
                $this->handle_action_update();

            } else {
                echo 'invalid_authentication';
            }
        }

        if ($this->post_data['action'] == 'delete-folder') {
            if ($this->post_data['exam_folder_id']) {
                $this->handle_action_delete();
            }
        }

        if ($this->post_data['action'] == 'oe-publish-qus') {
            if (!$this->post_data['dept_id'] || $this->post_data['dept_id'] == null) {
                echo "empty_dept";
                return;
            }
            if ($this->post_data['exam_folder_id']) {
                $this->publish_exam();
            }
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
                    'exam_folder_name' => sanitize_text_field($this->post_data['exam_folder_name']),
                    'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                    'quantity' => sanitize_text_field($this->post_data['quantity']),
                    'exam_time' => sanitize_text_field($this->post_data['exam_time']),
                    'pass_percentage' => sanitize_text_field($this->post_data['pass_percentage']),
                    'pass_percentage' => sanitize_text_field($this->post_data['pass_percentage']),
                ],
                [
                    'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                ],
                [
                    '%d',
                    '%s',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
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
                        'exam_folder_name' => sanitize_text_field($this->post_data['exam_folder_name']),
                        'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                        'quantity' => sanitize_text_field($this->post_data['quantity']),
                        'exam_time' => sanitize_text_field($this->post_data['exam_time']),
                        'pass_percentage' => sanitize_text_field($this->post_data['pass_percentage']),
                        'pass_percentage' => sanitize_text_field($this->post_data['pass_percentage']),
                    ],
                    [
                        'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                        'examined_by' => get_current_user_id(),
                    ],
                    [
                        '%d',
                        '%s',
                        '%d',
                        '%d',
                        '%d',
                        '%d',
                        '%d',
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
                    'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                ],
                [
                    '%d',
                ]
            );
            /* if qustion folder is deleted then delete all qustion related to its folder */
            if ($res) {
                $this->qustions_delete();
            }
            $this->output($res, 'deleted', 'failed');
        } else {
            if (get_userdata(get_current_user_id())->roles[0] == 'teacher') {
                global $wpdb;
                $res = $wpdb->delete(
                    $this->table,
                    [
                        'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                        'examined_by' => get_current_user_id(),
                    ],
                    [
                        '%d',
                        '%d',
                    ]
                );
                /* if qustion folder is deleted then delete all qustion related to its folder */
                if ($res) {
                    $this->qustions_delete();
                }
                $this->output($res, 'deleted', 'failed');
            }
        }
    }

    /**
     * @method is going to delete all qustion related to its folder if folder is deleted succesfully
     * @return void
     */
    public function qustions_delete()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . 'qustions';
        $wpdb->delete(
            $this->table,
            [
                'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
            ],
            [
                '%d',
            ]
        );
    }
    public function publish_exam()
    {
        global $wpdb;
        if (get_userdata(get_current_user_id())->roles[0] == 'administrator') {
            date_default_timezone_set(wp_timezone_string());
            $res = $wpdb->update(
                $this->table,
                [
                    'publish_exam' => true,
                    'published_date' => time(),
                    'remaining_time' => (time() + (sanitize_text_field($this->post_data['exam_time']) * 60)),
                    'exam_status' => 'Running',
                    'terminate_exam' => true,
                ],
                [
                    'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                ],
                [
                    '%d',
                    '%d',
                    '%d',
                    '%s',
                    '%d',
                ],
                [
                    '%d',
                ],
            );
            $this->output($res, 'published', 'not_published');
        } else {
            if (get_userdata(get_current_user_id())->roles[0] == 'teacher') {
                $res = $wpdb->update(
                    $this->table,
                    [
                        'publish_exam' => true,
                        'published_date' => time(),
                        'exam_status' => 'Running',
                        'terminate_exam' => true,
                    ],
                    [
                        'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                        'examined_by' => get_current_user_id(),
                    ],
                    [
                        '%d',
                        '%d',
                        '%s',
                        '%d',
                    ],
                    [
                        '%d',
                        '%d',
                    ],
                );
                $this->output($res, 'published', 'not_published');
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
