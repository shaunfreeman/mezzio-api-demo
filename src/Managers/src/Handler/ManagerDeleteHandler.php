<?php

declare(strict_types=1);

namespace Managers\Handler;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\ValueObject\Uuid;
use Managers\Repository\ManagerRepositoryInterface;

final class ManagerDeleteHandler implements RequestHandlerInterface
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
        $uuid = $request->getAttribute(Uuid::class);

        try {
            $result = $this->managerRepository->delete($uuid);

            if (false === $result) {
                throw new Exception(sprintf(
                    'Could not delete record with id: %s.',
                    $uuid
                ));
            }
        } catch (Exception $exception) {
            return $this->responseFactory
                ->createResponse(
                    $request,
                    500,
                    $exception->getMessage(),
                    'Failed deleting record in database.'
                );
        }

        return new JsonResponse([
            'message' => sprintf('record %s has been deleted', $uuid)
        ]);
    }
}
