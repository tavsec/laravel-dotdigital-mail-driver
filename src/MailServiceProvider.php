<?php

use Tavsec\LaravelDotdigitalMailDriver\DotdigitalTransportServiceProvider;

class MailServiceProvider extends \Illuminate\Mail\MailServiceProvider
{
    public function register()
    {
        parent::register();
        $this->app->register(DotdigitalTransportServiceProvider::class);
    }
}
