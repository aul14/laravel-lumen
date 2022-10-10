<?php

namespace App\Providers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            $auth = $request->header('Authorization');

            if ($auth == '') {
                abort(401, 'Unauthorized');
            }
            $auth = explode(' ', $auth);
            if ($auth[0] != 'Bearer') {
                abort(401, 'Unauthorized');
            }

            $token = $auth[1];

            try {
                $decoded = JWT::decode($token, new Key(env('JWT_SECRET', 'keyXSURU17'), 'HS256'));
                return User::findOrFail($decoded->uid);
            } catch (\Throwable $th) {
                abort(401, $th->getMessage());
            }
        });
    }
}
