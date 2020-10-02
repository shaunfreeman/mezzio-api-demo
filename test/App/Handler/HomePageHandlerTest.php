<?php

declare(strict_types=1);

namespace Cms\Test\App\Handler;

use Prophecy\PhpUnit\ProphecyTrait;
use Cms\App\Handler\HomePageHandler;

use Laminas\Diactoros\Response\JsonResponse;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class HomePageHandlerTest extends TestCase
{
    use ProphecyTrait;

    public function testReturnsJsonResponseWhenNoTemplateRendererProvided()
    {
        $homePage = new HomePageHandler();
        $response = $homePage->handle(
            $this->prophesize(ServerRequestInterface::class)->reveal()
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
