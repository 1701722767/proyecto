<?php

namespace Tests\Feature;

use App\Http\Controllers\MailController;
use Illuminate\Http\Request;
use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailMailable;

class SendEmailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_send_email()
    {
        Mail::fake();
        $temporal = new MailController();
        $response = $temporal->getEMail('santiago.1701721006@ucaldas.edu.co','This is a Subject','This is a Message');
        $this->assertEquals($response->error, false);
        Mail::assertSent(SendEmailMailable::class);

    }

    public function test_send_email_failed()
    {
        Mail::fake();
        $temporal = new MailController();
        $response = $temporal->getEMail('santiago.1701721006ucaldas.edu.co','This is a Subject','This is a Message');    
        $this->assertEquals($response->error, true);
        Mail::assertNotSent(SendEmailMailable::class);
    }
}
