<?php
class OE_User_role
{
    private $user_role;
    public function __construct()
    {
        $this->add_teacher_role();
    }
    public function add_teacher_role()
    {
        add_role('teacher', 'Teacher');

        /**
         * giving permission for this plugin to teacher
         */

        $this->user_role = get_role('teacher');
        $this->user_role->add_cap('update_plugins', false);
        $this->user_role->add_cap('upload_files', false);
        $this->user_role->add_cap('add_users', false);
        $this->user_role->add_cap('delete_users', false);
        $this->user_role->add_cap('edit_themes', false);
        $this->user_role->add_cap('read', true);
        $this->user_role->add_cap('update_plugins', false);
        $this->user_role->add_cap('switch_themes', false);
        $this->user_role->add_cap('promote_users', false);
        $this->user_role->add_cap('edit_pages', false);
        $this->user_role->add_cap('edit_posts', false);
        $this->user_role->add_cap('edit_plugins', true);
        $this->user_role->add_cap('edit_users', true);
        $this->user_role->add_cap('list_users', false);
        $this->user_role->add_cap('customize', false);
        $this->user_role->add_cap('manage_options', false);
        $this->user_role->add_cap('edit_theme_options', false);
        $this->user_role->add_cap('edit_pages', false);
        $this->user_role->add_cap('activate_plugins', false);
        $this->user_role->add_cap('update_core', false);
        $this->user_role->add_cap('update_themes', false);
        $this->user_role->add_cap('manage_questions', true);
        $this->user_role->add_cap('manage_students', true);
        $this->user_role->add_cap('create_question', true);
        $this->user_role->add_cap('manage_routine', true);

        /**
         * giving permission for this plugin to admin
         */

        $this->user_role = get_role('administrator');
        $this->user_role->add_cap('manage_questions', true);
        $this->user_role->add_cap('manage_students', true);
        $this->user_role->add_cap('manage_teachers', true);
        $this->user_role->add_cap('manage_department', true);
        $this->user_role->add_cap('create_question', true);
        $this->user_role->add_cap('manage_routine', true);

    }
}
new OE_User_role();
