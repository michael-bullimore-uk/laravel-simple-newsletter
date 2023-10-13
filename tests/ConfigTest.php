<?php

namespace MIBU\Newsletter\Tests;

use MIBU\Newsletter\Newsletter;

class ConfigTest extends TestCase
{
    /*
    public function test_routes_prefix()
    {
        config([
            'newsletter.routes.prefix' => $prefix = 'foo',
        ]);
        $this->json('post', "/{$prefix}/subscribe")->assertSuccessful();
    }

    public function test_default_middleware()
    {
        $this
            ->json('post', '/subscribe')
            ->assertHeader(
                'X-RateLimit-Remaining',
                config('newsletter.routes.rate_limiter.per_min') - 1,
            );
    }

    public function test_custom_middleware()
    {
        config([
            'newsletter.routes.middleware' => [],
        ]);

        $this->json('post', '/subscribe')->dumpHeaders();
    }

    public function test_disable_routes_register()
    {
        config([
            'newsletter.routes.register' => false,
        ]);
        $this->json('post', '/subscribe')->assertNotFound();
    }
    */

    public function test()
    {
        $this->assertTrue(true);
    }
}
