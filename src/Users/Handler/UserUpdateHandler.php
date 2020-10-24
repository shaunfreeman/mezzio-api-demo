<?php

declare(strict_types=1);

namespace Cms\Users\Handler;

use Cms\App\ValueObject\Uuid;
use Cms\Users\Entity\UserEntity;
use Cms\Users\Repository\UserRepositoryInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UserUpdateHandler implements RequestHandlerInterface
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
            $user = $this->userRepository->find(
                $request->getAttribute(Uuid::class)
            );

            $user = $user->updateFromDto($data);

            if (!$this->userRepository->update($user->getArrayCopy())) {
                throw new Exception(sprintf(
                    'Failed updating manager id: %s',
                    $user->getId()
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

        return new JsonResponse($user);
    }
}
