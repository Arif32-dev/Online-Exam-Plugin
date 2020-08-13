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
                if (!$this->post_data['exam_folder_name'] ||
                    !$this->post_data['exam_folder_id'] ||
                    !$this->post_data['quantity'] ||
                    !$this->post_data['exam_time'] ||
                    !$this->post_data['pass_percentage'] ||
                    !$this->post_data['per_qus_mark']) {
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
            if (empty($this->post_data)) {
                return;
            }
            if (!$this->post_data['dept_id'] || $this->post_data['dept_id'] == null) {
                echo "empty_dept";
                return;
            }
            if (!$this->post_data['exam_folder_name'] ||
                !$this->post_data['exam_folder_id'] ||
                !$this->post_data['quantity'] ||
                !$this->post_data['exam_time'] ||
                !$this->post_data['pass_percentage'] ||
                !$this->post_data['per_qus_mark']) {
                echo 'empty_field';
                return;
            }
            $this->publish_exam();
        }
        if ($this->post_data['action'] == 'terminate-exam') {
            if ($this->post_data['exam_folder_id']) {
                $this->handle_action_terminate();
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
                    'per_qus_mark' => sanitize_text_field($this->post_data['per_qus_mark']),
                    'total_mark' => (sanitize_text_field($this->post_data['per_qus_mark']) * sanitize_text_field($this->post_data['quantity'])),
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
        $qustion_exists = $wpdb->get_results("SELECT * FROM " . $this->table . " WHERE exam_folder_id=" . sanitize_text_field($this->post_data['exam_folder_id']) . "");
        if ($qustion_exists) {

            $res = $wpdb->delete(
                $this->table,
                [
                    'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                ],
                [
                    '%d',
                ]
            );
            if ($res) {
                $this->result_delete();
            }
        } else {
            $this->output(true, 'deleted', 'failed');
        }
    }

    /**
     * @method is going to delete all result answered by students related to its folder if folder is deleted succesfully
     * @return void
     */
    public function result_delete()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . 'result';
        $result_exists = $wpdb->get_results("SELECT * FROM " . $this->table . " WHERE exam_folder_id=" . sanitize_text_field($this->post_data['exam_folder_id']) . "");
        if ($result_exists) {
            $res = $wpdb->delete(
                $this->table,
                [
                    'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                ],
                [
                    '%d',
                ]
            );
            $this->output($res, 'deleted', 'failed');
        } else {
            $this->output(true, 'deleted', 'failed');
        }
    }
    public function publish_exam()
    {
        if ($this->check_prev_published_exam()) {
            echo 'prev_exam_exists';
            return;
        }
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
    public function check_prev_published_exam()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . 'question_folder';
        $res = $wpdb->get_results("SELECT exam_folder_id FROM " . $this->table . " WHERE dept_id=" . sanitize_text_field($this->post_data['dept_id']) . " AND terminate_exam=1");
        if ($res) {
            return $res;
        } else {
            return false;
        }
    }
    public function handle_action_terminate()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . 'question_folder';

        $check_exam_folder = $wpdb->get_results("SELECT * FROM " . $this->table . " WHERE exam_folder_id=" . sanitize_text_field($this->post_data['exam_folder_id']) . " AND exam_status='Running' AND terminate_exam=1");
        if (get_userdata(get_current_user_id())->roles[0] == 'administrator') {
            if ($check_exam_folder) {
                $res = $wpdb->update(
                    $this->table,
                    [
                        'terminate_exam' => false,
                        'termination_date' => time(),
                        'exam_status' => 'Finished',
                    ],
                    [
                        'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                    ],
                    [
                        '%d',
                        '%d',
                        '%s',
                    ],
                    [
                        '%d',
                    ],
                );
                $this->output($res, 'terminated', 'failed');
            } else {
                $this->output(false, 'terminated', 'failed');
            }
        } else {
            if (get_userdata(get_current_user_id())->roles[0] == 'teacher') {
                if ($check_exam_folder) {
                    $res = $wpdb->update(
                        $this->table,
                        [
                            'terminate_exam' => false,
                            'termination_date' => time(),
                            'exam_status' => 'Finished',
                        ],
                        [
                            'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                            'examined_by' => get_current_user_id(),
                        ],
                        [
                            '%d',
                            '%d',
                            '%s',
                        ],
                        [
                            '%d',
                            '%d',
                        ],
                    );
                    $this->output($res, 'terminated', 'failed');
                } else {
                    $this->output(false, 'terminated', 'failed');
                }
            } else {
                echo 'failed';
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
