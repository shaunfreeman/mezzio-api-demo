<?php

declare(strict_types=1);

namespace Cms\Test\App\Handler;

use Prophecy\PhpUnit\ProphecyTrait;
use Cms\App\Handler\PingHandler;
use Laminas\Diactoros\Response\JsonResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class PingHandlerTest extends TestCase
{
    use ProphecyTrait;

    public function testResponse()
    {
        /** @var ServerRequestInterface $requestInterface */
        $requestInterface = $this->prophesize(ServerRequestInterface::class)->reveal();
        $pingHandler = new PingHandler();
        $response = $pingHandler->handle(
            $requestInterface
        );

        $json = json_decode((string) $response->getBody());

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertTrue(isset($json->ack));
    }
}
