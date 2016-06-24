<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class TestingMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if($request->header('testing') === 'true'){
            // test api
            app()->make('config')->set('database.connections.testapi', [
                'driver'    => 'mysql',
                'host'      => '192.168.10.10',
                'database'  => 'formandsystem_api_test',
                'username'  => 'homestead',
                'password'  => 'secret',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ]);
            app()->make('config')->set('database.default', 'testapi');
        }

        return $response;
    }
}
