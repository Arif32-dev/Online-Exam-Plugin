<?php
settings_errors();
?>
<div class="oe-notification">
</div>
<?php
class Exam_routine extends Base_tab
{
    private $routine_data;

    public function __construct()
    {
        parent::__construct('Exam Routine', 'Create Routine');
        global $wpdb;
        $this->table = $wpdb->prefix . 'exam_routine';

        if (get_userdata(get_current_user_id())->roles[0] == 'teacher') {
            $this->routine_data = $wpdb->get_results("SELECT * FROM " . $this->table . " WHERE user_id=" . get_current_user_id() . "");
        }
        if (get_userdata(get_current_user_id())->roles[0] == 'administrator') {
            $this->routine_data = $wpdb->get_results("SELECT * FROM " . $this->table . "");
        }
        $this->tab_body();
    }
    public function tab_panel1()
    {

        ?>
            <div id="tab-one-panel" class="panel active">
                <div style="overflow-x:auto;">
                    <table>
                        <tr>
                            <th>Exam Name</th>
                            <th>Department Name</th>
                            <th>Exam Date</th>
                            <th>Created By</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                        <?php $this->info_table_rows();?>
                    </table>
                </div>
            </div>
        <?php

    }

    public function tab_panel2()
    {

        ?>
            <div id="tab-two-panel" class="panel">
                <div class="wrap">
                    <form id="exam-routine_form" action="options.php" method='POST'>
                            <?php settings_fields('oe-exam-routine')?>
                            <?php do_settings_sections('manage_routine')?>
                            <?php submit_button('Save Department');?>
                    </form>
                </div>
            </div>
        <?php

    }
    public function info_table_rows()
    {
        foreach ($this->routine_data as $routine) {

            ?>
                <tr>
                    <td>
                        <input type="text" class="exam_name" name="exam_name" value="<?php echo $routine->exam_name ?>">
                    </td>
                     <td>
                            <select  style="width: 10rem" name="dept_id" class="dept_id" >
                                <?php if (get_userdata(get_current_user_id())->roles[0] == 'administrator') {$this->admin_select_box($routine->dept_id, $routine->user_id);}?>
                                <?php if (get_userdata(get_current_user_id())->roles[0] == 'teacher') {$this->teacher_select_box($routine->dept_id);}?>
                            </select>
                    </td>
                    <td>
                        <input type="date" class="exam_date" name="exam_date" value="<?php echo $routine->exam_date ?>">
                    </td>
                    <td style="white-space: nowrap">
                        <?php echo get_userdata($routine->user_id)->data->display_name ?>
                    </td>
                    <td ><button data-id="<?php echo $routine->ID ?>"  data-action="update-routine"  class="oe-green oe-routine-update">Update</button></td>
                    <td><button data-id="<?php echo $routine->ID ?>"  data-action="delete-routine"  class="oe-red oe-routine-delete">Delete</button></td>
                </tr>
            <?php

        }
    }
}
new Exam_routine();
?>
