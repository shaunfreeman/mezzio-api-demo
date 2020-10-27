<?php

declare(strict_types=1);

namespace App\Authentication;

use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Authentication\Jwt\Jwt;

final class AuthenticationHandler implements RequestHandlerInterface
{
    private ProblemDetailsResponseFactory $problemDetailsFactory;

    private AuthenticationInterface $auth;

    private Jwt $jwt;

    public function __construct(
        AuthenticationInterface $auth,
        Jwt $jwt,
        ProblemDetailsResponseFactory $problemDetailsFactory
    ) {
        $this->problemDetailsFactory    = $problemDetailsFactory;
        $this->auth                     = $auth;
        $this->jwt                      = $jwt;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $credentials = $request->getParsedBody();

        try {
            $identity = $this->auth
                ->authenticate($credentials['email'], $credentials['password']);
        } catch (Exception $exception) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                401,
                $exception->getMessage(),
                'Failed validating credentials.',
                ''
            );
        }

        return new JsonResponse([
            'token' => $this->jwt->createToken($identity),
        ]);
    }
}
