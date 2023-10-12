<?php

namespace MIBU\Newsletter;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MIBU\Newsletter\Console\PurgeSubscribersCommand;

class NewsletterServiceProvider extends ServiceProvider
{
    private const VERSION = '0.0.1';

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/newsletter.php', 'newsletter');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/newsletter.php' => config_path('newsletter.php'),
            ], 'newsletter-config');

            $this->publishes(
                [
                    __DIR__.'/../database/migrations' => database_path('migrations'),
                ],
                'newsletter-migrations',
            );

            $this->publishes(
                [
                    __DIR__ . '/../stubs/routes/newsletter.php' => base_path('routes/newsletter.php'),
                ],
                'newsletter-routes',
            );

            $this->publishes(
                [
                    __DIR__.'/../stubs/app/Actions' => app_path('Actions'),
                    __DIR__.'/../stubs/app/Providers' => app_path('Providers'),
                ],
                'newsletter-src',
            );

            // https://github.com/laravel/framework/blob/9.x/CHANGELOG.md#v9210---2022-07-19
            if (method_exists(AboutCommand::class, 'add')) {
                AboutCommand::add('Newsletter', fn () => [
                    // 'Foo' => '<fg=yellow;options=bold>ENABLED</>',
                    'Version' => self::VERSION,
                ]);
            }

            $this->commands([
                PurgeSubscribersCommand::class,
            ]);
        }

        if (Newsletter::$registersRoutes) {
            RateLimiter::for(config('newsletter.routes.rate_limiter.name'), function (Request $request) {
                return Limit::perMinute(config('newsletter.routes.rate_limiter.per_min'))->by($request->ip());
            });

            Route::group([
                'middleware' => config('newsletter.routes.middleware'),
                'name' => config('newsletter.routes.name_prefix'),
                'prefix' => config('newsletter.routes.prefix'),
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
            });
        }
    }
}
