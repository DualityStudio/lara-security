<?php

if (!function_exists('nonce')) {
    function nonce(string $directive): string
    {
        return app('lara-security-nonce')->generate($directive);
    }
}
