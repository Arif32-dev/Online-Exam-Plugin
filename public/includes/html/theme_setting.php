<?php

namespace OE\includes\html;

?>
<div class="oe-notification">
</div>
<script>
    function copyText(e) {
        const input = document.getElementById('oe_set_page_url');
        const btn = document.getElementById('oe_copy_url');
        input.focus();
        input.select();
        input.setSelectionRange(0, 99999)
        document.execCommand("copy");
        btn.innerText = "Copied"
        btn.classList.add('copied')
    }
</script>
<?php

class Theme_Setting
{
    public function __construct()
    {
        $this->render_html();
        $this->handle_uploaded_file();
        $this->client_api();
    }

    public function render_html()
    { ?>
        <h3 id="theme_setting_header">Online Exam theme uses Gmail Api to send email <br><br>
            <div class="url">
                <input width="80px" type="text" value="<?php echo $this->page_url() ?>" id="oe_set_page_url">
                <button id="oe_copy_url" onclick="copyText()">Copy URL</button>
            </div>
            <br>
            <i>Please copy this URL and paste it into the "Authorized redirect URIs" field of your Google web application
                <a href="https://console.developers.google.com/apis/credentials" target="_blank">Click Here</a>
            </i><br><br>
            <i>Downlaod the JSON File and upload it here</i>
        </h3>
        <div class="wrap">
            <form id="gmail_json" action="" method='POST' enctype="multipart/form-data">
                <label for="json_file" id="file_label">Choose JSON File</label>
                <input type="file" name="json_file" id="json_file">
                <input type="submit" name="submit" value="Upload JSON" />
            </form>
        </div>
        <?php echo $this->auth_btn() ?>
        <?php $this->test_msg_field() ?>
        <?php $this->set_theme_timezone() ?>
        <?php
    }
    public function page_url()
    {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) === 'on' ?
            "https" : "http" . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if (isset($_GET) && isset($_GET['code'])) {
            $replaced_url = preg_replace('/\&.*/', '', $url);
            return $replaced_url;
        } else {
            return $url;
        }
    }
    public function handle_uploaded_file()
    {
        if (isset($_POST['submit'])) {
            $file = $_FILES['json_file'];
            $ext = explode('.', $file['name']);
            if (empty($file['name'])) {
                echo $this->show_notification("error", "No file uploaded");
                return;
            }
            if ($ext[1] !== 'json' && $file['type'] != 'application/json') {
                echo $this->show_notification("error", "File is not a json type");
                return;
            }
            $credentials = fgets(fopen($file['tmp_name'], 'r'));
            add_option('credentials');
            update_option('credentials', json_decode($credentials, true));
            if (get_option('credentials')) {
                echo $this->show_notification("success", "File saved successfully");
                fclose(fopen($file['tmp_name'], 'r'));
            }
        }
    }

    public function show_notification($res, $text)
    {
        return '<div class="notice notice-' . $res . ' is-dismissible"><p>' . $text . '</p></div>';
    }
    public function client_api()
    {
        require_once BASE_PATH . 'vendor/autoload.php';
        $client = new \Google_Client();
        $client->setApplicationName('Online Exam Gmail Setup');
        $client->setScopes(\Google_Service_Gmail::MAIL_GOOGLE_COM);
        $client->setAuthConfig(get_option('credentials'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        add_option('access_token');

        if (get_option('access_token')) {
            $client->setAccessToken(get_option('access_token'));
        }
        if (isset($_GET) && isset($_GET['code'])) {
            update_option('access_token', $client->fetchAccessTokenWithAuthCode($_GET['code']));
            wp_safe_redirect(admin_url('admin.php?page=oe_theme_setting'));
            exit;
        }
        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                if (get_option('access_token')) {
                    $client->setAccessToken(get_option('access_token'));
                    if (array_key_exists('error', get_option('access_token'))) {
                        throw new \Exception(join(', ', get_option('access_token')));
                    }
                }
            }
        }
    }
    public function auth_btn()
    {
        if (get_option('credentials')) {
            if (get_option('access_token')) {

                $msg = "Verified";
                $class = "verified";
            } else {
                $msg = "Authorize Gmail Account";
                $class = "invalid";
            }
            return '<h3 id="oe_auth_wrap">
                            <a class="' . $class . '" href="' . $this->get_auth_url() . '" target="_blank" >
                                ' . $msg . '
                            </a>
                        </h3>';
        }
    }
    public function test_msg_field()
    {
        if (get_option('access_token')) { ?>
            <form id="oe_gmail_test">
                <input type="email" value="<?php echo get_option('admin_email') ?>" name="target_email">
                <input type="submit" value="Send Test Messege">
            </form>
        <?php
        }
    }
    public function get_auth_url()
    {
        require_once BASE_PATH . 'vendor/autoload.php';
        $client = new \Google_Client();
        $client->setApplicationName('Online Exam Gmail Setup');
        $client->setScopes(\Google_Service_Gmail::MAIL_GOOGLE_COM);
        $client->setAuthConfig(get_option('credentials'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $authUrl = $client->createAuthUrl();
        return $authUrl;
    }
    public function set_theme_timezone()
    { ?>
        <br>
        <a id="theme_time_zone" href="<?php echo admin_url('options-general.php') ?>">Set Theme Timezone</a>
<?php
    }
}
