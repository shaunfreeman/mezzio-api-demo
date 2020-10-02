<?php

declare(strict_types=1);

namespace Cms\App\Stdlib;

use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

trait ProblemDetailsTrait
{
    private ProblemDetailsResponseFactory $problemDetailsFactory;

    /**
     * Call the error handler if it exists.
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return ResponseInterface
     */
    private function processError(ServerRequestInterface $request, ResponseInterface $response, array $arguments = null)
    {
        return $this->problemDetailsFactory->createResponse(
            $request,
            $response->getStatusCode(),
            $response->getReasonPhrase(),
            $arguments['message'],
            '',
            []
        );
    }
}
