<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

// this is a controller for save logs and send to slack channel
class LoggerController extends Controller
{
    const INFO           = 'info';
    const WARNING        = 'warning';
    const ERROR          = 'error';
    const CRITICAL       = 'critical';
    const TOSLACKANDFILE = ['single', 'slack'];
    const TOSLACK        = ['slack'];
    const TOFILE         = ['single'];
    const MAXLENGTH      = 100;
    const PARSERS        = [
        'single' => true,
        'slack'  => true,
    ];

    public static function write($level, $event, $data, $channels)
    {
        try {
            foreach ($channels as $channel) {
                if (!array_key_exists($channel, LoggerController::PARSERS)) {
                    continue;
                }

                $message = LoggerController::getMessage($channel, $event, $data);

                LoggerController::sendToChannel($level, $channel, $message);
            }
        } catch (\Throwable $th) {
            $message = sprintf("Write log fail: %s", $th->getMessage());

            LoggerController::sendToChannel(
                LoggerController::CRITICAL,
                'slack',
                $message
            );
            LoggerController::sendToChannel(
                LoggerController::CRITICAL,
                'single',
                $message
            );
        }
    }

    private static function getMessage($channel, $event, $data)
    {
        if ($channel == 'slack') {
            return LoggerController::slackParser($event, $data);
        }

        return LoggerController::singleParser($event, $data);
    }

    private static function slackParser($event, $data)
    {
        $message     = $event . "\n\r";
        $dataToSlack = LoggerController::getUserInformation();

        if (array_key_exists("error", $data)) {
            $dataToSlack["error"] = $data["error"];
        }

        $message .= LoggerController::arrayToSlack($dataToSlack);

        return $message;
    }

    private static function singleParser($event, $data)
    {
        $message = $event . "\n\r";
        $data    = array_merge(LoggerController::getUserInformation(), $data);
        $message .= LoggerController::arrayToFile($data);

        return $message;
    }

    private static function sendToChannel($level, $channel, $message)
    {
        try {
            if ($channel == 'slack') {
                $channel .= sprintf("-%s", $level);
            }

            switch ($level) {
                case LoggerController::INFO:
                    Log::channel($channel)->info($message);
                    break;
                case LoggerController::WARNING:
                    Log::channel($channel)->warning($message);
                    break;
                case LoggerController::ERROR:
                    Log::channel($channel)->error($message);
                    break;
                case LoggerController::CRITICAL:
                    Log::channel($channel)->critical($message);
                    break;

                default:
                    $message = sprintf("Level wrong for: %s", $message);

                    Log::channel('slack-error')->error($message);
                    Log::channel('single')->error($message);
                    break;
            }
        } catch (\Throwable $th) {
            Log::channel('slack-critical')->critical(sprintf("Level wrong for: %s", $th->getMessage()));
        }
    }

    private static function getUserInformation()
    {
        // TODO: when autentication is done change this for get real information

        return [
            "buyer"       => "the buyer name",
            "buyer email" => "the buyer email",
            "user email"  => "the user email",
            "user role"   => "the user role",
        ];
    }

    private static function arrayToSlack($array)
    {
        $message = "";
        foreach ($array as $key => $value) {
            $value = substr($value, 0, LoggerController::MAXLENGTH);
            $message .= sprintf("%s: %s\n", $key, $value);
        }

        return $message;
    }

    private static function arrayToFile($array)
    {
        return json_encode($array);
    }

}
