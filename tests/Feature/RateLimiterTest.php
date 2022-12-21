<?php

namespace MIBU\Newsletter\Tests\Feature;

use Illuminate\Support\Facades\Route;
use MIBU\Newsletter\Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class RateLimiterTest extends TestCase
{
    public function test_rate_limiter()
    {
        $perMin = 1;
        config([
            'newsletter.rate_limiter.per_min' => $perMin,
        ]);

        $rateLimiter = config('newsletter.rate_limiter');
        Route::get($uri = '/test-rate-limiter', fn () => [])->middleware("throttle:{$rateLimiter['name']}");

        $requests = $rateLimiter['per_min'] + 1; // To exceed the rate limit.
        for ($i = 1; $i <= $requests; ++$i) {
            $response = $this
                ->json('get', $uri)
                ->assertHeader('x-ratelimit-remaining', max(0, $rateLimiter['per_min'] - $i));

            if ($i <= $rateLimiter['per_min']) {
                $response->assertSuccessful();
            } else {
                $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
            }
        }

        $this->withServerVariables([
            'REMOTE_ADDR' => '1.1.1.1', // From a diff. IP and therefore not throttled.
        ])
            ->json('get', $uri)
            ->assertSuccessful()
            ->assertHeader('x-ratelimit-remaining', $rateLimiter['per_min'] - 1);
    }
}
