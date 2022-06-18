<?php

namespace Tavsec\LaravelDotdigitalMailDriver;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Mail\MailManager;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Tavsec\LaravelDotdigitalMailDriver\Transport\DotdigitalTransport;

class DotdigitalTransportServiceProvider extends ServiceProvider
{
    public function boot(){
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/dotdigital.php' => config_path('dotdigital.php'),
            ], 'config');

        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/dotdigital.php', "dotdigital");

        $this->app->afterResolving(MailManager::class, function (MailManager $mail_manager) {
            $mail_manager->extend("dotdigital", function ($config) {
                $config = $this->app['config']->get('dotdigital', []);
                $client = new HttpClient([
                    "verify" => false
                ]);

                return new DotdigitalTransport($client, $config['region'], $config['username'], $config['password']);
            });

        });
    }
}
