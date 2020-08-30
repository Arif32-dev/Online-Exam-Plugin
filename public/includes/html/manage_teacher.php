<?php

namespace OE\includes\html;

use OE\includes\classes\Base_Tab;

?>
<div class="oe-notification">
</div>
<?php

class Manage_Teaher extends Base_Tab
{
    private $teacher_data;

    public function __construct()
    {
        parent::__construct("Teacher's Details", "Assign Teacher");
        global $wpdb;
        $table = $wpdb->prefix . 'teacher';
        $this->teacher_data = $wpdb->get_results("SELECT * FROM " . $table . " ORDER BY teacher_id DESC");

        $this->tab_body();
    }

    public function tab_panel1()
    { ?>

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
                    <?php $this->info_table_rows(); ?>
                </table>
            </div>

        </div>
    <?php

    }
    public function tab_panel2()
    { ?>

        <div id="tab-two-panel" class="panel">
            <div class="wrap">
                <form id="ow_teacher_form" method='POST'>
                    <?php $this->teachers_field() ?>
                    <?php submit_button("Save Teacher") ?>
                </form>
            </div>
        </div>
        <?php

    }
    public function info_table_rows()
    {
        if ($this->teacher_data) {
            foreach ($this->teacher_data as $teacher) {
                $zoneList = timezone_identifiers_list();
                if (in_array(wp_timezone_string(), $zoneList)) {
                    date_default_timezone_set(wp_timezone_string());
                } ?>

                <tr>
                    <td><?php echo $teacher->teacher_id ?></td>
                    <td><input type="text" name="teacher_name" value="<?php echo trim($teacher->teacher_name) ?>"></td>
                    <td>
                        <select style="width: 10rem" class="teacher_dept">
                            <?php $this->dept_select_box($teacher->teacher_dept); ?>
                        </select>
                    </td>
                    <td><input type="text" name="teacher_phn" value="<?php echo trim($teacher->teacher_phn) ?>"></td>
                    <td><?php echo $teacher->teacher_email ?></td>
                    <td><?php echo date("Y-m-d   h:i:sa", $teacher->appoint_date) ?></td>
                    <td><?php echo $teacher->status == 1 ? "<span class='user-status user_active'>Active</span>" : "<span class='user-status user_inactive'>Inactive</span>" ?></td>
                    <td>
                        <?php echo $teacher->restriction == 0 ? "<button data-time=" . time() . " data-teacher-id=" . $teacher->teacher_id . " data-action='restrict-teacher'  class='oe-restrict-teacher oe-red'>Restrict</button>" : "<button data-teacher-id=" . $teacher->teacher_id . " data-action='allow-teacher'  class='oe-allow-teacher oe-green'>Allow</button>" ?>
                    </td>
                    <td style="padding: 0 10px"><?php echo $teacher->restrict_date == 0 ? "Not restricted" : date("Y-m-d   h:i:sa", $teacher->restrict_date) ?></td>
                    <td><button data-teacher-id="<?php echo $teacher->teacher_id ?>" data-action="update-teacher" class="oe-teacher-update oe-green">Update</button></td>
                    <td><button data-teacher-id="<?php echo $teacher->teacher_id ?>" data-action="delete-teacher" class="oe-teacher-delete oe-red">Delete</button></td>
                </tr>
        <?php

            }
        }
    }
    public function teachers_field()
    { ?>

        <div class="s_fields">
            <div class="single_field">
                <strong>
                    <label for="teacher_name">Teacher Name:</label>
                </strong>
                <input type="text" name="teacher_name" required />
            </div>
            <br>
            <div class="single_field">
                <strong>
                    <label for="teacher_dept">Choose Department:</label>
                </strong>
                <select name="teacher_dept" required>
                    <option selected disabled hidden>Select Department</option>
                    <?php $this->no_data_options(); ?>
                </select>
            </div>
            <br>
            <div class="single_field">
                <strong>
                    <label for="teacher_username">Teacher Username:</label>
                </strong>
                <input type="text" name="teacher_username" required />
            </div>
            <br>
            <div class="single_field">
                <strong>
                    <label for="teacher_email">Teacher Email:</label>
                </strong>
                <input type="email" name="teacher_email" required />
            </div>
            <br>
            <div class="single_field" title="IF you generate or create a password please wrtie it somewhere else to remember">
                <strong>
                    <label for="teacher_pass">Teacher Login Password:</label>
                </strong>
                <div class="teacher-psw">
                    <span><i class="fas fa-key"></i></span>
                    <input type="text" name="teacher_pass" required />
                </div>
            </div>
            <br>
            <div class="single_field">
                <strong>
                    <label for="teacher_phn">Teacher Phone:</label>
                </strong>
                <input type="text" name="teacher_phn" />
            </div>
            <input type="hidden" name="appoint_date" value="<?php echo time() ?>" />
        </div>
<?php

    }
}
