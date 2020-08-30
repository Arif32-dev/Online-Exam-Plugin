<?php

namespace OE\includes\html;

use OE\includes\classes\Base_Tab;

class Student_Performence extends Base_Tab
{
    private $exam_folder_id;
    private $per_qus_mark;
    private $total_mark;
    private $pass_percentage;
    public function __construct()
    {
        parent::__construct("Student's Performence", '');
        $this->tab_body();
    }
    public function student_data()
    {
        if (
            isset($_GET['current_folder_id']) && $_GET['current_folder_id'] != '' &&
            isset($_GET['per_qus_mark']) && $_GET['per_qus_mark'] != '' &&
            isset($_GET['total_mark']) && $_GET['total_mark'] != '' &&
            isset($_GET['pass_percentage']) && $_GET['pass_percentage'] != ''
        ) {
            $this->exam_folder_id = rtrim(ltrim(sanitize_text_field($_GET['current_folder_id']), '"'), '"');
            $this->per_qus_mark = rtrim(ltrim(sanitize_text_field($_GET['per_qus_mark']), '"'), '"');
            $this->total_mark = rtrim(ltrim(sanitize_text_field($_GET['total_mark']), '"'), '"');
            $this->pass_percentage = rtrim(ltrim(sanitize_text_field($_GET['pass_percentage']), '"'), '"');
            global $wpdb;
            $table = $wpdb->prefix . 'students';
            $student_data = $wpdb->get_results("SELECT * FROM " . $table . " ORDER BY std_id DESC");
            return $student_data;
        }
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
                        <th>Student Mark</th>
                        <th>Exam Mark</th>
                        <th>Attendence</th>
                        <th>Performence Status</th>
                        <th>Result</th>
                    </tr>
                    <?php $this->student_performence_rows() ?>
                </table>
            </div>
        </div>
        <?php
    }

    public function student_performence_rows()
    {
        $student_data = $this->student_data();
        if ($student_data) {
            foreach ($student_data as $student) { ?>
                <tr>
                    <td><?php echo $student->std_id ?></td>
                    <td><?php echo $student->std_name ?></td>
                    <td><?php echo $this->student_mark($this->exam_folder_id, $this->per_qus_mark, $student->std_id) ?></td>
                    <td><?php echo $this->total_mark ?></td>
                    <?php echo $this->exam_attendence($this->exam_folder_id, $student->std_id) ?>
                    <?php echo $this->exam_status($this->exam_folder_id, $this->per_qus_mark, $this->pass_percentage, $this->total_mark, $student->std_id) ?>
                    <td>
                        <div class="oe-folder">
                            <a href="<?php echo admin_url('admin.php?page=individual_performence&performence_folder_id=' . $this->exam_folder_id . '&student_name=' . $student->std_name . '&std_id=' . $student->std_id . '') ?>">
                                <span class='oe-folder-icon'><i class="fas fa-folder"></i></span>
                            </a>
                        </div>
                    </td>
                </tr>
        <?php
            }
        }
    }

    public function exam_status(int $exam_folder_id, int $per_qus_mark, int $pass_percentage, int $total_mark, int $std_id)
    {
        $pass_mark = $total_mark * ($pass_percentage / 100);
        if (ceil($this->student_mark($exam_folder_id, $per_qus_mark, $std_id)) >= ceil($pass_mark)) {
            return '<td class="pass_txt"><span>Passed</span></td>';
        } else {
            return '<td class="fail_txt"><span>Failed</span></td>';
        }
    }
    public function exam_attendence(int $exam_folder_id, int $std_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'result';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE exam_folder_id=" . $exam_folder_id . " AND std_id=" . $std_id . "");
        if ($res) {
            return '<td class="attended">Attended</td>';
        } else {
            return '<td class="absent">Absent</td>';
        }
    }
    public function student_mark(int $exam_folder_id, int $per_qus_mark, int $std_id)
    {
        $total_mark = 0;
        global $wpdb;
        $table = $wpdb->prefix . 'result';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE exam_folder_id=" . $exam_folder_id . " AND std_id=" . $std_id . "");
        if ($res) {
            $table = $wpdb->prefix . 'qustions';
            foreach ($res as $result) {
                $qustion_res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE exam_folder_id=" . $exam_folder_id . " AND qustion_id=" . $result->qustion_id . "");
                if ($qustion_res) {
                    if ($result->student_ans == $qustion_res[0]->correct_ans) {
                        $total_mark += $per_qus_mark;
                    }
                }
            }
        }
        return $total_mark;
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
}
