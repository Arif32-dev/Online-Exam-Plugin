<?php
class OE_individual_performence
{
    private $exam_folder_id;
    private $exam_folder_data;
    private $std_id;
    public function __construct()
    {
        if (isset($_GET['performence_folder_id']) && !empty($_GET['performence_folder_id']) &&
            isset($_GET['std_id']) && !empty($_GET['std_id'])) {
            $this->exam_folder_id = rtrim(ltrim(sanitize_text_field($_GET['performence_folder_id']), '"'), '"');
            $this->std_id = rtrim(ltrim(sanitize_text_field($_GET['std_id']), '"'), '"');
            $this->exam_folder_data = $this->qustion_folder_check($this->exam_folder_id, 'Finished');
            if ($this->exam_folder_data) {
                if (!$this->department_data($this->exam_folder_data)) {
                    return;
                }
                if ($this->check_perticipant($this->exam_folder_data, $this->std_id)) {
                    $this->exam_folder_table();
                } else {
                    $text = "" . $_GET['student_name'] . " didn't perticipated in exam";
                    $btn = "Back to Result";
                    $url = admin_url('admin.php?page=manage_questions');
                    $this->notify_msg($text, $btn, $url);
                }
            }
        } else {
            $text = "Please check previous exam results";
            $btn = "Previous Result's";
            $url = admin_url('admin.php?page=manage_questions');
            $this->notify_msg($text, $btn, $url);
        }
    }
    public function exam_folder_table()
    {

        ?>
            <section class="exam_results">
                <h1>Exam Result</h1>
                <div class="current_result">

                    <div class="current_exam_info">
                        <div class="info">
                            <h3>Exam Name : <?php echo $this->exam_folder_data[0]->exam_folder_name ?></h3>
                            <h3>Department : <?php echo $this->department_data($this->exam_folder_data)[0]->dept_name ?></h3>
                        </div>
                        <div class="total_mark">
                            <h3>Total Mark : <?php echo $this->exam_folder_data[0]->total_mark ?></h3>
                            <h3>Your Mark : <?php echo $this->aquired_mark($this->exam_folder_data, $this->std_id) ?></h3>
                        </div>
                    </div>

                    <div class="result">
                        <div class="header">
                            <span>Qustion</span>
                            <span>Correct Answer</span>
                            <span>Student Answer</span>
                            <span>Answer Status</span>
                        </div>
                        <div class="result_container">
                            <?php $this->student_result($this->exam_folder_id, $this->std_id)?>
                        </div>
                    </div>

                </div>
            </section>
        <?php

    }
    public function result_loader($result, $exam_folder_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'qustions';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE exam_folder_id=" . $exam_folder_id . " AND qustion_id=" . $result->qustion_id . "");
        if (!$res) {
            return;
        }

        ?>
               <div class="show_result">
                        <div class="title">
                            <span>Qustion :</span>
                        </div>
                        <div class="qustion">
                            <p><?php echo stripslashes(stripslashes($res[0]->qustion)) ?></p>
                        </div>

                        <div class="title">
                            <span>Correct Answer :</span>
                        </div>
                        <div class="correct_ans">
                            <p><?php echo $this->correct_ans($res) ?></p>
                        </div>

                        <div class="title">
                            <span>Student Answer :</span>
                        </div>
                        <div class="std_answer">
                            <span><?php echo $this->student_answer($result, $res) ?></span>
                        </div>

                        <div class="title">
                            <span>Answer Status :</span>
                        </div>
                        <div class="answer_status">
                            <?php echo $this->answer_status($result, $res) ?>
                        </div>
                </div>
        <?php

    }
    public function student_result($exam_folder_id, $std_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'result';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE exam_folder_id=" . $exam_folder_id . " AND std_id=" . $std_id . "");
        if ($res) {
            foreach ($res as $result) {
                $this->result_loader($result, $exam_folder_id);
            }
        }
    }
    public function correct_ans($res)
    {
        if ($res) {
            if ($res[0]->correct_ans == $res[0]->a1_id) {
                return $res[0]->a1;
            }
            if ($res[0]->correct_ans == $res[0]->a2_id) {
                return $res[0]->a2;
            }
            if ($res[0]->correct_ans == $res[0]->a3_id) {
                return $res[0]->a3;
            }
            if ($res[0]->correct_ans == $res[0]->a4_id) {
                return $res[0]->a4;
            }
        }
    }
    public function answer_status($result, $res)
    {
        if ($res) {
            if ($res[0]->correct_ans == $result->student_ans) {
                return '<span class="correct_ans">Correct</span>';
            } else {
                return '<span class="false_ans">Wrong</span>';
            }
        }
    }
    public function student_answer($result, $res)
    {
        if ($res) {
            if ($res[0]->a1_id == $result->student_ans) {
                return $res[0]->a1;
            }
            if ($res[0]->a2_id == $result->student_ans) {
                return $res[0]->a2;
            }
            if ($res[0]->a3_id == $result->student_ans) {
                return $res[0]->a3;
            }
            if ($res[0]->a4_id == $result->student_ans) {
                return $res[0]->a4;
            }
        }
    }
    public function aquired_mark($exam_folder_data, $std_id)
    {
        $total_mark = 0;
        global $wpdb;
        $table = $wpdb->prefix . 'result';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE exam_folder_id=" . $exam_folder_data[0]->exam_folder_id . " AND std_id=" . $std_id . "");
        if ($res) {
            $table = $wpdb->prefix . 'qustions';
            foreach ($res as $result) {
                $qustion_res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE exam_folder_id=" . $exam_folder_data[0]->exam_folder_id . " AND qustion_id=" . $result->qustion_id . "");
                if ($qustion_res) {
                    if ($result->student_ans == $qustion_res[0]->correct_ans) {
                        $total_mark += $exam_folder_data[0]->per_qus_mark;
                    }
                }
            }
        }
        return $total_mark;
    }
    public function check_perticipant($exam_folder_data, $std_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'result';
        $res = $wpdb->get_results("SELECT std_id FROM " . $table . " WHERE std_id=" . $std_id . " AND exam_folder_id=" . $exam_folder_data[0]->exam_folder_id . "");
        return $res;
    }
    public function department_data($exam_folder_data)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'department';
        $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE dept_id=" . $exam_folder_data[0]->dept_id . "");
        return $res;
    }
    public function qustion_folder_check($exam_folder_id, $status)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'question_folder';
        $exam_folder_data = $wpdb->get_results("SELECT * FROM " . $table . " WHERE exam_folder_id=" . $exam_folder_id . " AND exam_status='$status' ORDER BY exam_folder_id DESC");
        return $exam_folder_data;
    }
    public function notify_msg($text, $btn, $url)
    {

        ?>
            <section class="oe-verifcation">
                <div class="veri_container">
                    <div class="ver_msg">
                        <p><?php echo $text ?></p>
                    </div>
                    <a href="<?php echo $url ?>"><?php echo $btn ?></a>
                </div>
            </section>
        <?php

    }
}
new OE_individual_performence();