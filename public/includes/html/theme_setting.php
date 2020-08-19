<style>
    table th {
        display: none
    }
    h4{
        color: green;
    }
</style>
<h3>Please use a dummy gmail to send email to your users. Create a new gmail in order to send email to your user. Your account and password will only be used in this theme. If you delete this plugin your given info will be deleted also. Don't use a info senstive gmail.  </h3>
<h4>Recommended : Create a new gmail account and use it here</h4>
<div class="wrap">
    <p>Please deactive 2-step verification and activate your gmail less secure app turn on to send gmail <a target="blank" href="https://myaccount.google.com/lesssecureapps">Click here</a></p>
    <form action="options.php" method='POST'>
        <?php settings_fields('oe-theme-set')?>
        <?php do_settings_sections('oe_theme_setting')?>
        <?php submit_button('Save Settings');?>
    </form>
</div>