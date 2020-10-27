<?php

declare(strict_types=1);

namespace App\Authentication\Jwt;

use Exception;
use Firebase\JWT\JWT as FirebaseJWT;
use App\Authentication\Identity;

final class Jwt
{
    private JwtConfig $config;

    public function __construct(JwtConfig $config)
    {
        $this->config = $config;
    }

    public function createToken(Identity $identity): string
    {
        $payload = Payload::fromIdentity($identity);

        return FirebaseJWT::encode(
            $payload->getArrayCopy(),       // Data to be encoded in the JWT
            $this->config->getSecret(),     // The signing key
            $this->config->getAlgorithm()   // Algorithm used to sign the token
        );
    }

    /**
     * @param string $token
     * @return array
     * @throws Exception
     */
    public function decodeToken(string $token): array
    {
        try {
            $decoded = FirebaseJWT::decode(
                $token,
                $this->config->getSecret(),
                (array) $this->config->getAlgorithm()
            );
            return (array) $decoded;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function getConfig(): JwtConfig
    {
        return $this->config;
    }
}
