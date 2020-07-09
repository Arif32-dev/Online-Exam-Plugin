<?php
settings_errors();
?>
<div class="oe-notification">
</div>
<?php
class Department extends Base_tab
{
    private $dept_data;
    public function __construct()
    {
        parent::__construct('Department Details', 'Create Department');
        global $wpdb;
        $table = $wpdb->prefix . 'department';
        $this->dept_data = $wpdb->get_results("SELECT * FROM " . $table . "");
        $this->tab_body();
    }
    public function tab_panel1()
    {

        ?>
            <div id="tab-one-panel" class="panel active">
                <div style="overflow-x:auto;">
                    <table>
                        <tr>
                            <th>Department Name</th>
                            <th>Date Created</th>
                            <th>Last Updated</th>
                            <th>Update</th>
                            <th>Delete</th>
                            <th>Author</th>
                        </tr>
                        <?php $this->info_table_rows();?>
                    </table>
                </div>
            </div>
        <?php

    }

    public function tab_panel2()
    {

        ?>
            <div id="tab-two-panel" class="panel">
                <div class="wrap">
                    <form id="department_form"action="options.php" method='POST'>
                            <?php settings_fields('department')?>
                            <?php do_settings_sections('online_exam')?>
                            <?php submit_button('Save Department');?>
                    </form>
                </div>
            </div>
        <?php

    }
    public function info_table_rows()
    {
        if ($this->dept_data) {
            foreach ($this->dept_data as $data) {
                date_default_timezone_set(wp_timezone_string());
                $last_updated;
                if ($data->dept_last_updated != 0) {
                    $last_updated = date("Y-m-d  h:i:sa", $data->dept_last_updated);
                } else {
                    $last_updated = "Not updated";
                }
                echo '<tr>
                            <td><input type="text" value="' . $data->dept_name . '"/></td>
                            <td>' . date("Y-m-d  h:i:sa", $data->dept_create_date) . '</td>
                            <td>' . $last_updated . '</td>
                            <td><button data-update-date="' . time() . '" data-action="update" id="' . $data->dept_create_date . '" class="oe-update oe-green">Update</button></td>
                            <td><button data-action="delete" id="' . $data->dept_create_date . '" class="oe-delete oe-red">Delete</button></td>
                            <td>' . get_userdata($data->dept_author)->data->user_nicename . '</td>
                    </tr>';
            }
        } else {
            return '';
        }
    }
}
new Department();
?>
