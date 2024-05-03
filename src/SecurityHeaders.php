<?php

namespace DualityStudio\LaraSecurity;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Collection;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!is_callable([$response, 'header'])) {
            return $response;
        }

        if (config('lara-security.enabled')) {
            Headers::getHeaders()
                ->each(fn ($value, $header) => $response->header($header, $value));
        }

        return $response;
    }
}
