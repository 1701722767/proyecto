<?php

namespace Tests\Feature;

namespace Tests\Feature;

use App\Http\Controllers\MailController;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailMailable;
use Illuminate\Support\Facades\Log;
use TiMacDonald\Log\LogFake;
class SendEmailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_send_email()
    {
        Log::swap(new LogFake);

        Mail::fake();
        $temporal = new MailController();
        $temporal->sendEmail('santiago.1701721006@ucaldas.edu.co', 'This is a Subject', 'This is a Message');

        Mail::assertSent(SendEmailMailable::class);
    }

    public function test_send_email_failed()
    {
        Log::swap(new LogFake);
        Mail::fake();
        $temporal = new MailController();
        $this->expectErrorMessage('El correo tiene que ser una dirección de correo válido');
        $temporal->sendEmail('santiago.1701721006ucaldas.edu.co', 'This is a Subject', 'This is a Message');
        Mail::assertNotSent(SendEmailMailable::class);
    }
}
