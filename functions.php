<?php
require __DIR__ . "/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::create(__DIR__);

$dotenv->load();


/**
 * Sendgrid Send email function
 * @param $subject
 * @param $body
 * @param $to
 * @return int
 * @throws \SendGrid\Mail\TypeException
 */
function sendEmail($subject, $body, $to)
{
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom(getenv('FROM_EMAIL'), getenv('FROM_NAME'));
    $email->setSubject($subject);
    $email->addTo($to);
    $email->addContent("text/plain", $body);
    $email->addContent(
        "text/html",
        $body
    );
    $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
    try {
        $response = $sendgrid->send($email);
        return $response->statusCode();
    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . "\n";
    }
}

/**
 * Takes and process user's input, sends email.
 * @param $message
 * @return array|string[]
 * @throws \SendGrid\Mail\TypeException
 */
function processAndSendEmail($message)
{
    //TO:sam@mail.io+SUBJ:Hello+MSG: Im sending this email using SMS
    //we split the first input command
    $RawCommand = explode("+", $message);
    if (count($RawCommand) === 3) {
        //extract the useful data by spliting again using :
        $To = explode(":", $RawCommand[0])[1];
        $Subj = explode(":", $RawCommand[1])[1];
        $Msg = explode(":", $RawCommand[2])[1];
        //send email
        $sendEmail = sendEmail($Subj, $Msg, $To);
        //if email send success
        if ($sendEmail === 202) {
            $resp = ['status' => 'success', 'data' => [
                'to' => $To,
                'Subject' => $Subj,
                'Message' => $Msg
            ]];
        } else {
            //if email send fails
            $resp = ['status' => 'failed', 'message' => 'Message could\'nt be sent please try again'];
        }
    } else {
        //if user syntax is incorrect
        $resp = ['status' => 'failed', 'message' => 'Message could\'nt be sent please check your syntax'];
    }

    return $resp;
}