<?php

namespace DualityStudio\LaraSecurity;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

//        dd(Headers::getHeaders());

        Headers::getHeaders()
            ->each(fn ($value, $header) => $response->header($header, $value));

        return $response;
    }
}
