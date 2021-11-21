<?php

namespace App\Http\Controllers;

use App\Mail\SendEmailMailable;
use App\Models\Email;
use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailController extends Controller
{
    public function getEMail($email, $subject, $message)
    {

        $Response = new Response();

        try {
            $request    = new Request(array('email' => $email, 'subject' => $subject, 'message' => $message));
            $validation = Validator::make($request->all(), [
                'subject' => 'required',
                'message' => 'required',
                'email'   => 'required|email',
            ]);

            if ($validation->fails()) {
                $Response->error   = true;
                $Response->message = "Los datos ingresados son incorrectos";
                $Response->data    = $validation->errors();
                return $Response;
            }
            $email                  = new SendEmailMailable();
            $emailTemporal          = new Email;
            $emailTemporal->email   = $request->email;
            $emailTemporal->message = $request->message;
            $emailTemporal->subject = $request->subject;
            $email->email           = $emailTemporal;

            try {
                Mail::to($emailTemporal->email)->send($email);
            } catch (Exception $e) {
                $Response->error   = true;
                $Response->message = "El correo electrónico no pudo ser enviado";
                $Response->data    = $e->getMessage();
                $emailTemporal->save();
                return $Response;
            }

            $Response->error   = false;
            $Response->message = "El correo electrónico se envió satisfactoriamente";
            $Response->data    = $email->email;
            return $Response;

        } catch (Exception $e) {
            $Response->error   = true;
            $Response->message = "El correo electrónico no pudo ser enviado";
            $Response->data    = $e->getMessage();
            return $Response;
        }

    }
}
