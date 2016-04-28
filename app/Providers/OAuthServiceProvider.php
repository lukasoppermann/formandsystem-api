<?php

namespace App\Providers;

use Dingo\Api\Auth\Auth;
use Dingo\Api\Auth\Provider\OAuth2;
use Illuminate\Support\ServiceProvider;

class OAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app[Auth::class]->extend('oauth', function ($app) {

            $provider = new OAuth2($app['oauth2-server.authorizer']->getChecker());
            // $provider->setUserResolver(function ($id) {
            //     // Logic to return a user by their ID.
            // });

            $provider->setClientResolver(function ($id) {
                $client = new \App\Api\V1\Models\Client;
                \LOG::debug('$id');
                \LOG::debug(json_encode($client->find($id)));
                return $client->find($id);
            });

            return $provider;
        });
    }

    public function register()
    {
        $this->app->register(\LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider::class);
        $this->app->register(\LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider::class);
    }
}
