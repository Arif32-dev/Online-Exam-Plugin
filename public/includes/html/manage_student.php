<?php

namespace OE\includes\html;

use OE\includes\classes\Base_Tab;

?>
<div class="oe-notification">
</div>
<?php

class Manage_Student extends Base_Tab
{
    private $std_data;
    public function __construct()
    {
        parent::__construct('Manage Students', '');
        global $wpdb;
        $table = $wpdb->prefix . 'students';
        $this->std_data = $wpdb->get_results("SELECT * FROM " . $table . " WHERE std_password='empty' ORDER BY std_id DESC");

        $this->tab_body();
    }

    public function tab_body()
    { ?>

        <div class="worko-tabs">

            <?php $this->inputs(); ?>
            <div class="tabs flex-tabs">
                <?php $this->labels(); ?>
                <?php $this->tab_panel1(); ?>

            </div>

        </div>
    <?php

    }

    public function tab_panel1()
    { ?>

        <div id="tab-one-panel" class="panel active">
            <div style="overflow-x:auto;">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Department</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Registration Date</th>
                        <th>Status</th>
                        <th>Restrict</th>
                        <th>Restrict Date</th>
                        <th>Update</th>
                        <th>Delete</th>
                    </tr>
                    <!-- fetch all the rows for the student -->
                    <?php $this->info_table_rows() ?>
                </table>
            </div>
        </div>
    <?php

    }

    public function inputs()
    { ?>

        <input class="state" type="radio" title="tab-one" name="tabs-state" id="tab-one" checked />
    <?php

    }

    public function labels()
    { ?>

        <label for="tab-one" id="tab-one-label" class="tab"><?php echo $this->param1; ?></label>
        <?php

    }
    public function info_table_rows()
    {
        /* if variable have student data then fetch them all */
        if ($this->std_data) {
            /* set the user selected timezone for time and date function */
            $zoneList = timezone_identifiers_list();
            if (in_array(wp_timezone_string(), $zoneList)) {
                date_default_timezone_set(wp_timezone_string());
            }
            foreach ($this->std_data as $std) { ?>

                <tr>
                    <td><?php echo $std->std_id ?></td>
                    <td>
                        <input type="text" name="std_name" class="std_name" value="<?php echo sanitize_text_field(trim($std->std_name)) ?>">
                    </td>
                    <td>
                        <select style="width: 10rem" class="student_dept">
                            <?php $this->dept_select_box($std->dept_id); ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" value="<?php echo sanitize_text_field(trim($std->std_phone)) ?>" name="std_phn" />
                    </td>
                    <td>
                        <?php echo $std->std_email ?>
                    </td>
                    <td><?php echo date("Y-m-d   h:i:sa", $std->std_reg_date) ?></td>
                    <td><?php echo $std->status == 1 ? "<span class='user-status user_active'>Active</span>" : "<span class='user-status user_inactive'>Inactive</span>" ?></td>
                    <td><?php echo $std->restriction == 0 ? "<button data-time=" . time() . " data-student-id=" . $std->std_id . " data-action='restrict-student'  class='oe-restrict-student oe-red'>Restrict</button>" : "<button data-student-id=" . $std->std_id . " data-action='allow-student'  class='oe-allow-student oe-green'>Allow</button>" ?></td>
                    <td style="padding: 0 10px"><?php echo $std->restriction == 0 ? "Not restricted" : date("Y-m-d   h:i:sa", $std->restrict_date) ?></td>
                    <td><button data-student-id="<?php echo $std->std_id ?>" data-action="update-student" class="oe-student-update oe-green">Update</button></td>
                    <td><button data-student-id="<?php echo $std->std_id ?>" data-action="delete-student" class="oe-student-delete oe-red">Delete</button></td>
                </tr>
<?php
            }
        }
    }
}
