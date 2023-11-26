<?php

namespace DualityStudio\LaraSecurity;

use Illuminate\Support\{Arr, Str};

use DualityStudio\LaraSecurity\Exceptions\InvalidDirective;

class NonceGenerator
{
    public static array $nonces = [];

    public function generate(string $directive): string
    {
        $this->checkDirective($directive);

        $nonce = $this->generateNonce();

        $this->addNonce($directive, $nonce);

        return $nonce;
    }

    public function addNonce(string $directive, string $nonce): void
    {
        if (!array_key_exists($directive, static::$nonces)) {
            static::$nonces[$directive] = [];
        }

        static::$nonces[$directive][] = [
            'string' => $nonce,
            'nonce' => 'nonce-' . $nonce
        ];
    }

    public function getNonces(string $directive): array
    {
        $this->checkDirective($directive);

        return Arr::get(static::$nonces, $directive, []);
    }

    private function generateNonce(): string
    {
        return Str::random(32);
    }

    private function checkDirective(string $directive): void
    {
        if (!Directives::canHaveNonce($directive)) {
            throw new InvalidDirective($directive);
        }
    }
}
