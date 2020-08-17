<?php
/**
 * @class Admin_menu
 */
if (!class_exists('Admin_menu')) {
    return;
}
class Admin_menu
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_menus']);
    }
    public function add_admin_menus()
    {

        /**
         * adding main menu to admin page
         */

        add_menu_page(
            'Online Exam',
            'Online Exam',
            'manage_department',
            'online_exam',
            function () {
                Callback::add_admin_menu();
            },
            'dashicons-text-page',
        );

        /**
         * adding all the submens to admin page
         * this is teacher's submenu page
         */

        add_submenu_page(
            // parant slug
            'online_exam',
            // page title
            "Manage Teacher's",
            // menu title
            "Manage Teacher's",
            // capability
            'manage_teachers',
            // menu slug
            'manage_teachers',
            // callback function
            function () {
                Callback::submenu_teachers();
            },
        );

        /**
         * adding all the submens to admin page
         * this is all questions managing page
         */

        add_submenu_page(
            // parant slug
            'online_exam',
            // page title
            "Manage Questions",
            // menu title
            "Manage Questions",
            // capability
            'manage_questions',
            // menu slug
            'manage_questions',
            // callback function
            function () {
                Callback::submenu_questions();
            },
        );

        /**
         * adding all the submens to admin page
         * this is all questions managing page
         */

        add_submenu_page(
            // parant slug
            'online_exam',
            // page title
            "Exam Routine",
            // menu title
            "Exam Routine",
            // capability
            'manage_routine',
            // menu slug
            'manage_routine',
            // callback function
            function () {
                Callback::submenu_routine();
            },
        );

        /**
         * adding all the submens to admin page
         * this is all questions managing page
         */

        add_submenu_page(
            // parant slug
            'online_exam',
            // page title
            "Manage Students",
            // menu title
            "Manage Students",
            // capability
            'manage_students',
            // menu slug
            'manage_students',
            // callback function
            function () {
                Callback::submenu_students();
            },
        );

        /**
         * adding  create all question page according to question folder
         * this is question creation page
         */
        if (isset($_GET['exam_folder_id'])) {
            add_submenu_page(
                // parant slug
                'online_exam',
                // page title
                "Create Qustion",
                // menu title
                "Create Qustion",
                // capability
                'create_question',
                // menu slug
                'create_question',
                // callback function
                function () {
                    Callback::submenu_create_qus();
                },
            );
        }

        /**
         * adding  student performence page according to question folder
         * this is question creation page
         */
        if (isset($_GET['current_folder_id'])) {
            add_submenu_page(
                // parant slug
                'online_exam',
                // page title
                "Student's Performence",
                // menu title
                "Student's Performence",
                // capability
                'student_performence',
                // menu slug
                'student_performence',
                // callback function
                function () {
                    Callback::student_performence();
                },
            );
        }

        /**
         * adding individual performence page according to question folder
         * this is question creation page
         */
        if (isset($_GET['performence_folder_id'])) {
            add_submenu_page(
                // parant slug
                'online_exam',
                // page title
                "" . $_GET['student_name'] . "'s Performence",
                // menu title
                "" . $_GET['student_name'] . "'s Performence",
                // capability
                'individual_performence',
                // menu slug
                'individual_performence',
                // callback function
                function () {
                    Callback::individual_performence();
                },
            );
        }

        /**
         * renaming online exam menu
         */

        $this->admin_menu_rename();
    }
    public function admin_menu_rename()
    {
        global $submenu;
        if ($submenu['online_exam'][0][0] == 'Online Exam') {
            $submenu['online_exam'][0][0] = 'Manage Department';
        }
    }
}
new Admin_menu();
