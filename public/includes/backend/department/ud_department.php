<?php
require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))) . '/wp-load.php';
class UD_department
{
    private $post_data;
    private $table;
    public function __construct()
    {
        $this->post_data = $_POST;
        global $wpdb;
        $this->table = $wpdb->prefix . 'department';
        $update_check = $wpdb->get_results("SELECT dept_name FROM " . $this->table . " WHERE dept_id=" . $this->post_data['id'] . "");
        if ($this->post_data['action'] == 'update') {
            if ($update_check[0]->dept_name != $this->post_data['input_val']) {
                $this->handle_action_update();
            } else {
                echo 'not_updated';
            }
        }
        if ($this->post_data['action'] == 'delete') {
            $this->handle_action_delete();
        }

    }
    public function handle_action_update()
    {
        global $wpdb;
        $res = $wpdb->update(
            $this->table,
            [
                'dept_name' => stripslashes($this->post_data['input_val']),
                'dept_last_updated' => stripslashes($this->post_data['last_update']),
            ],
            [
                'dept_id' => $this->post_data['id'],
            ],
            [
                '%s',
                '%d',
            ],
            [
                '%d',
            ],
        );
        $this->output($res, 'updated', 'not_updated');
    }
    public function handle_action_delete()
    {
        global $wpdb;
        $res = $wpdb->delete(
            $this->table,
            [
                'dept_id' => $this->post_data['id'],
            ],
            [
                '%d',
            ]
        );
        $this->output($res, 'deleted', 'failed');
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
new UD_department();
