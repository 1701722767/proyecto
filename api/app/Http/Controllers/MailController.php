<?php

namespace App\Http\Controllers;


use App\Exceptions\MailException;
use App\Mail\SendEmailMailable;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Throwable;

class MailController extends Controller
{
    public function sendEmail($email, $subject, $message)
    {
        $request    = new Request(array('email' => $email, 'subject' => $subject, 'message' => $message));
        $validation = Validator::make($request->all(), [
            'subject' => 'required',
            'message' => 'required',
            'email'   => 'required|email',
        ]);

        if ($validation->fails()) {
            throw new MailException("{$validation->getMessageBag()->first()}");
        }
        $email                  = new SendEmailMailable();
        $emailTemporal          = new Email;
        $emailTemporal->email   = $request->email;
        $emailTemporal->message = $request->message;
        $emailTemporal->subject = $request->subject;
        $email->email           = $emailTemporal;

        try {
            Mail::to($emailTemporal->email)->send($email);
        } catch (Throwable $e) {
            $emailTemporal->save();
            throw $e;
        }
    }
}
