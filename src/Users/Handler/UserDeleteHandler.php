<?php

declare(strict_types=1);

namespace Cms\Users\Handler;

use Cms\App\ValueObject\Uuid;
use Cms\Users\Repository\UserRepositoryInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UserDeleteHandler implements RequestHandlerInterface
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
            $result = $this->userRepository->delete($uuid);

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
