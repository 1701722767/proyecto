<?php

namespace App\Http\Controllers;

use App\Models\Response;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $response = new Response();
        try {
            $credentials = $request->only(['username', 'password']);
            $validation  = Validator::make($credentials, [
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validation->fails()) {
                $response->error   = true;
                $response->message = $validation->getMessageBag()->first();
                $response->data    = null;

                return $response->toJSON();
            }

            if (!$token = Auth::attempt($credentials)) {
                $response->error   = true;
                $response->message = "Credenciales incorrectas";
                $response->data    = null;

                return response()->json($response->toJSON(), 401);
            }

            $response->error = false;
            $response->data  = ["token" => $token];

            return $response->toJSON();
        } catch (\Throwable $th) {
            LoggerController::write(LoggerController::CRITICAL, 'login failed', [
                "error"    => $th->getMessage(),
                "response" => "Error interno en el servidor",
            ], LoggerController::TOSLACK);

            $response->error   = true;
            $response->message = "Error interno en el servidor";

            return response()->json($response->toJSON(), 500);
        }

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $response = new Response();
        try {
            $response->error = false;
            $response->data  = Auth::user();

            return $response->toJSON();
        } catch (\Throwable $th) {
            LoggerController::write(LoggerController::CRITICAL, 'get user failed', [
                "error"    => $th->getMessage(),
                "response" => "Error interno en el servidor",
            ], LoggerController::TOSLACK);

            $response->error   = true;
            $response->message = "Error interno en el servidor";
            return response()->json($response->toJSON(), 500);

        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $response = new Response();
        try {
            Auth::logout();

            $response->error   = false;
            $response->message = "Cierre de sesión exitoso.";

            return $response->toJSON();
        } catch (\Throwable $th) {
            LoggerController::write(LoggerController::CRITICAL, 'logout failed', [
                "error"    => $th->getMessage(),
                "response" => "Error interno en el servidor",
            ], LoggerController::TOSLACK);

            $response->error   = true;
            $response->message = "Error interno en el servidor";
            return response()->json($response->toJSON(), 500);

        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $response = new Response();
        try {
            $response->error = false;
            $response->data  = ["token" => Auth::refresh()];

            return $response->toJSON();
        } catch (\Throwable $th) {
            LoggerController::write(LoggerController::CRITICAL, 'refresh token failed', [
                "error"    => $th->getMessage(),
                "response" => "Error interno en el servidor",
            ], LoggerController::TOSLACK);

            $response->error   = true;
            $response->message = "Error interno en el servidor";
            return response()->json($response->toJSON(), 500);

        }
    }
}
