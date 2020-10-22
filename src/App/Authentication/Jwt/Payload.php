<?php

declare(strict_types=1);

namespace Cms\App\Authentication\Jwt;

use Exception;
use JsonSerializable;
use Cms\App\Authentication\Identity;
use Cms\App\ValueObject\Uuid;

final class Payload implements JsonSerializable
{
    private Uuid $tokenId;
    private string $serverName;
    private int $issuedAt;
    private int $notBefore;
    private int $expire;
    private Identity $identity;

    public static function fromArray(array $array): Payload
    {
        $payload                = new static();
        $payload->tokenId       = Uuid::fromString($array['jti']);
        $payload->serverName    = $array['iss'];
        $payload->issuedAt      = $array['iat'];
        $payload->notBefore     = $array['nbf'];
        $payload->expire        = $array['exp'];
        $payload->identity      = Identity::fromArray((array) $array['identity']);

        return $payload;
    }

    public static function fromIdentity(Identity $identity): Payload
    {
        $payload = new static();
        $payload->identity = $identity;
        return $payload;
    }

    /**
     * Payload constructor.
     * @throws Exception
     */
    private function __construct()
    {
        $this->tokenId      = Uuid::generate();
        $this->serverName   = $_SERVER['SERVER_NAME'];
        $this->issuedAt     = time();
        $this->notBefore    = $this->issuedAt;
        $this->expire       = $this->issuedAt + (60*20);
    }

    public function getTokenId(): Uuid
    {
        return $this->tokenId;
    }

    public function getServerName(): string
    {
        return $this->serverName;
    }

    public function getIssuedAt(): int
    {
        return $this->issuedAt;
    }

    public function getNotBefore(): int
    {
        return $this->notBefore;
    }

    public function getExpire(): int
    {
        return $this->expire;
    }

    public function getIdentity(): Identity
    {
        return $this->identity;
    }

    public function getArrayCopy(): array
    {
        return json_decode(json_encode($this), true);
    }

    public function jsonSerialize()
    {
        return [
            'iat'       => $this->getIssuedAt(),    // Issued at: time when the token was generated
            'jti'       => $this->getTokenId(),     // Json Token Id: an unique identifier for the token
            'iss'       => $this->getServerName(),  // Issuer
            'nbf'       => $this->getNotBefore(),   // Not before
            'exp'       => $this->getExpire(),      // Expire
            'identity'  => $this->getIdentity(),
        ];
    }
}
