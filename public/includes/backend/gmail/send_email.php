<?php
require_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__DIR__))))))) . '/wp-load.php';
require_once BASE_PATH . 'vendor/autoload.php';
class Send_Email
{
    public function __construct()
    {
        if (!$_POST['target_email'] && $_POST['target_email'] == null) {
            echo json_encode([
                'res' => 'empty_field',
                'returned' => 'empty'
            ]);
            return;
        }
        $this->send_messege();
    }
    public function service()
    {
        $client = $this->get_client();
        $service = new \Google_Service_Gmail($client);
        return $service;
    }
    public function get_client()
    {
        $client = new \Google_Client();
        $client->setApplicationName('Online Exam Gmail Setup');
        $client->setScopes(\Google_Service_Gmail::MAIL_GOOGLE_COM);
        $client->setAuthConfig(get_option('credentials'));

        if (get_option('access_token')) {
            $client->setAccessToken(get_option('access_token'));
        }
        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            }
        }
        return $client;
    }
    public function createMessage($sender, $to, $subject, $messageText)
    {
        $message = new \Google_Service_Gmail_Message();

        $rawMessageString = "From: <{$sender}>\r\n";
        $rawMessageString .= "To: <{$to}>\r\n";
        $rawMessageString .= 'Subject: =?utf-8?B?' . base64_encode($subject) . "?=\r\n";
        $rawMessageString .= "MIME-Version: 1.0\r\n";
        $rawMessageString .= "Content-Type: text/html; charset=utf-8\r\n";
        $rawMessageString .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
        $rawMessageString .= "{$messageText}\r\n";

        $rawMessage = strtr(base64_encode($rawMessageString), array('+' => '-', '/' => '_'));
        $message->setRaw($rawMessage);
        return $message;
    }
    public function send_messege()
    {
        $authenticated_email = $this->service()->users->getProfile('me')->getEmailAddress();

        $sending_msg = $this->createMessage(
            $authenticated_email,
            sanitize_text_field($_POST['target_email']),
            "Test Message From " . get_option('blogname') . "",
            "Messege Sent Successfully"
        );
        try {
            $message = $this->service()->users_messages->send($authenticated_email, $sending_msg);
            $this->output_msg($message);
            return $message;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function output_msg($message)
    {
        $output_array = [
            'res' => 'success',
            'returned' => 'Message with ID: ' . $message->getId() . ' sent.'
        ];
        echo json_encode($output_array);
    }
}
new Send_Email;
