<?php
if (!class_exists('Base_tab')) {
    return;
}
class Base_tab
{
    protected $param1;
    protected $param2;
    public function __construct($param1, $param2)
    {
        $this->param1 = $param1;
        $this->param2 = $param2;
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
}
