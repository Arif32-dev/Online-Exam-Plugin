<?php
class Students extends Base_tab
{
    public function __construct()
    {
        parent::__construct('Manage Students', '');
        $this->tab_body();
    }

    public function tab_body()
    {

        ?>
            <div class="worko-tabs">

                 <?php $this->inputs();?>
                <div class="tabs flex-tabs">
                    <?php $this->labels();?>
                    <?php $this->tab_panel1();?>

                </div>

            </div>
    <?php

    }

    public function tab_panel1()
    {

        ?>
            <div id="tab-one-panel" class="panel active">
                    <div style="overflow-x:auto;">
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Department</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Registration Date</th>
                                <th>Status</th>
                                <th>Restrict</th>
                                <th>Restrict Date</th>
                                <th>Delete</th>
                            </tr>
                            <tr>
                                <td>Jill</td>
                                <td>Smith</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                                <td>50</td>
                            </tr>
                        </table>
                    </div>
            </div>
        <?php

    }

    public function inputs(Type $var = null)
    {

        ?>
                 <input class="state" type="radio" title="tab-one" name="tabs-state" id="tab-one" checked />
        <?php

    }

    public function labels()
    {

        ?>
                <label for="tab-one" id="tab-one-label" class="tab"><?php echo $this->param1; ?></label>
        <?php

    }
}
new Students();
