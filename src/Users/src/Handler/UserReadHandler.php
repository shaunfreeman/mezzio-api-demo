<?php

declare(strict_types=1);

namespace Users\Handler;

use Core\ValueObject\Uuid;
use Users\Repository\UserRepositoryInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UserReadHandler implements RequestHandlerInterface
{
    private UserRepositoryInterface $userRepository;

    private ProblemDetailsResponseFactory $responseFactory;

    public function __construct(
        UserRepositoryInterface $userRepository,
        ProblemDetailsResponseFactory $responseFactory
    ) {
        $this->userRepository   = $userRepository;
        $this->responseFactory  = $responseFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uuid = $request->getAttribute(Uuid::class);

        try {
            $user = $this->userRepository->find($uuid);
        } catch (Exception $exception) {
            return $this->responseFactory
                ->createResponse(
                    $request,
                    401,
                    $exception->getMessage(),
                    'Not found.'
                );
        }

        return new JsonResponse($user);
    }
}
