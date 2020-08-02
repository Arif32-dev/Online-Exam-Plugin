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
        $res = $wpdb->get_results("SELECT teacher_email, teacher_name FROM " . $table . " WHERE teacher_id=" . $user_id . "");
        if (!$res) {
            return;
        }
        if (get_userdata($user_id)->roles[0] == 'teacher' || get_userdata($user_id)->roles[0] == 'administrator') {
            // if user role is changed to either a teacher or admin then the database will be updated accordingly
            self::user_update($res, $table, $user_id, false, true, 0);
        } else {
            // if user is changed to any other role except teacher and admin then the user user will be restricted
            self::user_update($res, $table, $user_id, true, 0, time());
        }
    }
    private static function user_update($res, $table, $user_id, $restriction, $status, $restrict_date)
    {
        global $wpdb;
        if (($res[0]->teacher_email != get_userdata($user_id)->data->user_email) || ($res[0]->teacher_name != get_userdata($user_id)->data->display_name)) {
            $wpdb->update(
                $table,
                [
                    'teacher_email' => ' ' . get_userdata($user_id)->data->user_email . ' ',
                    'teacher_name' => ' ' . get_userdata($user_id)->data->display_name . ' ',
                ],
                [
                    'teacher_id' => $user_id,
                ],
                [
                    '%s',
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
