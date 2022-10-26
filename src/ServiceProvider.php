<?php

namespace SpiritSaint\LaravelDTE;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-dte');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}