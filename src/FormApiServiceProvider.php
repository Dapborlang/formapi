<?php
namespace Rdmarwein\FormApi;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
class FormApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', 'form_api');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $router = $this->app->make(Router::class);
    }

    public function register()
    {

    }
}