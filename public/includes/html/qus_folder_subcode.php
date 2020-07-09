<?php
class Subcode
{
    private $current_user_id;
    public $dept_data;
    private $teacher_data;
    public function __construct()
    {
        $this->current_user_id = get_current_user_id();
        global $wpdb;

        $table = $wpdb->prefix . 'teacher';
        $this->teacher_data = $wpdb->get_results("SELECT teacher_dept, teacher_name FROM " . $table . " WHERE teacher_id=" . $this->current_user_id . "");
        if (!$this->teacher_data) {
            echo "<h1>User: Admin</h1>";
            return;
        } else {
            $table = $wpdb->prefix . 'department';
            $this->dept_data = $wpdb->get_results("SELECT dept_name, dept_id FROM " . $table . " WHERE dept_id=" . $this->teacher_data[0]->teacher_dept . "");

            ?>
                <h2>User : <?php echo $this->teacher_data[0]->teacher_name ?> </h2>
                <h2>Department : <?php echo $this->dept_data[0]->dept_name ?></h2>
            <?php

        }
    }

}
new Subcode();
