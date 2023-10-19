<?php

namespace MIBU\Newsletter\Tests;

use MIBU\Newsletter\Factories\SubscriberFactory;
use MIBU\Newsletter\Models\Subscriber;
use MIBU\Newsletter\NewsletterServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function defineEnvironment($app)
    {
        $app['config']->set([
            'database.default' => 'testing',
            'mail.default' => 'array',
        ]);

        $app['config']->set('newsletter', require __DIR__.'/../config/newsletter.php');
        $app['config']->set('newsletter.routes.prefix', '');
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function getPackageProviders($app): array
    {
        return [
            NewsletterServiceProvider::class,
        ];
    }

    protected function createSubscriber(array $attributes = []): Subscriber
    {
        return (new SubscriberFactory())->create([
            ...$attributes,
        ]);
    }

    protected function getSubscriber(string $email): Subscriber
    {
        return app(config('newsletter.model'))->email($email)->first();
    }
}
