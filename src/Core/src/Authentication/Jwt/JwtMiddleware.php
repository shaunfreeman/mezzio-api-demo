<?php

declare(strict_types=1);

namespace Core\Authentication\Jwt;

use Exception;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use Core\Authentication\Identity;

final class JwtMiddleware implements MiddlewareInterface
{
    private ProblemDetailsResponseFactory $problemDetailsFactory;

    private Jwt $jwt;

    public function __construct(ProblemDetailsResponseFactory $problemDetailsFactory, Jwt $jwt)
    {
        $this->problemDetailsFactory    = $problemDetailsFactory;
        $this->jwt                      = $jwt;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /* If rules say we should not authenticate call next and return. */
        if ('OPTIONS' === $request->getMethod()) {
            return $handler->handle($request);
        }

        $scheme     = $request->getUri()->getScheme();
        $host       = $request->getUri()->getHost();
        $config     = $this->jwt->getConfig();

        /* HTTP allowed only if secure is false or server is in relaxed array. */
        if ('https' !== $scheme && true === $config->isSecure()) {
            if (!in_array($host, $config->getRelaxed())) {
                return $this->problemDetailsFactory->createResponse(
                    $request,
                    401,
                    sprintf('Insecure use of middleware over %s denied by configuration.', $host),
                    '',
                    '',
                    []
                );
            }
        }

        /* If token cannot be found or payload return with 401 Unauthorized. */
        try {
            $token      = $this->fetchToken($request);
            $payload    = Payload::fromArray($this->jwt->decodeToken($token));
        } catch (RuntimeException | Exception $exception) {
            return $this->problemDetailsFactory->createResponse(
                $request,
                401,
                $exception->getMessage(),
                '',
                '',
                ['uri' => (string) $request->getUri()]
            );
        }

        /* Add payload token to request as attribute when requested. */
        $request = $request->withAttribute(Identity::class, $payload->getIdentity());

        return $handler->handle($request);
    }

    /**
     * Fetch the access token.
     * @param ServerRequestInterface $request
     * @return string
     */
    private function fetchToken(ServerRequestInterface $request): string
    {
        /* Check for token in header. */
        $header = $request->getHeaderLine('Authorization');

        if (false === empty($header)) {
            if (preg_match('/Bearer (?P<token>.+)/', $header, $matches)) {
                return $matches['token'];
            }
        }

        throw new RuntimeException('Token not found.');
    }
}
