<?php

namespace DualityStudio\LaraSecurity;

use Illuminate\Support\Str;
use ReflectionClass;

class Directives
{
    const SOURCE_VITE_ASSET = 'vite_asset';
    const SOURCE_SELF = '\'self\'';
    const SOURCE_NONE = '\'none\'';
    const SOURCE_UNSAFE_INLINE = '\'unsafe-inline\'';
    const SOURCE_UNSAFE_EVAL = '\'unsafe-eval\'';
    const SOURCE_WASM_UNSAFE_EVAL = '\'wasm-unsafe-eval\'';
    const SOURCE_STRICT_DYNAMIC = '\'strict-dynamic\'';
    const SOURCE_UNSAFE_HASHES = '\'unsafe-hashes\'';
    const SOURCE_DATA = 'data:';
    const SOURCE_BLOB = 'blob:';
    const SOURCE_FILESYSTEM = 'filesystem:';
    const SOURCE_HTTP = 'http:';
    const SOURCE_HTTPS = 'https:';
    const SOURCE_MEDIASTREAM = 'mediastream:';
    const SOURCE_REPORT_SAMPLE = 'report-sample';
    const SOURCE_INLINE_SPECULATION_RULES = 'inline-speculation-rules';

    const BASE = 'base-uri';
    const CHILD = 'child-src';
    const CONNECT = 'connect-src';
    const DEFAULT = 'default-src';
    const FONT = 'font-src';
    const FORM_ACTION = 'form-action';
    const FRAME = 'frame-src';
    const FRAME_ANCESTORS = 'frame-ancestors';
    const IMG = 'img-src';
    const MANIFEST = 'manifest-src';
    const MEDIA = 'media-src';
    const OBJECT = 'object-src';
    const REPORT_TO = 'report-to';
    const REQUIRE_TRUSTED_TYPES_FOR = 'require-trusted-types-for';
    const SANDBOX = 'sandbox';
    const SCRIPT = 'script-src';
    const SCRIPT_ATTR = 'script-src-attr';
    const SCRIPT_ELEM = 'script-src-elem';
    const STYLE = 'style-src';
    const STYLE_ATTR = 'style-src-attr';
    const STYLE_ELEM = 'style-src-elem';
    const TRUSTED_TYPES = 'trusted-types';
    const UPGRADE_INSECURE_REQUESTS = 'upgrade-insecure-requests';
    const WORKER = 'worker-src';

    public static function isValid(string $directive): bool
    {
        return in_array($directive, self::getDirectives());
    }

    public static function expectsValue(string $directive): bool
    {
        return $directive !== self::UPGRADE_INSECURE_REQUESTS;
    }

    public static function canHaveNonce(string $directive): bool
    {
        return $directive === self::SCRIPT || $directive === self::STYLE;
    }

    public static function getDirectives(): array
    {
        return collect((new ReflectionClass(self::class))->getConstants())
            ->filter(fn ($directive, $constant) => !Str::startsWith($constant, 'SOURCE_'))
            ->values()
            ->toArray();
    }
}
