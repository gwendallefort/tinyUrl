<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StagingAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->environment('staging')) {
            return $next($request);
        }

        $expectedUser = (string) config('staging.user');
        $expectedPass = (string) config('staging.password');

        // Fail closed in staging if credentials are not configured.
        if ($expectedUser === '' || $expectedPass === '') {
            return response('Staging credentials are not configured.', 503);
        }

        $givenUser = (string) $request->getUser();
        $givenPass = (string) $request->getPassword();

        $ok = hash_equals($expectedUser, $givenUser)
            && hash_equals($expectedPass, $givenPass);

        if (! $ok || $request->has('logout-staging')) {
            return response('Authentication required', 401, [
                'WWW-Authenticate' => 'Basic realm="Staging"',
            ]);
        }

        return $next($request);
    }
}
