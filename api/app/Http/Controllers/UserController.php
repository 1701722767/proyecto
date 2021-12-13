<?php

namespace App\Http\Controllers;


use App\Exceptions\MailException;
use App\Exceptions\UserException;
use App\Http\Controllers\Notifications\EmailNotifier;
use App\Http\Controllers\Notifications\NotificationManager;
use App\Models\Response;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class UserController extends Controller
{
    private $userModel       = null;
    private $emailController = null;

    public function __construct($userModel = null, $emailController = null)
    {
        $this->userModel       = ($userModel == null) ? new User() : $userModel;
        $this->emailController = ($emailController == null) ? new MailController() : $emailController;
    }


    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'full_name' => 'required',
            'role_id'   => 'required|exists:roles,id',
            'email'     => 'unique:users|regex:/^.+@.+$/i',
            'username'  => 'required',
        ]);

        if ($validation->fails()) {
            throw new UserException("{$validation->getMessageBag()->first()} para usuario.");
        }

        $randomPass                 = Str::random(8);
        $hash                       = Hash::make($randomPass);
        $this->userModel->full_name = $request->full_name;
        $this->userModel->email     = $request->email;
        $this->userModel->password  = $hash;
        $this->userModel->role_id   = $request->role_id;
        $this->userModel->username  = $request->username;

        


        $mensaje = 'Felicitaciones su usuario y contraseña son, Usuario ' . $this->userModel->username . ' y su contraseña: ' . $randomPass; 


        $notifier = new EmailNotifier('Registro Exitoso',$mensaje);
        $notifiermanager = new NotificationManager($notifier,$this->userModel);
        $this->userModel->save();
        $notifiermanager->notify();
        
        
        return $this->userModel;
    }

    public function create(Request $request)
    {
        try {
            $response = new Response();

            $newUser           = $this->store($request);
            $response->error   = false;
            $response->message = "Usuario creado correctamente.";
            $response->data    = $newUser;

            return $response->toJSON();
        } catch (UserException $e) {
            LoggerController::write(LoggerController::WARNING, 'create user failed', [
                "error" => $e->getMessage(),
            ], LoggerController::TOSLACKANDFILE);
            $response->error   = true;
            $response->message = $e->getMessage();

            return $response->toJSON();
        } catch (MailException $e) {
            LoggerController::write(LoggerController::ERROR, 'send email failed', [
                "error" => $e->getMessage(),
            ], LoggerController::TOSLACKANDFILE);
            $response->error   = true;
            $response->message = $e->getMessage();

            return $response->toJSON();
        } catch (Throwable $th) {
            LoggerController::write(LoggerController::CRITICAL, 'create user failed', [
                "error" => $th->getMessage(),
            ], LoggerController::TOSLACKANDFILE);
            $response->error   = true;
            $response->message = 'Error interno en el servidor';

            return $response->toJSON();
        }
    }
}
