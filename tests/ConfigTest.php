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
        $this->json('post', "/{$prefix}/subscribe")->assertFound();
    }

    public function test_default_middleware()
    {
        $this
            ->json('post', '/subscribe')
            ->assertHeader(
                'X-RateLimit-Limit',
                config('newsletter.routes.rate_limiter.per_min') - 1,
            );
    }

    public function test_custom_middleware()
    {
        config([
            'newsletter.routes.middleware' => [],
        ]);
        $this->json('post', '/subscribe')->assertHeaderMissing('X-RateLimit-Limit');
    }

    public function test_disable_routes()
    {
        Newsletter::ignoreRoutes();
        $this->json('post', '/subscribe')->assertNotFound();
    }
    */

    public function test()
    {
        $this->assertTrue(true);
    }
}
