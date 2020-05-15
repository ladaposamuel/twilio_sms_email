<?php
require __DIR__ . "/vendor/autoload.php";
require_once('functions.php');

use Twilio\Rest\Client;

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_REQUEST['Body'] ?? '';
    $from = $_REQUEST['From'] ?? '';
    if ($message !== '') {
        $account_sid = getenv("TWILIO_ACCOUNT_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_PHONE_NUMBER");
        $client = new Client($account_sid, $auth_token);
        $response = processAndSendEmail($message);
        $text = '';
        if ($response['status'] === 'success') {
            $text = 'Message was sent to ' . $response['data']['to'] . ' Successfully';
        } else {
            $text = $response['message'];
        }

        $client->messages->create(
            $from,
            array(
                'from' => $twilio_number,
                'body' => $text
            )
        );
    }
}