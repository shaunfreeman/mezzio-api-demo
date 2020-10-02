<?php

declare(strict_types=1);

namespace Cms\App\Middleware;

use Closure;
use Exception;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cms\App\Stdlib\ProblemDetailsTrait;
use Cms\App\ValueObject\Uuid;

final class UuidMiddleware implements MiddlewareInterface
{
    use ProblemDetailsTrait;

    private Closure $responseFactory;

    private ProblemDetailsResponseFactory $problemDetailsFactory;

    public function __construct(
        callable $responseFactory,
        ProblemDetailsResponseFactory $problemDetailsFactory
    ) {
        // Factories is wrapped in a closure in order to enforce return type safety.
        $this->responseFactory = function () use ($responseFactory) : ResponseInterface {
            return $responseFactory();
        };
        $this->problemDetailsFactory = $problemDetailsFactory;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        try {
            $uuid = Uuid::fromString(
                $request->getAttribute('uuid')
            );
        } catch (Exception $exception) {
            $response   = ($this->responseFactory)();
            $response   = $response->withStatus(400);
            return $this->processError($request, $response, [
                'message' => 'Malformed id.'
            ]);
        }

        $request = $request->withAttribute(Uuid::class, $uuid);

        return $handler->handle($request);
    }
}
