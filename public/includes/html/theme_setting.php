<?php

namespace OE\includes\html;

?>
<style>
    table th {
        display: none
    }

    h4 {
        color: green;
    }

    .url {
        width: 100%;
        display: flex;
        justify-content: flex-start;
    }

    #oe_set_page_url {
        width: 70%;
    }

    @media screen and (max-width: 800px) {
        .url {
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
        }

        #oe_copy_url {
            margin-top: 10px;
        }

        #oe_set_page_url {
            width: 95%;
        }
    }
</style>
<script>
    function copyText(e) {
        const input = document.getElementById('oe_set_page_url');
        const btn = document.getElementById('oe_copy_url');
        input.focus();
        input.select();
        input.setSelectionRange(0, 99999)
        document.execCommand("copy");
        btn.innerText = "Copied"
    }
</script>
<?php

class Theme_Setting
{
    public function __construct()
    {
        $this->render_html();
        $this->validate_uploaded_file();
    }
    public function render_html()
    { ?>
        <h3>Online Exam theme uses Gmail Api to send email <br><br>
            <div class="url">
                <input width="80px" type="text" value="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) === 'on' ? "https" : "http" . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>" id="oe_set_page_url">
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
                <input type="file" name="json_file">
                <input type="submit" name="submit" value="Upload JSON" />
            </form>
        </div>
        <?php $this->auth_btn() ?>
<?php
    }

    public function validate_uploaded_file()
    {
        if (isset($_POST['submit'])) {
            $file = $_FILES['json_file'];
            $ext = explode('.', $file['name']);
            if (!isset($file)) {
                echo $this->show_notification("error", "No file uploaded");
                return;
            }
            if ($ext[1] !== 'json' && $file['type'] !== 'application/json') {
                echo $this->show_notification("error", "File is not a json type");
                return;
            }
            $credentials = fgets(fopen($file['tmp_name'], 'r'));

            add_option('credentials');
            update_option('credentials', json_decode($credentials, true));
            if (get_option('credentials')) {
                $this->client_api(get_option('credentials'));
                fclose(fopen($file['tmp_name'], 'r'));
            }
        }
    }
    public function show_notification($res, $text)
    {
        return '<div class="notice notice-' . $res . ' is-dismissible"><p>' . $text . '</p></div>';
    }
    public function client_api($credentials)
    {
        require_once BASE_PATH . 'vendor/autoload.php';
        $client = new \Google_Client();
        $client->setApplicationName('Online Exam Gmail Setup');
        $client->setScopes(\Google_Service_Gmail::MAIL_GOOGLE_COM);
        $client->setAuthConfig($credentials);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        add_option('access_token');

        if (get_option('access_token')) {
            $client->setAccessToken(get_option('access_token'));
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.

                if (isset($_GET) && isset($_GET['code'])) {

                    update_option('access_token', $client->fetchAccessTokenWithAuthCode($_GET['code']));
                    $client->setAccessToken(get_option('access_token'));

                    // Check to see if there was an error.
                    if (array_key_exists('error', get_option('access_token'))) {
                        throw new \Exception(join(', ', get_option('access_token')));
                    }
                }
            }
        }
        return $client;
    }
    public function auth_btn()
    {
        if (get_option('credentials')) {

            echo '<h3>
                        <button id="oe_auth">Authorize Gmail Account</button>
                </h3>';
        }
    }
}
