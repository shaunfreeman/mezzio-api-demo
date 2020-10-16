<?php

declare(strict_types=1);

namespace Cms\Users\Middleware;

use Closure;
use Cms\App\Stdlib\ProblemDetailsTrait;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cms\Users\Entity\UserEntity;
use Cms\Users\Filter\UserInputFilter;

final class UserInputFilterMiddleware implements MiddlewareInterface
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

        $requestBody    = $request->getParsedBody();
        $filter         = new UserInputFilter($requestBody);

        if (!$filter->isValid()) {
            $response   = ($this->responseFactory)();
            $response   = $response->withStatus(400);
            $data       = $filter->getData();
            return $this->processError($request, $response, [
                'message'           => 'User Validation Error.',
                'error_messages'    => $data->getErrors(),
            ]);
        }

        $request = $request->withAttribute(UserEntity::class, $filter->getData());
        return $handler->handle($request);
    }
}
