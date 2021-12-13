<?php

namespace App\Http\Controllers;

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
            $this->mailController->sendEmail($listen->email, $this->subject, $this->message);
        }
    }

}
