<?php

namespace MIBU\Newsletter;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MIBU\Newsletter\Console\PurgeSubscribersCommand;
use MIBU\Newsletter\Contracts\SubscribeResponse as SubscribeResponseContract;
use MIBU\Newsletter\Contracts\UnsubscribedResponse as UnsubscribedResponseContract;
use MIBU\Newsletter\Contracts\VerifiedResponse as VerifiedResponseContract;
use MIBU\Newsletter\Events\Subscribed;
use MIBU\Newsletter\Http\Responses\SubscribeResponse;
use MIBU\Newsletter\Http\Responses\UnsubscribedResponse;
use MIBU\Newsletter\Http\Responses\VerifiedResponse;
use MIBU\Newsletter\Listeners\SendSubscribedNotification;

class NewsletterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/newsletter.php', 'newsletter');

        $this->app->singleton(SubscribeResponseContract::class, SubscribeResponse::class);
        $this->app->singleton(VerifiedResponseContract::class, VerifiedResponse::class);
        $this->app->singleton(UnsubscribedResponseContract::class, UnsubscribedResponse::class);
    }

    public function boot(): void
    {
        $langPath = __DIR__.'/../lang';
        $this->loadTranslationsFrom($langPath, 'newsletter');

        $viewsPath = __DIR__.'/../resources/views';
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

            /*
            $this->publishes([
                $langPath => $this->app->langPath('vendor/newsletter'),
            ], 'newsletter-lang');

            $this->publishes([
                $viewsPath => resource_path('views/vendor/newsletter'),
            ], 'newsletter-views');
            */

            $this->publishes(
                [
                    __DIR__.'/../stubs/app/Actions' => app_path('Actions'),
                ],
                'newsletter-actions',
            );

            $this->commands([
                PurgeSubscribersCommand::class,
            ]);
        }

        if (config('newsletter.routes.register')) {
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

        Event::listen(Subscribed::class, SendSubscribedNotification::class);
    }
}
