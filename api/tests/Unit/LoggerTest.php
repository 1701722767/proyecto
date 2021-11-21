<?php

namespace Tests\Feature;

use App\Http\Controllers\LoggerController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tests\TestCase;
use TiMacDonald\Log\LogFake;

class LoggerTest extends TestCase
{
    /**
     * A basic test for log
     *
     * @return void
     */
    public function test_logs()
    {
        Log::swap(new LogFake);

        // log info
        LoggerController::write('info', 'Test of aplication', ["data" => "hola"], LoggerController::TOSLACK);
        Log::channel('slack-info')->assertLogged('info', function ($message, $context) {
            if (!Str::contains($message, 'Test of aplication')) {
                return false;
            }

            return !Str::contains($message, 'error');
        });

        // log warning
        LoggerController::write('warning', 'Test of aplication', ["data" => "hola"], LoggerController::TOSLACK);
        Log::channel('slack-warning')->assertLogged('warning', function ($message, $context) {
            if (!Str::contains($message, 'Test of aplication')) {
                return false;
            }

            return !Str::contains($message, 'error');
        });

        // log error
        LoggerController::write('error', 'Test of aplication', ["data" => "hola", "error" => "un error"], LoggerController::TOSLACK);
        Log::channel('slack-error')->assertLogged('error', function ($message, $context) {
            if (!Str::contains($message, 'Test of aplication')) {
                return false;
            }

            return Str::contains($message, 'error');
        });

        // log critical
        LoggerController::write('critical', 'Test of aplication', ["data" => "hola", "error" => "un error"], LoggerController::TOSLACK);
        Log::channel('slack-critical')->assertLogged('critical', function ($message, $context) {
            if (!Str::contains($message, 'Test of aplication')) {
                return false;
            }

            return Str::contains($message, 'error: un error');
        });
    }
}
