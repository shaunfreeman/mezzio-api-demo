<?php

declare(strict_types=1);

namespace Core\ValueObject;

use Exception;
use InvalidArgumentException;
use JsonSerializable;

final class Uuid implements JsonSerializable
{
    public const UUID_REGEX = '/^([a-f0-9]{8})-([a-f0-9]{4})-([a-f0-9]{4})-([a-f0-9]{4})-([a-f0-9]{12})$/';

    private string $id;

    public static function fromString(string $uuid): Uuid
    {
        return new static($uuid);
    }

    /**
     * @return Uuid
     * @throws Exception
     */
    public static function generate(): Uuid
    {
        $hex        = bin2hex(random_bytes(16));
        $timeHi     = hexdec(substr($hex, 12, 4)) & 0x0fff & ~(0xf000) | 4 << 12;
        $clockSeqHi = hexdec(substr($hex, 16, 2)) & 0x3f & ~(0xc0) | 0x80;

        $fields = [
            'time_low'                  => substr($hex, 0, 8),
            'time_mid'                  => substr($hex, 8, 4),
            'time_hi_and_version'       => dechex($timeHi),
            'clock_seq_hi_and_reserved' => dechex($clockSeqHi),
            'clock_seq_low'             => substr($hex, 18, 2),
            'node'                      => substr($hex, 20, 12),
        ];

        $uuid = vsprintf('%08s-%04s-%04s-%02s%02s-%012s', $fields);

        return new static($uuid);
    }

    private function __construct(string $uuid)
    {
        $this->id = $this->validateAndNormalise($uuid);
    }

    private function validateAndNormalise(string $uuid): string
    {
        if (preg_match(self::UUID_REGEX, $uuid, $matches) !== 1) {
            throw new InvalidArgumentException(sprintf('Uuid %s is not a valid uuid.', $uuid));
        }

        array_shift($matches);

        return vsprintf('%08s-%04s-%04s-%04s-%012s', $matches);
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function jsonSerialize(): string
    {
        return $this->id;
    }
}
