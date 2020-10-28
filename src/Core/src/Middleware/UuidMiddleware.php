<?php

declare(strict_types=1);

namespace Core\Middleware;

use Exception;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Core\ValueObject\Uuid;

final class UuidMiddleware implements MiddlewareInterface
{
    private ProblemDetailsResponseFactory $problemDetailsFactory;

    public function __construct(ProblemDetailsResponseFactory $problemDetailsFactory)
    {
        $this->problemDetailsFactory = $problemDetailsFactory;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $uuid = Uuid::fromString(
                $request->getAttribute('uuid')
            );
        } catch (Exception $exception) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                400,
                $exception->getMessage(),
                'Invalid UUID ',
                '',
                []
            );
        }

        $request = $request->withAttribute(Uuid::class, $uuid);

        return $handler->handle($request);
    }
}
