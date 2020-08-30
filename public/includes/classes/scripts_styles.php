<?php
namespace OE\includes\classes;

class Scripts_Styles
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scirpts_styles']);
    }
    public function enqueue_scirpts_styles()
    {
        wp_enqueue_style('oe-tab_style', BASE_URL . 'public/assets/css/tab.min.css');
        wp_enqueue_style('oe-table_style', BASE_URL . 'public/assets/css/table.min.css');
        wp_enqueue_style('oe-qustion', BASE_URL . 'public/assets/css/qustion.min.css');
        wp_enqueue_style('oe-individual', BASE_URL . 'public/assets/css/individual_result.min.css');
        wp_enqueue_script('oe_admin_scirpt', BASE_URL . 'public/assets/scripts/admin.min.js', 'jquery', '1.0.0', true);
        wp_enqueue_script('oe_admin_fontawesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/js/all.min.js', '', '', true);
        wp_localize_script('oe_admin_scirpt', 'file_url', [
            'department_url' => BASE_URL . 'public/includes/backend/department/department_data.php',
            'ud_department_url' => BASE_URL . 'public/includes/backend/department/ud_department.php',
            'create_teacher_url' => BASE_URL . 'public/includes/backend/teacher/create_teacher.php',
            'ud_teacher_url' => BASE_URL . 'public/includes/backend/teacher/ud_teacher.php',
            'create_question_url' => BASE_URL . 'public/includes/backend/question/create_question.php',
            'ud_question_url' => BASE_URL . 'public/includes/backend/question/ud_question.php',
            'single_qus_url' => BASE_URL . 'public/includes/backend/question/single_qustion.php',
            'exam_routine_url' => BASE_URL . 'public/includes/backend/routine/create_routine.php',
            'ud_routine_url' => BASE_URL . 'public/includes/backend/routine/ud_routine.php',
            'ud_student_url' => BASE_URL . 'public/includes/backend/student/ud_student.php',
        ]);
    }
}
