<?php

namespace App\Models;

class Response
{

    public $error;
    public $message;
    public $data;
    public function __construct()
    {

    }

    public function toJSON()
    {
        return [
            "error"   => $this->error,
            "message" => $this->message,
            "data"    => $this->data,
        ];
    }

}
