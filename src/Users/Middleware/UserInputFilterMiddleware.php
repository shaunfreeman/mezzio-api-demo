<?php

declare(strict_types=1);

namespace Cms\Users\Middleware;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cms\Users\Entity\UserEntity;
use Cms\Users\Filter\UserInputFilter;

final class UserInputFilterMiddleware implements MiddlewareInterface
{
    private ProblemDetailsResponseFactory $problemDetailsFactory;

    public function __construct(ProblemDetailsResponseFactory $problemDetailsFactory)
    {
        $this->problemDetailsFactory = $problemDetailsFactory;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        $requestBody    = $request->getParsedBody();
        $filter         = new UserInputFilter($requestBody);

        if (!$filter->isValid()) {
            $data = $filter->getData();

            return $this->problemDetailsFactory->createResponse(
                $request,
                422,
                'User Validation Error.',
                '',
                '',
                ['error_messages' => $data->getErrors()]
            );
        }

        $request = $request->withAttribute(UserEntity::class, $filter->getData());
        return $handler->handle($request);
    }
}
