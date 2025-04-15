<?php

namespace App\Services;

use Postmark\PostmarkClient;
use Exception;

class SendEmailService
{
    protected $postmarkClient;
    protected $fromEmail;

    public function __construct() {
        $this->postmarkClient = new PostmarkClient(config('services.postmark.token'));
        $this->fromEmail      = config('mail.from.address', 'info@linkatik.com');
    }
    private function sendEmail($toEmail, $subject, $htmlBody, $textBody)
    {
        // try {
            $tag           = null;
            $trackOpens    = true;
            $trackLinks    = 'None';
            $messageStream = 'outbound';

            $response = $this->postmarkClient->sendEmail(
                $this->fromEmail,
                $toEmail,
                $subject,
                $htmlBody,
                $textBody,
                $tag,
                $trackOpens,
                null,
                null,
                null,
                null,
                null,
                $trackLinks,
                null,
                $messageStream
            );
            dd($response);

        //     return true;
        // } catch (Exception $e) {
        //     return $e->getMessage();
        // }
    }

    public function sendTestEmail() {
        $subject  = 'Test Email';
        $htmlBody = view('emails.email', [
            'name' => 'Test',
        ])->render();

        $textBody = "This is a test email";

        return $this->sendEmail('alielbialy44r@gmail.com', $subject, $htmlBody, $textBody);
    }

}
