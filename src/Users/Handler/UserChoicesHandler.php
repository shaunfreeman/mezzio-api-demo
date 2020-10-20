<?php

declare(strict_types=1);


namespace Cms\Users\Handler;


use Cms\Users\Entity\UserEntity;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class UserChoicesHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(UserEntity::getChoices());
    }
}
