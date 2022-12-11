<?php

namespace MIBU\Newsletter;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
                __DIR__.'/../stubs/config/newsletter.php' => config_path('newsletter.php'),
            ], 'newsletter-config');

            $this->publishes(
                [
                    __DIR__.'/../database/migrations' => database_path('migrations'),
                ],
                'newsletter-migrations',
            );

            $this->publishes(
                [
                    __DIR__.'/../stubs/routes/newsletter.php' => base_path('routes/newsletter.php'),
                ],
                'newsletter-routes',
            );

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

        RateLimiter::for(config('newsletter.rate_limiter_name'), function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });

        AboutCommand::add('Newsletter', fn () => [
            'Version' => self::VERSION,
        ]);
    }
}
