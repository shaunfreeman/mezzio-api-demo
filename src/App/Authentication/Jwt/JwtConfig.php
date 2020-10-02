<?php

declare(strict_types=1);

namespace Cms\App\Authentication\Jwt;

final class JwtConfig
{
    private bool $secure;
    private array $relaxed;
    private string $algorithm;
    private string $secret;

    public static function formArray(array $config): JwtConfig
    {
        $jwtConfig = new static();

        $jwtConfig->secure      = $config['secure'];
        $jwtConfig->relaxed     = $config['relaxed'];
        $jwtConfig->algorithm   = $config['algorithm'];
        $jwtConfig->secret      = $config['secret'];

        return $jwtConfig;
    }

    private function __construct()
    {
    }

    public function isSecure(): bool
    {
        return $this->secure;
    }

    public function getRelaxed(): array
    {
        return $this->relaxed;
    }

    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }
}
