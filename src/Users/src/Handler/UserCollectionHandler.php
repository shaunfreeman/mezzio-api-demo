<?php

declare(strict_types=1);

namespace Users\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Users\Repository\UserRepositoryInterface;

final class UserCollectionHandler implements RequestHandlerInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $users = $this->userRepository->findAll();

        return new JsonResponse($users);
    }
}
