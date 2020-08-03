<?php
class OE_Update_user
{
    public function __construct()
    {
        add_action('profile_update', [$this, 'update_student_role']);
        add_action('profile_update', [$this, 'update_teacher_role']);
    }
    public function update_teacher_role($user_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'teacher';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE teacher_id=" . $user_id . "");
        if (!$res) {
            return;
        }
        if (get_userdata($user_id)->roles[0] == 'teacher' || get_userdata($user_id)->roles[0] == 'administrator') {
            // if user role is changed to either a teacher or admin then the database will be updated accordingly
            $column = [
                'teacher_email',
                'teacher_name',
                'teacher_id',
            ];
            self::teacher_update($res, $table, $column, $user_id, false, true, 0);
        } else {
            // if user is changed to any other role except teacher and admin then the user user will be restricted
            self::teacher_update($res, $table, $column, $user_id, true, false, time());
        }
    }

    public function update_student_role($user_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'students';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE std_id=" . $user_id . "");
        if (!$res) {
            return;
        }
        if (get_userdata($user_id)->roles[0] == 'student' || get_userdata($user_id)->roles[0] == 'administrator') {
            $column = [
                'std_email',
                'std_name',
                'std_id',
            ];
            self::std_update($res, $table, $column, $user_id, false, true, 0);
        } else {
            self::std_update($res, $table, $column, $user_id, true, false, time());
        }

    }

    private static function teacher_update($res, $table, $column, $user_id, $restriction, $status, $restrict_date)
    {
        global $wpdb;
        $wpdb->update(
            $table,
            [
                'teacher_email' => ' ' . get_userdata($user_id)->data->user_email . ' ',
                'teacher_name' => ' ' . get_userdata($user_id)->data->display_name . ' ',
                'restriction' => $restriction,
                'status' => $status,
                'restrict_date' => $restrict_date,
            ],
            [
                'teacher_id' => $user_id,
            ],
            [
                '%s',
                '%s',
                '%d',
                '%d',
                '%d',
            ],
            [
                '%d',
            ],
        );
    }

    private static function std_update($res, $table, $column, $user_id, $restriction, $status, $restrict_date)
    {
        global $wpdb;
        $wpdb->update(
            $table,
            [
                'std_email' => ' ' . get_userdata($user_id)->data->user_email . ' ',
                'std_name' => ' ' . get_userdata($user_id)->data->display_name . ' ',
                'restriction' => $restriction,
                'status' => $status,
                'restrict_date' => $restrict_date,
            ],
            [
                'std_id' => $user_id,
            ],
            [
                '%s',
                '%s',
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
new OE_Update_user();
