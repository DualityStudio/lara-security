<?php

namespace DualityStudio\LaraSecurity;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Vite;
use ReflectionClass;

class Headers
{
    const CONTENT_SECURITY_POLICY = 'Content-Security-Policy';
    const PERMISSIONS_POLICY = 'Permissions-Policy';
    const REFERRER_POLICY = 'Referrer-Policy';
    const STRICT_TRANSPORT_SECURITY = 'Strict-Transport-Security';
    const X_FRAME_OPTIONS = 'X-Frame-Options';
    const X_CONTENT_TYPE_OPTIONS = 'X-Content-Type-Options';
    const X_XSS_PROTECTION = 'X-XSS-Protection';

    public static function getHeaders(): Collection
    {
        return collect((new ReflectionClass(static::class))->getConstants())
            ->filter(fn ($header) => !!config("lara-security.headers.$header"))
            ->mapWithKeys(function ($header) {
                $value = match ($header) {
                    static::CONTENT_SECURITY_POLICY => static::getContentSecurityPolicy(),
                    default => config("lara-security.headers.$header"),
                };

                return [
                    $header => $value,
                ];
            });
    }

    private static function getContentSecurityPolicy(): string
    {
        return collect(Directives::getDirectives())
            ->map(function ($directive) {
                if (!Directives::expectsValue($directive)) {
                    if (config('lara-security.headers.' . static::CONTENT_SECURITY_POLICY . '.' . $directive)) {
                        return $directive;
                    }
                }

                // TODO... make this less shit
                $values = collect(config('lara-security.headers.' . static::CONTENT_SECURITY_POLICY . '.' . $directive, []))
                    ->map(fn ($value) => match($value) {
                        Directives::SOURCE_ASSET_URL => asset('/'),
                        Directives::SOURCE_VITE_URL => self::getViteUrl($directive),
                        default => $value,
                    });

                if (Directives::canHaveNonce($directive)) {
                    $values = $values->merge(
                        collect(app('lara-security-nonce')->getNonces($directive))
                            ->pluck('nonce')
                            ->map(fn ($nonce) => '\'' . $nonce . '\'')
                    );
                }

                if ($values->isEmpty()) {
                    return null;
                }

                return $directive . ' ' . $values->implode(' ');
            })
            ->filter()
            ->implode('; ');
    }

    protected static function getViteUrl(string $directive): string
    {
        if (Vite::isRunningHot()) {
            $path = rtrim(file_get_contents(Vite::hotFile()));

            if ($directive === Directives::CONNECT) {
                $path = str_replace(['http://', 'https://'], ['ws://', 'wss://'], $path);
            }

            return $path;
        }

        return app('url')->asset('');
    }
}
