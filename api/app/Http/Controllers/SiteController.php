<?php

namespace App\Http\Controllers;

use App\Exceptions\SiteException;
use App\Exceptions\UserException;
use App\Http\Controllers\Notifications\EmailNotifier;
use App\Http\Controllers\Notifications\NotificationManager;
use App\Models\Response;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller
{
    private $siteModel       = null;
    private $emailController = null;
    private $userController  = null;

    public function __construct($siteModel = null, $emailController = null, $userController = null)
    {
        $this->siteModel       = ($siteModel == null) ? new Site() : $siteModel;
        $this->emailController = ($emailController == null) ? new MailController() : $emailController;
        $this->userController  = ($userController == null) ? new UserController() : $userController;
    }

    public function createSiteAndUser(Request $request)
    {
        try {
            $response = new Response();

            $newUser = $this->userController->store($request->merge([
                'role_id' => 2,
            ]));

            $this->createSite($request->merge([
                'user_id' => $newUser->id,
            ]));

            $message = "Sitio creado correctamente, espere la aprobación para que su sitio se liste en nuestra plataforma";

            $notifier        = new EmailNotifier('Registro de sitio exitoso', $message);
            $notifiermanager = new NotificationManager($notifier, $newUser);
            $notifiermanager->notify();

            $superAdmins     = User::where('role_id', 1)->get();
            $notifier        = new EmailNotifier('Nuevo sitio creado', "Se ha registrado un nuevo sitio, debes ingresar a nuestro sistema para validarlo.");
            $notifiermanager = new NotificationManager($notifier, ...$superAdmins);
            $notifiermanager->notify();

            $response->error   = false;
            $response->message = $message;

            return $response->toJSON();

        } catch (UserException $e) {
            LoggerController::write(LoggerController::WARNING, 'create user failed in create site', [
                "error" => $e->getMessage(),
            ], LoggerController::TOSLACKANDFILE);
            $response->error   = true;
            $response->message = $e->getMessage();

            return $response->toJSON();

        } catch (SiteException $e) {
            LoggerController::write(LoggerController::WARNING, 'create site failed', [
                "error" => $e->getMessage(),
            ], LoggerController::TOSLACKANDFILE);
            $response->error   = true;
            $response->message = $e->getMessage();

            return $response->toJSON();

        } catch (\Throwable $th) {
            LoggerController::write(LoggerController::CRITICAL, 'create site failed', [
                "error" => $th->getMessage(),
            ], LoggerController::TOSLACKANDFILE);
            $response->error   = true;
            $response->message = 'Error interno en el servidor';

            return $response->toJSON();
        }
    }

    private function createSite(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name'        => 'required',
            'description' => 'required',
            'category_id' => 'exists:categories,id',
            'address'     => 'required',
        ]);

        if ($validation->fails()) {
            throw new SiteException("{$validation->getMessageBag()->first()} para el sitio.");
        }

        $this->siteModel->name        = $request->name;
        $this->siteModel->description = $request->description;
        $this->siteModel->category_id = $request->category_id;
        $this->siteModel->address     = $request->address;
        $this->siteModel->user_id     = $request->user_id;

        $this->siteModel->save();

        return $this->siteModel;
    }
}
