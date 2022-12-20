<?php

namespace MIBU\Newsletter\Tests;

use Illuminate\Support\Facades\Route;
use MIBU\Newsletter\Models\Subscriber;
use MIBU\Newsletter\NewsletterServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testing');

        $app['config']->set('newsletter', require __DIR__.'/../config/newsletter.php');
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function defineRoutes($router)
    {
        Route::middleware([
            'throttle:' . config('newsletter.rate_limiter.name'),
        ])
            ->group(function () {
                require __DIR__.'/../stubs/routes/newsletter.php';
            });
    }

    protected function getPackageProviders($app): array
    {
        return [
            NewsletterServiceProvider::class,
        ];
    }

    protected function getSubscriber(string $email): Subscriber
    {
        return app(config('newsletter.model'))->where('email', $email)->first();
    }
}