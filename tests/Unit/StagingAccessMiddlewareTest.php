<?php

namespace Tests\Unit;

use App\Http\Middleware\StagingAccess;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class StagingAccessMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_allows_requests_outside_staging(): void
    {
        $this->app->detectEnvironment(fn () => 'testing');
        $middleware = new StagingAccess;
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, fn () => response('ok', 200));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('ok', $response->getContent());
    }

    public function test_it_requires_credentials_in_staging(): void
    {
        $this->app->detectEnvironment(fn () => 'staging');
        config()->set('staging.user', 'staging-user');
        config()->set('staging.password', 'staging-pass');

        $middleware = new StagingAccess;
        $request = Request::create('/dashboard', 'GET');

        $response = $middleware->handle($request, fn () => response('ok', 200));

        $this->assertSame(401, $response->getStatusCode());
        $this->assertSame('Basic realm="Staging"', $response->headers->get('WWW-Authenticate'));
    }

    public function test_it_allows_valid_basic_auth_in_staging(): void
    {
        $this->app->detectEnvironment(fn () => 'staging');
        config()->set('staging.user', 'staging-user');
        config()->set('staging.password', 'staging-pass');

        $middleware = new StagingAccess;
        $request = Request::create('/dashboard', 'GET', [], [], [], [
            'PHP_AUTH_USER' => 'staging-user',
            'PHP_AUTH_PW' => 'staging-pass',
        ]);

        $response = $middleware->handle($request, fn () => new Response('ok', 200));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('ok', $response->getContent());
    }
}
