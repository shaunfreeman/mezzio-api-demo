<?php

declare(strict_types=1);

namespace Users\Handler;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Users\Entity\UserEntity;
use Users\Repository\UserRepositoryInterface;

final class UserCreateHandler implements RequestHandlerInterface
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
        $data = $request->getAttribute(UserEntity::class);

        try {
            $user = UserEntity::fromArray((array) $data);

            if (!$this->userRepository->create($user->getArrayCopy())) {
                throw new Exception(sprintf(
                    'Failed inserting user with name: %s',
                    $user->getName()
                ));
            }
        } catch (Exception $exception) {
            return $this->responseFactory
                ->createResponse(
                    $request,
                    500,
                    $exception->getMessage(),
                    'Failed adding user to database.'
                );
        }

        return new JsonResponse($user);
    }
}
