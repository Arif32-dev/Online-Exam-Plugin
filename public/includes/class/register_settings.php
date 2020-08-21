<?php
class Settings_api
{
    public function __construct()
    {
        add_action('admin_init', [$this, 'register_settings']);
    }
    public function register_settings()
    {
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
            'oe-theme-sec',
            '',
            "",
            'oe_theme_setting'
        );

    }

    public function settings_fields()
    {
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
}
new Settings_api();
