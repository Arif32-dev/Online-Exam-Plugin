<?php
class Settings_api
{
    private $dept_data;
    public function __construct()
    {
        add_action('admin_init', [$this, 'register_settings']);
        global $wpdb;
        $table = $wpdb->prefix . 'department';
        $this->dept_data = $wpdb->get_results("SELECT dept_name, dept_id FROM " . $table . "");
    }
    public function register_settings()
    {
        /**
         * registering department and fields for department tab
         */
        register_setting('department', 'dept_name');
        /**
         * registering Teachers and fields for Assign Teacher  tab
         */
        register_setting('ow-teacher', 'teacher_name');
        register_setting('ow-teacher', 'teacher_dept');
        register_setting('ow-teacher', 'teacher_username');
        register_setting('ow-teacher', 'teacher_email');
        register_setting('ow-teacher', 'teacher_pass');
        register_setting('ow-teacher', 'teacher_phn');
        /**
         * registering Teachers and fields for Assign Teacher  tab
         */
        register_setting('ow-question', 'exam_folder');
        register_setting('ow-question', 'quantity');
        register_setting('ow-question', 'est_time');
        register_setting('ow-question', 'pass_percentage');

        /**
         * registering exam routine and fields for Assign Teacher  tab
         */
        register_setting('ow-exam-routine', 'department');
        register_setting('ow-exam-routine', 'exam_name');
        register_setting('ow-exam-routine', 'exam_date');

        $this->settings_section();
        $this->settings_fields();
    }
    public function settings_section()
    {
        add_settings_section(
            'dept_section',
            '',
            "",
            'online_exam'
        );
        add_settings_section(
            'teacher_section',
            '',
            "",
            'manage_teachers'
        );
        add_settings_section(
            'question_section',
            '',
            "",
            'manage_questions'
        );
        add_settings_section(
            'routine_section',
            '',
            "",
            'manage_routine'
        );
    }

    public function settings_fields()
    {
        add_settings_field(
            'dept_name',
            '',
            [$this, 'dept_field'],
            'online_exam',
            'dept_section',
        );
        add_settings_field(
            'teacher_field',
            '',
            [$this, 'teachers_field'],
            'manage_teachers',
            'teacher_section',
        );
        add_settings_field(
            'question_field',
            '',
            [$this, 'question_field'],
            'manage_questions',
            'question_section',
        );
        add_settings_field(
            'routine_field',
            '',
            [$this, 'routine_field'],
            'manage_routine',
            'routine_section',
        );
    }

    public function dept_field()
    {

        ?>
                <div class="s_fields">
                        <div class="single_field">
                            <strong>
                                <label for="dept">Department Name:</label>
                            </strong>
                            <input type="text" name="dept_name"  />
                        </div>
                        <input type="hidden" name="dept_create_date"  value="<?php echo time(); ?>" />
                        <input type="hidden" name="dept_author"  value="<?php echo get_current_user_id(); ?>" />
                  </div>
            <?php

    }
    public function teachers_field()
    {

        ?>
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
                                <option  selected  disabled hidden>Select Department</option>
                                <?php echo $this->select_options(); ?>
                            </select>
                    </div>
                    <br>
                    <div class="single_field">
                            <strong>
                                <label for="teacher_username" >Teacher Username:</label>
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
                            <input type="text" name="teacher_pass"  required />
                            </div>
                    </div>
                    <br>
                    <div class="single_field">
                            <strong>
                                <label for="teacher_phn">Teacher Phone:</label>
                            </strong>
                            <input type="text" name="teacher_phn"  />
                    </div>
                    <input type="hidden" name="appoint_date"  value="<?php echo time() ?>" />
            </div>
            <?php

    }
    public function question_field()
    {

        ?>
        <div class="s_fields">
                        <div class="single_field">
                            <strong>
                                <label for="dept_id">Department:</label>
                            </strong>
                           <?php $this->user_data()?>
                        </div>
                        <br>
                        <div class="single_field">
                            <strong>
                                <label for="exam_folder">Create Exam Folder:</label>
                            </strong>
                            <input style="width: 11.5rem;" type="text" name="exam_folder" placeholder="Exam Name" required/>
                        </div>
                        <br>
                        <div class="single_field">
                            <strong>
                                <label for="quantity">Question Quantity:</label>
                            </strong>
                            <input type="number" placeholder="25" name="quantity" required />
                        </div>
                        <br>
                        <div class="single_field">
                            <strong>
                                <label for="est_time">Estimated Exam Time:</label>
                            </strong>
                            <input type="number" placeholder="40 min" name="est_time" required />
                        </div>
                        <br>
                        <div class="single_field">
                            <strong>
                                <label for="pass_percentage">Pass Percentage:</label>
                            </strong>
                            <input type="number" placeholder="33%" name="pass_percentage"  required />
                        </div>
                        <br>
                        <div class="single_field">
                            <strong>
                                <label for="per_qus_mark">Per Qustion Mark:</label>
                            </strong>
                            <input type="number" placeholder="Each qustion mark" name="per_qus_mark"  required />
                        </div>
                        <br>
            </div>
            <?php

    }
    public function routine_field()
    {

        ?>
        <div class="s_fields">
             <div class="single_field">
                    <strong>
                        <label for="teacher_dept">Choose Department:</label>
                    </strong>
                    <?php $this->user_data()?>
            </div>
               <br>
             <div class="single_field">
                    <strong>
                        <label for="teacher_dept">For Exam :</label>
                    </strong>
                    <input type="text" name="exam_name" required placeholder="Upcoming exam name">
            </div>
               <br>
             <div class="single_field">
                    <strong>
                        <label for="teacher_dept">Choose Date:</label>
                    </strong>
                    <input type="date" name="exam_date" required>
            </div>
        </div>
        <?php

    }

    public function select_options()
    {
        $select_options = '';
        if ($this->dept_data) {
            foreach ($this->dept_data as $data) {
                $select_options .= '<option value="' . $data->dept_id . '">' . $data->dept_name . '</option>';
            }
        }
        return $select_options;
    }

    public function user_data()
    {
        if (get_userdata(get_current_user_id())->roles[0] == 'teacher') {
            global $wpdb;

            $table = $wpdb->prefix . 'teacher';
            $teacher_dept = $wpdb->get_results("SELECT teacher_dept FROM " . $table . " WHERE teacher_id=" . get_current_user_id() . "");

            $table = $wpdb->prefix . 'department';
            $this->dept_data = $wpdb->get_results("SELECT dept_name, dept_id FROM " . $table . " WHERE dept_id=" . $teacher_dept[0]->teacher_dept . "");

            ?>
                <select required style="width: 9rem" name="dept_id">
                    <option value="<?php echo $this->dept_data[0]->dept_id; ?>" selected><?php echo $this->dept_data[0]->dept_name; ?></option>
                </select>
             <?php

        } elseif (get_userdata(get_current_user_id())->roles[0] == 'administrator') {

            ?>
                <select name="dept_id"  required>
                    <option  selected  disabled hidden>Select Department</option>
                    <?php echo $this->select_options(); ?>
                </select>
            <?php

        }
    }
}
new Settings_api();
