<?php

declare(strict_types=1);

namespace Cms\Managers\Handler;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cms\App\ValueObject\Uuid;
use Cms\Managers\Entity\ManagerEntity;
use Cms\Managers\Repository\ManagerRepositoryInterface;

final class ManagerReadHandler implements RequestHandlerInterface
{
    private ManagerRepositoryInterface $managerRepository;

    private ProblemDetailsResponseFactory $responseFactory;

    public function __construct(
        ManagerRepositoryInterface $managerRepository,
        ProblemDetailsResponseFactory $responseFactory
    ) {
        $this->managerRepository    = $managerRepository;
        $this->responseFactory      = $responseFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $uuid = $request->getAttribute(Uuid::class);

        try {
            $result     = $this->managerRepository->find($uuid);
            $manager    = ManagerEntity::fromArray($result);
        } catch (Exception $exception) {
            return $this->responseFactory
                ->createResponse(
                    $request,
                    401,
                    $exception->getMessage(),
                    'Not found.'
                );
        }

        return new JsonResponse($manager->getArrayCopy());
    }
}
