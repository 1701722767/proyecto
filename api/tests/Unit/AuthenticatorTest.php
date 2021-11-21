<?php

namespace Tests\Feature;

use App\Http\Controllers\AuthController as ControllersAuthController;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use TiMacDonald\Log\LogFake;

class AuthenticatorTest extends TestCase
{
    /**
     * Validate test.
     *
     * @return void
     */
    public function test_missing_parameters()
    {
        Log::swap(new LogFake);

        $request    = new Request();
        $controller = new ControllersAuthController();

        $response = $controller->login($request);
        $this->assertEquals([
            "error"   => true,
            "message" => "El campo correo es requerido.",
            "data"    => null,
        ], $response);

        $request->merge(['email' => 'juacagiri@gmail.com']);
        $response = $controller->login($request);
        $this->assertEquals([
            "error"   => true,
            "message" => "El campo contraseña es requerido.",
            "data"    => null,
        ], $response);
    }

    /**
     * Generate token
     *
     * @return void
     */
    public function test_login()
    {
        Log::swap(new LogFake);
        Auth::swap(new AuthFake);

        $request    = new Request();
        $controller = new ControllersAuthController();

        $request->merge(['email' => 'juacagiri@gmail.com', 'password' => "password"]);

        $response = $controller->login($request);

        $this->assertEquals([
            "error"   => false,
            "message" => null,
            "data"    => [
                "token" => "token",
            ],
        ], $response);
    }

    /**
     * logout
     *
     * @return void
     */
    public function test_logout()
    {
        Log::swap(new LogFake);
        Auth::swap(new AuthFake);

        $controller = new ControllersAuthController();
        $response   = $controller->logout();

        $this->assertEquals([
            "error"   => false,
            "message" => "Cierre de sesión exitoso.",
            "data"    => null,
        ], $response);
    }

    /**
     * Refresh token
     *
     * @return void
     */
    public function test_refresh()
    {
        Log::swap(new LogFake);
        Auth::swap(new AuthFake);

        $request    = new Request();
        $controller = new ControllersAuthController();

        $response = $controller->refresh();

        $this->assertEquals([
            "error"   => false,
            "message" => null,
            "data"    => [
                "token" => "token",
            ],
        ], $response);
    }

    /**
     * Get user
     *
     * @return void
     */
    public function test_get_user()
    {
        Log::swap(new LogFake);
        Auth::swap(new AuthFake);

        $request    = new Request();
        $controller = new ControllersAuthController();

        $response = $controller->me();

        $userExpected            = new User();
        $userExpected->id        = "1";
        $userExpected->email     = "juacagiri@gmail.com";
        $userExpected->full_name = "peranito";

        $this->assertEquals([
            "error"   => false,
            "message" => null,
            "data"    => $userExpected,
        ], $response);
    }
}

class AuthFake
{
    public static function logout()
    {
        return true;
    }

    public static function attempt($credentials)
    {
        return 'token';
    }

    public static function refresh()
    {
        return 'token';
    }

    public static function user()
    {
        $user            = new User();
        $user->id        = "1";
        $user->email     = "juacagiri@gmail.com";
        $user->full_name = "peranito";

        return $user;
    }
}
