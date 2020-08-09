
<?php
class OE_qustion
{
    private $qus_data;
    private $table;
    private $published_exam;
    public function __construct()
    {
        require_once BASE_PATH . 'public/includes/html/qus_folder_subcode.php';

        global $wpdb;
        if (isset($_GET['exam_folder_id'])) {
            update_option('exam_folder_id', $_GET['exam_folder_id']);
        }
        if (get_option('exam_folder_id')) {
            $this->table = $wpdb->prefix . 'question_folder';
            $this->qus_data = $wpdb->get_results("SELECT * FROM " . $this->table . " WHERE exam_folder_id=" . get_option('exam_folder_id') . "");
            echo "<h2>Qustion_folder : " . $this->qus_data[0]->exam_folder_name . "</h2>";
            ?>
                <div  class="oe-notification">
                </div>
            <?php

            $qustion_folder_table = $wpdb->prefix . 'question_folder';
            $qustion_folder_res = $wpdb->get_results("SELECT * FROM " . $qustion_folder_table . " WHERE exam_folder_id=" . get_option('exam_folder_id') . " AND publish_exam=0");
            if ($qustion_folder_res) {
                $this->published_exam = true;
            } else {
                $this->published_exam = false;
            }
            $this->qustion_page();
        }
    }
    public function qustion_page()
    {
        echo '<div class="oe-main-qus-sec">';
        global $wpdb;
        $table = $wpdb->prefix . 'qustions';
        for ($i = 1; $i <= $this->qus_data[0]->quantity; $i++) {
            $res = $wpdb->get_results("SELECT * FROM " . $table . " WHERE exam_folder_id=" . get_option('exam_folder_id') . " AND qustion_id =" . $i . "");
            if ($res) {

                ?>
                <section class="main-qus-container">

                        <form id="<?php echo ($this->qus_data[0]->exam_folder_id * $i) ?>" class="oe-qustion_submit" action="" method="post">
                            <input type="hidden" name="exam_folder_id" value="<?php echo $this->qus_data[0]->exam_folder_id ?>">
                            <input type="hidden" name="qustion_id" value="<?php echo $i ?>">
                            <h3>
                                <label for="oe-qus-text">Qustion : </label>
                            </h3>
                            <textarea <?php echo $this->published_exam ? "" : "disabled" ?> name="oe-qus-text"  cols="50" rows="5"><?php echo $res[0]->qustion ?></textarea>
                            <div>
                                <span>
                                    <strong>
                                        <label for="qus1">A1 </label>
                                    </strong>
                                    <input
                                        <?php echo $this->published_exam ? "" : "disabled" ?>
                                            type="text"
                                            name="qus1"
                                            value="<?php echo $res[0]->a1 ?>"
                                        >
                                    <input type="hidden" name="a1_id" value="<?php echo $i + 1 ?>">
                                </span>
                                <span>
                                    <strong>
                                        <label for="qus2">A2</label>
                                    </strong>
                                    <input
                                        <?php echo $this->published_exam ? "" : "disabled" ?>
                                            type="text"
                                            name="qus2"
                                            value="<?php echo $res[0]->a2 ?>"
                                        >
                                    <input type="hidden" name="a2_id" value="<?php echo $i + 2 ?>">
                                </span>
                                <span>
                                    <strong>
                                        <label for="qus3">A3</label>
                                    </strong>
                                    <input
                                        <?php echo $this->published_exam ? "" : "disabled" ?>
                                        type="text"
                                        name="qus3"
                                        value="<?php echo $res[0]->a3 ?>"
                                    >
                                    <input type="hidden" name="a3_id" value="<?php echo $i + 3 ?>">
                                </span>
                                <span>
                                    <strong>
                                        <label for="qus4">A4</label>
                                    </strong>
                                    <input
                                        <?php echo $this->published_exam ? "" : "disabled" ?>
                                        type="text"
                                        name="qus4"
                                        value="<?php echo $res[0]->a4 ?>"
                                    >
                                    <input type="hidden" name="a4_id" value="<?php echo $i + 4 ?>">
                                </span>
                            </div>
                            <section>
                                <span>
                                    <strong>
                                        <label for="oe-qus-answer">Correct Answer :</label>
                                    </strong>
                                    <select
                                        <?php echo $this->published_exam ? "" : "disabled" ?>
                                        name="correct_ans"
                                        form="<?php echo ($this->qus_data[0]->exam_folder_id * $i) ?>"
                                    >
                                        <option selected disabled hidden>Choose Answer</option>
                                        <option <?php echo $res[0]->correct_ans == $i + 1 ? "selected" : "" ?>  value="<?php echo $i + 1 ?>" >A1</option>
                                        <option <?php echo $res[0]->correct_ans == $i + 2 ? "selected" : "" ?>  value="<?php echo $i + 2 ?>" >A2</option>
                                        <option <?php echo $res[0]->correct_ans == $i + 3 ? "selected" : "" ?> value="<?php echo $i + 3 ?>" >A3</option>
                                        <option <?php echo $res[0]->correct_ans == $i + 4 ? "selected" : "" ?> value="<?php echo $i + 4 ?>" >A4</option>
                                    </select>
                                </span>
                                <?php echo $this->published_exam ? '<input type="submit" name="qustion_submit" value="Save Qustion">' : "" ?>
                            </section>
                        </form>
                </section>
           <?php

            } else {

                ?>
                <section class="main-qus-container">
                        <form id="<?php echo ($this->qus_data[0]->exam_folder_id * $i) ?>" class="oe-qustion_submit" action="" method="post">
                            <input type="hidden" name="exam_folder_id" value="<?php echo $this->qus_data[0]->exam_folder_id ?>">
                            <input type="hidden" name="qustion_id" value="<?php echo $i ?>">
                            <h3>
                                <label for="oe-qus-text">Qustion : </label>
                            </h3>
                            <textarea <?php echo $this->published_exam ? "" : "disabled" ?> name="oe-qus-text"  cols="50" rows="5"></textarea>
                             <div>
                                <span>
                                    <strong>
                                        <label for="qus1">A1 </label>
                                    </strong>
                                    <input
                                        <?php echo $this->published_exam ? "" : "disabled" ?>
                                        type="text"
                                        name="qus1"
                                    >
                                    <input type="hidden" name="a1_id" value="<?php echo $i + 1 ?>">
                                </span>
                                <span>
                                    <strong>
                                        <label for="qus2">A2</label>
                                    </strong>
                                    <input
                                        <?php echo $this->published_exam ? "" : "disabled" ?>
                                        type="text"
                                        name="qus2"
                                    >
                                    <input type="hidden" name="a2_id" value="<?php echo $i + 2 ?>">
                                </span>
                                <span>
                                    <strong>
                                        <label for="qus3">A3</label>
                                    </strong>
                                    <input
                                        <?php echo $this->published_exam ? "" : "disabled" ?>
                                        type="text"
                                        name="qus3"
                                    >
                                    <input type="hidden" name="a3_id" value="<?php echo $i + 3 ?>">
                                </span>
                                <span>
                                    <strong>
                                        <label for="qus4">A4</label>
                                    </strong>
                                    <input
                                        <?php echo $this->published_exam ? "" : "disabled" ?>
                                        type="text"
                                        name="qus4"
                                    >
                                    <input type="hidden" name="a4_id" value="<?php echo $i + 4 ?>">
                                </span>
                            </div>
                            <section>
                                <span>
                                    <strong>
                                        <label for="oe-qus-answer">Correct Answer :</label>
                                    </strong>
                                    <select
                                        <?php echo $this->published_exam ? "" : "disabled" ?>
                                        name="correct_ans"
                                        form="<?php echo ($this->qus_data[0]->exam_folder_id * $i) ?>"
                                    >
                                        <option selected disabled hidden>Choose Answer</option>
                                        <option value="<?php echo $i + 1 ?>">A1</option>
                                        <option value="<?php echo $i + 2 ?>">A2</option>
                                        <option value="<?php echo $i + 3 ?>">A3</option>
                                        <option value="<?php echo $i + 4 ?>">A4</option>
                                    </select>
                                </span>
                                <?php echo $this->published_exam ? '<input type="submit" name="qustion_submit" value="Save Qustion">' : "" ?>
                            </section>
                        </form>
                </section>
           <?php

            }
        }
        echo '</div>';
    }
}
new OE_qustion();
?>