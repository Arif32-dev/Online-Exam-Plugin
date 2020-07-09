<?php
if (!class_exists('Scripts_styles')) {
    return;
}
class Scripts_styles
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scirpts_styles']);
    }
    public function enqueue_scirpts_styles()
    {
        wp_enqueue_style('tab_style', BASE_URL . 'public/assets/css/tab.min.css');
        wp_enqueue_style('table_style', BASE_URL . 'public/assets/css/table.min.css');
        wp_enqueue_script('ow_admin_scirpt', BASE_URL . 'public/assets/scripts/admin.min.js', 'jquery', '1.0.0', true);
        wp_enqueue_script('ow_admin_fontawesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/js/all.min.js', '', '', true);
        wp_localize_script('ow_admin_scirpt', 'file_url', [
            'department_url' => BASE_URL . 'public/includes/backend/department/department_data.php',
            'ud_department_url' => BASE_URL . 'public/includes/backend/department/ud_department.php',
            'create_teacher_url' => BASE_URL . 'public/includes/backend/teacher/create_teacher.php',
            'ud_teacher_url' => BASE_URL . 'public/includes/backend/teacher/ud_teacher.php',
            'create_question_url' => BASE_URL . 'public/includes/backend/question/create_question.php',
            'ud_question_url' => BASE_URL . 'public/includes/backend/question/ud_question.php',
        ]);
    }
}
new Scripts_styles();
