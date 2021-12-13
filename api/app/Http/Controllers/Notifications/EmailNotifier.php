<?php

namespace App\Http\Controllers;

use App\Exceptions\MailException;
use Throwable;

class EmailNotifier implements Notifier
{
    private $subject;
    private $message;
    private $mailController;

    public function __construct($subject, $message, MailController $mailController = null)
    {
        $this->subject        = $subject;
        $this->message        = $message;
        $this->mailController = ($mailController == null) ? new MailController() : $mailController;
    }

    public function notify(array $listeners)
    {
        foreach ($listeners as $listen) {
            try {
                $this->mailController->sendEmail($listen->email, $this->subject, $this->message);
            } catch (MailException $e) {
                LoggerController::write(LoggerController::ERROR, 'send email failed', [
                    "error"   => $e->getMessage(),
                    "email"   => $listen->email,
                    "subject" => $this->subject,
                ], LoggerController::TOSLACKANDFILE);
            } catch (Throwable $th) {
                LoggerController::write(LoggerController::CRITICAL, 'send email failed', [
                    "error"   => $th->getMessage(),
                    "email"   => $listen->email,
                    "subject" => $this->subject,
                ], LoggerController::TOSLACKANDFILE);
            }

        }
    }

}
