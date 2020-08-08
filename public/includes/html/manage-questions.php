<div class="oe-notification">
</div>
<?php
require_once BASE_PATH . 'public/includes/html/qus_folder_subcode.php';
class Question extends Base_tab
{
    private $qus_data;

    public function __construct()
    {
        parent::__construct("Question's Folder's", 'Create Questions');
        global $wpdb;
        $this->table = $wpdb->prefix . 'question_folder';
        if (get_userdata(get_current_user_id())->roles[0] == 'teacher') {
            $this->qus_data = $wpdb->get_results("SELECT * FROM " . $this->table . " WHERE examined_by=" . get_current_user_id() . "");
        }
        if (get_userdata(get_current_user_id())->roles[0] == 'administrator') {
            $this->qus_data = $wpdb->get_results("SELECT * FROM " . $this->table . "");
        }
        $this->tab_body();
    }
    public function tab_body()
    {

        ?>
            <div class="worko-tabs">

                 <?php $this->inputs();?>
                <div class="tabs flex-tabs">
                    <?php $this->labels();?>
                    <?php $this->tab_panel1();?>
                    <?php $this->tab_panel2();?>
                    <?php $this->tab_panel3();?>

                </div>

            </div>
        <?php

    }
    public function tab_panel1()
    {

        ?>
            <div id="tab-one-panel" class="panel active">
                <div style="overflow-x:auto;">
                        <table>
                            <tr>
                                <th>Department</th>
                                <th>Exam Folder</th>
                                <th>Quantity</th>
                                <th>Exam's Time</th>
                                <th>Pass Percentage</th>
                                <th>Publish Exam</th>
                                <th>Terminate Exam</th>
                                <th >Examiner</th>
                                <th>Exam Status</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                            <?php $this->info_table_rows()?>
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
                            <form id="question_create_form"action="options.php" method='POST'>
                                    <?php settings_fields("ow-question")?>
                                    <?php do_settings_sections("manage_questions")?>
                                    <?php submit_button("Save Question");?>
                            </form>
                         </div>
                    </div>
        <?php

    }
    public function tab_panel3()
    {
        ?>
            <div id="tab-three-panel" class="panel">
                <div style="overflow-x:auto;">
                        <table>
                            <tr>
                                <th>Exam Name</th>
                                <th>Published Date</th>
                                <th>Termination Date</th>
                                <th>Attendant's</th>
                                <th>Total Registered Student</th>
                                <th>Attendant's Performance</th>
                            </tr>
                            <tr>
                                <td>Jill</td>
                                <td>Smith</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                            </tr>
                        </table>
                    </div>
            </div>
        <?php

    }

    public function inputs()
    {

        ?>
                 <input class="state" type="radio" title="tab-one" name="tabs-state" id="tab-one" checked />
                <input class="state" type="radio" title="tab-two" name="tabs-state" id="tab-two" />
                <input class="state" type="radio" title="tab-three" name="tabs-state" id="tab-three" />
        <?php

    }
    public function labels()
    {

        ?>
                <label for="tab-one" id="tab-one-label" class="tab"><?php echo $this->param1; ?></label>
                <label for="tab-two" id="tab-two-label" class="tab"><?php echo $this->param2; ?></label>
                <label for="tab-three" id="tab-three-label" class="tab">Exam Performence</label>
        <?php

    }

    public function info_table_rows()
    {
        foreach ($this->qus_data as $qus) {
            ?>
                    <tr>
                        <td>
                            <select <?php echo $qus->publish_exam == true ? "disabled" : "" ?> style="width: 10rem" name="dept_id" class="question_dept_id">
                                <?php if (get_userdata(get_current_user_id())->roles[0] == 'administrator') {$this->admin_select_box($qus->dept_id, $qus->examined_by);}?>
                                <?php if (get_userdata(get_current_user_id())->roles[0] == 'teacher') {$this->teacher_select_box($qus->dept_id);}?>
                            </select>
                        </td>
                        <td>
                            <div href=""class="oe-folder" >
                                <a href="<?php echo admin_url('admin.php?page=create_question&exam_folder_id=' . $qus->exam_folder_id . '') ?>">
                                    <span class='oe-folder-icon'><i class="fas fa-folder"></i></span>
                                </a>
                                <input <?php echo $qus->publish_exam == true ? "disabled" : "" ?> type="text" name="exam_folder_name" value="<?php echo $qus->exam_folder_name ?>">
                            </div>
                        </td>
                        <td><input <?php echo $qus->publish_exam == true ? "disabled" : "" ?>  style="width: 80px;" type="number" name="quantity" value="<?php echo $qus->quantity ?>"></td>
                        <td ><input <?php echo $qus->publish_exam == true ? "disabled" : "" ?> style="width: 70px;" type="number" name="exam_time" value="<?php echo $qus->exam_time ?>"></td>
                        <td><input <?php echo $qus->publish_exam == true ? "disabled" : "" ?> style="width: 70px;" type="number" name="pass_percentage" value="<?php echo $qus->pass_percentage ?>" /></td>
                        <td><?php echo $qus->publish_exam == 0 ? "<button data-exam-time='" . $qus->exam_time . "' data-exam-folder-id='" . $qus->exam_folder_id . "'  data-action='oe-publish-qus' id='" . $qus->exam_folder_id . "' class='oe-publish-qus oe-green'>Publish</button>" : "<span class='user-status user_inactive'>Published</span>" ?></td>
                        <td><button data-termination_date="<?php echo time() ?>" <?php echo $qus->terminate_exam == false ? 'disabled' : "" ?> class="oe-terminate <?php echo $qus->terminate_exam == false ? 'oe-yellow' : 'ow-yellow-active' ?>">Terminate</button></td>
                        <td><?php echo get_userdata($qus->examined_by)->data->display_name ?></td>
                        <td><span class='exam_status'><?php echo $qus->exam_status ?></span></td>
                        <td ><button data-exam-folder-id="<?php echo $qus->exam_folder_id ?>" data-action="update-folder"  class="oe-folder-update oe-green">Update</button></td>
                        <td><button data-exam-folder-id="<?php echo $qus->exam_folder_id ?>" data-action="delete-folder"  class="oe-folder-delete oe-red">Delete</button></td>
                    </tr>
                <?php

        }
    }
}
new Question();
