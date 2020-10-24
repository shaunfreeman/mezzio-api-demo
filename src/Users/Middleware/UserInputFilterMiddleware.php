<?php

declare(strict_types=1);

namespace Cms\Users\Middleware;

use Cms\App\ValueObject\Uuid;
use Cms\Users\Repository\UserRepositoryInterface;
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

    private UserRepositoryInterface $userRepository;

    public function __construct(
        ProblemDetailsResponseFactory $problemDetailsFactory,
        UserRepositoryInterface $userRepository
    ) {
        $this->problemDetailsFactory    = $problemDetailsFactory;
        $this->userRepository           = $userRepository;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $filter = new UserInputFilter(
            $request->getParsedBody(),
            $this->userRepository,
            $request->getAttribute(Uuid::class)
        );

        if (!$filter->isValid()) {
            $data = $filter->getData();

            return $this->problemDetailsFactory->createResponse(
                $request,
                422,
                'User Validation Error.',
                '',
                '',
                ['errors' => $data->getErrors()]
            );
        }

        $request = $request->withAttribute(UserEntity::class, $filter->getData());

        return $handler->handle($request);
    }
}
