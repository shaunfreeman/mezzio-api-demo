<?php

declare(strict_types=1);

namespace Managers\Handler;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Managers\Entity\ManagerEntity;
use Managers\Repository\ManagerRepositoryInterface;

final class ManagerCreateHandler implements RequestHandlerInterface
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
        $data = $request->getAttribute(ManagerEntity::class);

        try {
            $manager = ManagerEntity::fromArray((array) $data);

            if (!$this->managerRepository->create($manager->getArrayCopy())) {
                throw new Exception(sprintf(
                    'Failed inserting manager with name: %s',
                    $manager->getName()
                ));
            }
        } catch (Exception $exception) {
            return $this->responseFactory
                ->createResponse(
                    $request,
                    500,
                    $exception->getMessage(),
                    'Failed adding manager to database.'
                );
        }

        return new JsonResponse($manager->getArrayCopy());
    }
}
