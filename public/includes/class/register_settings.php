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
        register_setting('oe-teacher', 'teacher_name');
        register_setting('oe-teacher', 'teacher_dept');
        register_setting('oe-teacher', 'teacher_username');
        register_setting('oe-teacher', 'teacher_email');
        register_setting('oe-teacher', 'teacher_pass');
        register_setting('oe-teacher', 'teacher_phn');
        /**
         * registering Teachers and fields for Assign Teacher  tab
         */
        register_setting('oe-question', 'exam_folder');
        register_setting('oe-question', 'quantity');
        register_setting('oe-question', 'est_time');
        register_setting('oe-question', 'pass_percentage');

        /**
         * registering exam routine and fields for Assign Teacher  tab
         */
        register_setting('oe-exam-routine', 'department');
        register_setting('oe-exam-routine', 'exam_name');
        register_setting('oe-exam-routine', 'exam_date');

        /**
         * registering theme settings for online-exam theme
         */
        register_setting('oe-theme-set', 'mailer_gmail');
        register_setting('oe-theme-set', 'mailer_pass');

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

        add_settings_section(
            'oe-theme-sec',
            '',
            "",
            'oe_theme_setting'
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
        add_settings_field(
            'mailer_setting',
            '',
            [$this, 'mailer_setting'],
            'oe_theme_setting',
            'oe-theme-sec',
        );

    }
    public function mailer_setting()
    {

        ?>
            <label for="gmail">Your Gmail :</label>
            <input type="text" placeholder="Your gmail account" name="mailer_gmail" value="<?php echo get_option('mailer_gmail') ? get_option('mailer_gmail') : "" ?>">
            <br>
            <br>
            <label for="gmail">Gmail Password :</label>
            <input type="password" placeholder="Your gmail password" name="mailer_pass" value="<?php echo get_option('mailer_pass') ? get_option('mailer_pass') : "" ?>">
            <br>
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
}
new Settings_api();
