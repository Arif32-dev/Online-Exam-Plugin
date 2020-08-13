<?php
require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))) . '/wp-load.php';
class Single_qustions
{
    private $post_data;
    public function __construct()
    {
        $this->post_data = $_POST;
        if (empty($this->post_data)) {
            echo "data_is_empty";
            return;
        }
        if (!$this->post_data['oe-qus-text'] || !$this->post_data['qus1'] || !$this->post_data['qus2'] || !$this->post_data['qus3'] || !$this->post_data['qus4']) {
            echo 'empty_field';
            return;
        }
        if (!isset($this->post_data['correct_ans'])) {
            echo 'empty_correct_ans';
            return;
        }
        global $wpdb;
        $table = $wpdb->prefix . 'qustions';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE qustion_id=" . $this->post_data['qustion_id'] . " AND exam_folder_id=" . $this->post_data['exam_folder_id'] . " ");
        if ($res) {
            $this->update_data();
        } else {
            $this->insert_data();
        }

    }
    private function insert_data()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'qustions';
        $res = $wpdb->insert($table,
            [
                'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                'qustion' => sanitize_text_field($this->post_data['oe-qus-text']),
                'qustion_id' => sanitize_text_field($this->post_data['qustion_id']),
                'a1' => sanitize_text_field($this->post_data['qus1']),
                'a1_id' => sanitize_text_field($this->post_data['a1_id']),
                'a2' => sanitize_text_field($this->post_data['qus2']),
                'a2_id' => sanitize_text_field($this->post_data['a2_id']),
                'a3' => sanitize_text_field($this->post_data['qus3']),
                'a3_id' => sanitize_text_field($this->post_data['a3_id']),
                'a4' => sanitize_text_field($this->post_data['qus4']),
                'a4_id' => sanitize_text_field($this->post_data['a4_id']),
                'correct_ans' => sanitize_text_field($this->post_data['correct_ans']),
            ],
            [
                '%d',
                '%s',
                '%d',
                '%s',
                '%d',
                '%s',
                '%d',
                '%s',
                '%d',
                '%s',
                '%d',
                '%d',
            ]
        );
        $this->output($res, 'success', 'failed');
    }
    private function update_data()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'qustions';
        $res = $wpdb->update($table,
            [
                'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
                'qustion' => sanitize_text_field($this->post_data['oe-qus-text']),
                'qustion_id' => sanitize_text_field($this->post_data['qustion_id']),
                'a1' => sanitize_text_field($this->post_data['qus1']),
                'a1_id' => sanitize_text_field($this->post_data['a1_id']),
                'a2' => sanitize_text_field($this->post_data['qus2']),
                'a2_id' => sanitize_text_field($this->post_data['a2_id']),
                'a3' => sanitize_text_field($this->post_data['qus3']),
                'a3_id' => sanitize_text_field($this->post_data['a3_id']),
                'a4' => sanitize_text_field($this->post_data['qus4']),
                'a4_id' => sanitize_text_field($this->post_data['a4_id']),
                'correct_ans' => sanitize_text_field($this->post_data['correct_ans']),
            ],
            [
                'qustion_id' => sanitize_text_field($this->post_data['qustion_id']),
                'exam_folder_id' => sanitize_text_field($this->post_data['exam_folder_id']),
            ],
            [
                '%d',
                '%s',
                '%d',
                '%s',
                '%d',
                '%s',
                '%d',
                '%s',
                '%d',
                '%s',
                '%d',
                '%d',
            ],
            [
                '%d',
                '%d',
            ]
        );
        $this->output($res, 'updated', 'not_updated');
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
new Single_qustions();
