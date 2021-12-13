<?php

namespace App\Http\Controllers\Notifications;

use App\Models\User;

class NotificationManager
{
    private $listeners;
    private $notifier;

    public function __construct(Notifier $notifier, User...$listeners)
    {
        $this->listeners = $listeners;
        $this->notifier  = $notifier;
    }

    public function notify()
    {
        $this->notifier->notify($this->listeners);
    }

}
