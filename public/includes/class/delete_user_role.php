<?php
class OE_Delete_user
{
    public function __construct()
    {
        add_action('delete_user', [$this, 'handle_delete']);
    }
    public function handle_delete($user_id)
    {
        self::teacher_delete($user_id);
        self::delete_qustion_folder($user_id);
    }
    public static function delete_qustion_folder($user_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'question_folder';
        $res = $wpdb->get_results("SELECT examined_by FROM " . $table . " WHERE examined_by=" . $user_id . "");
        if ($res) {
            $wpdb->delete(
                $table,
                [
                    'examined_by' => $user_id,
                ],
                [
                    '%d',
                ]
            );
        } else {
            return;
        }
    }
    public function teacher_delete($user_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'teacher';
        $res = $wpdb->get_results("SELECT teacher_id FROM " . $table . " WHERE teacher_id=" . $user_id . "");
        if ($res) {
            $wpdb->delete($table,
                [
                    'teacher_id' => $user_id,
                ],
                [
                    '%d',
                ]
            );
        } else {
            return;
        }

    }
}
new OE_Delete_user();
