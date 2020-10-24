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
        /** @var ServerRequestInterface $requestInterface */
        $requestInterface = $this->prophesize(ServerRequestInterface::class)->reveal();
        $homePage = new HomePageHandler();
        $response = $homePage->handle(
            $requestInterface
        );

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
