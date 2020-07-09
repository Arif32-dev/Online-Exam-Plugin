<?php
class OE_Update_user
{
    public function __construct()
    {
        add_action('profile_update', [$this, 'update_user_role']);
    }
    public function update_user_role($user_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'teacher';
        $res = $wpdb->get_results("SELECT teacher_email FROM " . $table . " WHERE teacher_id=" . $user_id . "");
        if (!$res) {
            return;
        }
        if (get_userdata($user_id)->roles[0] == 'teacher' || get_userdata($user_id)->roles[0] == 'administrator') {
            self::user_update($res, $table, $user_id, false, true, 0);
        } else {
            self::user_update($res, $table, $user_id, true, 0, time());
        }
    }
    private static function user_update($res, $table, $user_id, $restriction, $status, $restrict_date)
    {
        global $wpdb;
        if ($res->teacher_email != get_userdata($user_id)->user_email) {
            $wpdb->update(
                $table,
                [
                    'teacher_email' => ' ' . get_userdata($user_id)->user_email . ' ',
                ],
                [
                    'teacher_id' => $user_id,
                ],
                [
                    '%s',
                ],
                [
                    '%d',
                ],
            );
            $wpdb->update(
                $table,
                [
                    'restriction' => $restriction,
                    'status' => $status,
                    'restrict_date' => $restrict_date,
                ],
                [
                    'teacher_id' => $user_id,
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
        }
    }
}
new OE_Update_user();
