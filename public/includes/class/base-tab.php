<?php
if (!class_exists('Base_tab')) {
    return;
}
class Base_tab
{
    protected $param1;
    protected $param2;
    protected $table;
    protected $dept_data;
    protected $dept_id;
    public function __construct($param1, $param2)
    {
        $this->param1 = $param1;
        $this->param2 = $param2;

        global $wpdb;
        $this->table = $wpdb->prefix . 'department';
        $this->dept_data = $wpdb->get_results("SELECT * FROM " . $this->table . "");

    }
    public function tab_body()
    {

        ?>
            <div class="worko-tabs">

                 <?php $this->inputs();?>
                <div class="tabs flex-tabs">
                    <?php $this->labels();?>
                    <?php $this->tab_panel1();?>
                    <?php $this->tab_panel2();?>

                </div>

            </div>
        <?php

    }
    public function inputs()
    {

        ?>
                 <input class="state" type="radio" title="tab-one" name="tabs-state" id="tab-one" checked />
                <input class="state" type="radio" title="tab-two" name="tabs-state" id="tab-two" />
        <?php

    }
    public function labels()
    {

        ?>
                <label for="tab-one" id="tab-one-label" class="tab"><?php echo $this->param1; ?></label>
                <label for="tab-two" id="tab-two-label" class="tab"><?php echo $this->param2; ?></label>
        <?php

    }

    public function tab_panel1()
    {

        ?>
                  <div id="tab-one-panel" class="panel active">
                        <h1>panel 1</h1>
                    </div>
        <?php

    }
    public function tab_panel2()
    {

        ?>
                   <div id="tab-two-panel" class="panel">
                        Tab two content
                    </div>
        <?php

    }

    public function admin_select_box(int $dept_id, int $user_id)
    {
        global $wpdb;
        if ($this->dept_data) {

            if (get_userdata($user_id)->roles[0] == 'administrator') {
                $this->table = $wpdb->prefix . 'department';
                $this->dept_id = $wpdb->get_results("SELECT dept_name, dept_id FROM " . $this->table . " WHERE dept_id=" . $dept_id . "");
                if ($this->dept_id) {
                    $this->select_options($dept_id);
                } else {

                    ?>
                            <option  selected  disabled hidden>Select Department</option>
                    <?php

                    $this->no_data_options();
                }

            } else {
                // if database user id is a teacher roll
                if (get_userdata($user_id)->roles[0] == 'teacher') {
                    /**
                     * if teacher is active the method is going to run
                     * but if a teacher is deleted all its data is going to be deleted automatically
                     */
                    $this->teacher_dept($dept_id);
                } else {
                    /**
                     * if teacher is restricted it will call this method
                     */
                    $this->teacher_dept($dept_id);
                }
            }

        }

    }
    /**
     * this method is going to fetch only choosen department for a teacher
     * in admin login admin cant change selected teacher department
     * @param int $dept_id
     * @return this method is is going to return selected department selectbox options
     */
    public function teacher_dept(int $dept_id)
    {
        global $wpdb;
        $this->table = $wpdb->prefix . 'department';
        $dept_data = $wpdb->get_results("SELECT dept_name, dept_id FROM " . $this->table . " WHERE dept_id=" . $dept_id . "");

        if ($dept_data) {

            ?>
                <option value="<?php echo $dept_data[0]->dept_id ?>" selected  >
                    <?php echo $dept_data[0]->dept_name ?>
                </option>
            <?php

        } else {

            ?>
                <option value=""  selected  disabled hidden>No Department</option>
            <?php

        }
    }
    /**
     * if a teacher is logged in..  this method is going to fetch department only selected to teacher id
     * @param int $dept_id
     * @return this method is going o return only selected department. Different teacher cant see each other created question
     */
    public function teacher_select_box(int $dept_id)
    {
        global $wpdb;
        $this->table = $wpdb->prefix . 'department';
        $this->dept_data = $wpdb->get_results("SELECT dept_name, dept_id FROM " . $this->table . " WHERE dept_id=" . $dept_id . "");
        if ($this->dept_data) {

            ?>
                <option value="<?php echo $this->dept_data[0]->dept_id ?>" selected  >
                    <?php echo $this->dept_data[0]->dept_name ?>
                </option>
            <?php

        } else {

            ?>
                 <option value=""  selected  disabled hidden>No Department</option>
            <?php

        }
    }

    public function select_options(int $dept_id)
    {

        if ($this->dept_data) {
            foreach ($this->dept_data as $data) {

                ?>
                    <option
                        value="<?php echo $data->dept_id; ?> " <?php echo $data->dept_id == $dept_id ? "selected" : ""; ?> >
                        <?php echo $data->dept_name; ?>
                    </option>
                <?php

            }
        }
    }
    public function no_data_options()
    {

        if ($this->dept_data) {
            foreach ($this->dept_data as $data) {

                ?>
                <option value="<?php echo $data->dept_id; ?>"><?php echo $data->dept_name; ?></option>
                <?php

            }
        }
    }
}
