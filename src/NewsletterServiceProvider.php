<?php

namespace MIBU\Newsletter;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MIBU\Newsletter\Console\PurgeSubscribersCommand;
use MIBU\Newsletter\Events\Subscribed;
use MIBU\Newsletter\Listeners\Foo;
use MIBU\Newsletter\Models\Subscriber;

class NewsletterServiceProvider extends ServiceProvider
{
    private const VERSION = '0.0.1';

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/newsletter.php', 'newsletter');
    }

    public function boot(): void
    {
        $viewsPath = __DIR__.'/../stubs/resources/views';
        $this->loadViewsFrom($viewsPath, 'newsletter');

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
                    __DIR__.'/../stubs/app/Actions' => app_path('Actions'),
                ],
                'newsletter-actions',
            );

            $this->publishes([
                $viewsPath => resource_path('views/vendor/newsletter'),
            ], 'newsletter-views');

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
                'as' => config('newsletter.routes.as'),
                'middleware' => config('newsletter.routes.middleware'),
                'prefix' => config('newsletter.routes.prefix'),
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
            });
        }

        Event::listen(Subscribed::class, Foo::class);
    }
}
