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

final class ManagerUpdateHandler implements RequestHandlerInterface
{
    private ManagerRepositoryInterface $managerRepository;

    private ProblemDetailsResponseFactory $responseFactory;

    public function __construct(
        ManagerRepositoryInterface $managerRepository,
        ProblemDetailsResponseFactory $responseFactory
    ) {
        $this->managerRepository = $managerRepository;
        $this->responseFactory = $responseFactory;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getAttribute(ManagerEntity::class);

        try {
            $manager = $this->managerRepository->find(
                $request->getAttribute(Uuid::class)
            );

            $manager = $manager->updateFromDto($data);

            if (!$this->managerRepository->update($manager->getArrayCopy())) {
                throw new Exception(sprintf(
                    'Failed updating manager id: %s',
                    $manager->getId()
                ));
            }
        } catch (Exception $exception) {
            return $this->responseFactory
                ->createResponse(
                    $request,
                    500,
                    $exception->getMessage(),
                    'Failed updating record to database.'
                );
        }

        return new JsonResponse($manager);
    }
}
