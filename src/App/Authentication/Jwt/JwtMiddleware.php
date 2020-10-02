<?php

declare(strict_types=1);

namespace Cms\App\Authentication\Jwt;

use Closure;
use Exception;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;
use Cms\App\Authentication\Identity;
use Cms\App\Stdlib\ProblemDetailsTrait;

final class JwtMiddleware implements MiddlewareInterface
{
    use ProblemDetailsTrait;

    private Jwt $jwt;

    private Closure $responseFactory;

    public function __construct(
        callable $responseFactory,
        ProblemDetailsResponseFactory $problemDetailsFactory,
        Jwt $jwt
    ) {
        // Factories is wrapped in a closure in order to enforce return type safety.
        $this->responseFactory = function () use ($responseFactory) : ResponseInterface {
            return $responseFactory();
        };
        $this->problemDetailsFactory = $problemDetailsFactory;
        $this->jwt = $jwt;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /* If rules say we should not authenticate call next and return. */
        if ('OPTIONS' === $request->getMethod()) {
            return $handler->handle($request);
        }

        $scheme     = $request->getUri()->getScheme();
        $host       = $request->getUri()->getHost();
        $response   = ($this->responseFactory)();
        $config     = $this->jwt->getConfig();

        /* HTTP allowed only if secure is false or server is in relaxed array. */
        if ('https' !== $scheme && true === $config->isSecure()) {
            if (!in_array($host, $config->getRelaxed())) {
                $response = $response->withStatus(401);
                return $this->processError($request, $response, [
                    'message' => sprintf(
                        'Insecure use of middleware over %s denied by configuration.',
                        $host
                    )
                ]);
            }
        }

        /* If token cannot be found or payload return with 401 Unauthorized. */
        try {
            $token      = $this->fetchToken($request);
            $payload    = Payload::fromArray($this->jwt->decodeToken($token));
        } catch (RuntimeException | Exception $exception) {
            $response   = $response->withStatus(401);
            return $this->processError($request, $response, [
                'message'   => $exception->getMessage(),
                'uri'       => (string) $request->getUri()
            ]);
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
