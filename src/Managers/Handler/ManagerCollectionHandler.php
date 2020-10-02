<?php

declare(strict_types=1);

namespace Cms\Managers\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cms\Managers\Repository\ManagerRepositoryInterface;

final class ManagerCollectionHandler implements RequestHandlerInterface
{
    private ManagerRepositoryInterface $managerRepository;

    public function __construct(ManagerRepositoryInterface $managerRepository)
    {
        $this->managerRepository = $managerRepository;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $managers = $this->managerRepository->findAll();

        return new JsonResponse($managers);
    }
}
