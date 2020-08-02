<div class="oe-notification">
</div>
<?php
class Teahers extends Base_tab
{
    private $teacher_data;

    public function __construct()
    {
        parent::__construct("Teacher's Details", "Assign Teacher");
        global $wpdb;
        $table = $wpdb->prefix . 'teacher';
        $this->teacher_data = $wpdb->get_results("SELECT * FROM " . $table . "");

        $this->tab_body();
    }

    public function tab_panel1()
    {

        ?>
            <div id="tab-one-panel" class="panel active">
                    <div style="overflow-x:auto;">
                            <table>
                                    <tr>
                                        <th>ID</th>
                                        <th>Teacher Name</th>
                                        <th>Department</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Appointment Date</th>
                                        <th>Status</th>
                                        <th>Restrict</th>
                                        <th>Restriction Date</th>
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
                                    <form id="ow_teacher_form"action="options.php" method='POST'>
                                            <?php settings_fields("ow-teacher")?>
                                            <?php do_settings_sections("manage_teachers")?>
                                            <?php submit_button("Save Teacher");?>
                                    </form>
                        </div>
                </div>
        <?php

    }
    public function info_table_rows()
    {
        if ($this->teacher_data) {
            foreach ($this->teacher_data as $teacher) {
                date_default_timezone_set(wp_timezone_string());

                ?>
                <tr>
                        <td><?php echo $teacher->teacher_id ?></td>
                        <td><input type="text" name="teacher_name" value="<?php echo $teacher->teacher_name ?>"></td>
                        <td>
                            <select style="width: 10rem" class="teacher_dept">
                                    <?php $this->dept_select_box($teacher->teacher_dept);?>
                            </select>
                        </td>
                        <td><input type="text" name="teacher_phn" value="<?php echo $teacher->teacher_phn ?>"></td>
                        <td><?php echo $teacher->teacher_email ?></td>
                        <td><?php echo date("Y-m-d   h:i:sa", $teacher->appoint_date) ?></td>
                        <td><?php echo $teacher->status == 1 ? "<span class='user-status user_active'>Active</span>" : "<span class='user-status user_inactive'>Inactive</span>" ?></td>
                        <td>
                            <?php echo $teacher->restriction == 0 ? "<button data-time=" . time() . " data-teacher-id=" . $teacher->teacher_id . " data-action='restrict-teacher'  class='oe-restrict-teacher oe-red'>Restrict</button>" : "<button data-teacher-id=" . $teacher->teacher_id . " data-action='allow-teacher'  class='oe-allow-teacher oe-green'>Allow</button>" ?>
                        </td>
                        <td style="padding: 0 10px"><?php echo $teacher->restrict_date == 0 ? "Not restricted" : date("Y-m-d   h:i:sa", $teacher->restrict_date) ?></td>
                        <td><button data-teacher-id="<?php echo $teacher->teacher_id ?>" data-action="update-teacher"  class="oe-teacher-update oe-green">Update</button></td>
                        <td><button data-teacher-id="<?php echo $teacher->teacher_id ?>" data-action="delete-teacher"  class="oe-teacher-delete oe-red">Delete</button></td>
                </tr>
            <?php

            }
        }
    }

    public function dept_select_box($teacher_dept_id)
    {
        global $wpdb;
        if ($this->dept_data) {
            $table = $wpdb->prefix . 'department';
            $this->dept_id = $wpdb->get_results("SELECT dept_name, dept_id FROM " . $table . " WHERE dept_id=" . $teacher_dept_id . "");
            if ($this->dept_id) {

                foreach ($this->dept_data as $data) {

                    ?>
                        <option
                            value="<?php echo $data->dept_id; ?> " <?php echo $data->dept_id == $teacher_dept_id ? "selected" : ""; ?> >
                            <?php echo $data->dept_name; ?>
                        </option>
                     <?php

                }
            } else {

                ?>
                    <option  selected  disabled hidden>Select Department</option>
                    <?php $this->no_data_options()?>
                <?php

            }
        }
    }
}

new Teahers();