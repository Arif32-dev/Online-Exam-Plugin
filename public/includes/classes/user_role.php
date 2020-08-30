<?php
namespace OE\includes\classes;

class User_Role
{
    /**
     * @param object $permission
     */
    private $user_role;

    /**
     * @param array $permission
     */
    private $permission;
    public function __construct()
    {
        $this->set_permisson();
        $this->set_new_role();
        $this->set_admin_role();
    }
    public function set_permisson()
    {
        $this->permission = [
            [

                'role' => 'teacher',
                'Role' => 'Teacher',
                'true_cap' => true,
                'false_cap' => false,
            ],
            [

                'role' => 'student',
                'Role' => 'Student',
                'true_cap' => false,
                'false_cap' => false,
            ],
        ];
    }
    public function set_new_role()
    {
        foreach ($this->permission as $permission) {
            /* Adding role */
            add_role($permission['role'], $permission['Role']);

            /* giving permission for this plugin to teacher */

            $this->user_role = get_role($permission['role']);

            $this->user_role->add_cap('update_plugins', $permission['false_cap']);
            $this->user_role->add_cap('upload_files', $permission['false_cap']);
            $this->user_role->add_cap('add_users', $permission['false_cap']);
            $this->user_role->add_cap('delete_users', $permission['false_cap']);
            $this->user_role->add_cap('edit_themes', $permission['false_cap']);
            $this->user_role->add_cap('read', $permission['true_cap']);
            $this->user_role->add_cap('update_plugins', $permission['false_cap']);
            $this->user_role->add_cap('switch_themes', $permission['false_cap']);
            $this->user_role->add_cap('promote_users', $permission['false_cap']);
            $this->user_role->add_cap('edit_pages', $permission['false_cap']);
            $this->user_role->add_cap('edit_posts', $permission['false_cap']);
            $this->user_role->add_cap('edit_plugins', $permission['true_cap']);
            $this->user_role->add_cap('edit_users', $permission['true_cap']);
            $this->user_role->add_cap('list_users', $permission['false_cap']);
            $this->user_role->add_cap('customize', $permission['false_cap']);
            $this->user_role->add_cap('manage_options', $permission['false_cap']);
            $this->user_role->add_cap('edit_theme_options', $permission['false_cap']);
            $this->user_role->add_cap('edit_pages', $permission['false_cap']);
            $this->user_role->add_cap('activate_plugins', $permission['false_cap']);
            $this->user_role->add_cap('update_core', $permission['false_cap']);
            $this->user_role->add_cap('update_themes', $permission['false_cap']);
            $this->user_role->add_cap('manage_questions', $permission['true_cap']);
            $this->user_role->add_cap('manage_students', $permission['true_cap']);
            $this->user_role->add_cap('create_question', $permission['true_cap']);
            $this->user_role->add_cap('manage_routine', $permission['true_cap']);
            $this->user_role->add_cap('student_performence', $permission['true_cap']);
            $this->user_role->add_cap('individual_performence', $permission['true_cap']);

        }
    }
    public function set_admin_role()
    {
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
        $this->user_role->add_cap('student_performence', true);
        $this->user_role->add_cap('individual_performence', true);

    }
}
