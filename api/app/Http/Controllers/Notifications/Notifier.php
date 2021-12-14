<?php
namespace App\Http\Controllers\Notifications;

interface Notifier{
  public function notify(array $listeners);
}
