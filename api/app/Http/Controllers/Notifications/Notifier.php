<?
namespace App\Http\Controllers;

use App\Models\User;

interface Notifier{
  public function notify(array $listeners);
}
