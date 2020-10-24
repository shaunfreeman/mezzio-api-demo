<?php

declare(strict_types=1);

namespace Cms\Test\App\ValueObject;

use Exception;
use InvalidArgumentException;
use Cms\App\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{
    private string $uuidRegex   = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/';

    /**
     * @throws Exception
     */
    public function testCanCreateNewUuid()
    {
        $uuid = Uuid::generate();
        $this->assertTrue((bool) preg_match($this->uuidRegex, (string) $uuid));
    }

    public function testCanCreateUuidFromString()
    {
        $uuid = Uuid::fromString('5fd47c55-b2d3-48bb-9888-f9679f18f290');
        $this->assertSame('5fd47c55-b2d3-48bb-9888-f9679f18f290', (string) $uuid);
    }

    public function testMalFormedUuidThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);
        Uuid::fromString('5fd4-7c55b2d34-8bb9888f-9679f18-f290');
    }

    public function testCanCovertToJsonString()
    {
        $uuid = Uuid::fromString('5fd47c55-b2d3-48bb-9888-f9679f18f290');
        $this->assertSame('"5fd47c55-b2d3-48bb-9888-f9679f18f290"', json_encode($uuid));
    }
}
