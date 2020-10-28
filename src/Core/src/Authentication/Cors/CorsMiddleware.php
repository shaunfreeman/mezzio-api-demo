<?php

declare(strict_types=1);

namespace Core\Authentication\Cors;

use Closure;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Neomerx\Cors\Analyzer as CorsAnalyzer;
use Neomerx\Cors\Contracts\AnalysisResultInterface as CorsAnalysisResultInterface;
use Neomerx\Cors\Contracts\Constants\CorsResponseHeaders;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class CorsMiddleware implements MiddlewareInterface
{
    private array $options = [
        "origin" => [],
        "methods" => [],
        "headers.allow" => [],
        "headers.expose" => [],
        "credentials" => false,
        "origin.server" => null,
        "cache" => 0,
    ];

    private Closure $responseFactory;

    private ProblemDetailsResponseFactory $problemDetailsFactory;

    public function __construct(
        callable $responseFactory,
        ProblemDetailsResponseFactory $problemDetailsFactory,
        $options = []
    ) {

        // Factories is wrapped in a closure in order to enforce return type safety.
        $this->responseFactory = function () use ($responseFactory): ResponseInterface {
            return $responseFactory();
        };
        $this->problemDetailsFactory = $problemDetailsFactory;
        /* Store passed in options overwriting any defaults. */
        $this->options = array_merge($this->options, $options);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $settings   = (new CorsSettings())->buildSettings($request, $this->options);
        $analyzer   = CorsAnalyzer::instance($settings);
        $cors       = $analyzer->analyze($request);
        $response   = ($this->responseFactory)();

        switch ($cors->getRequestType()) {
            case CorsAnalysisResultInterface::ERR_ORIGIN_NOT_ALLOWED:
                $response = $response->withStatus(401);
                return $this->processError($request, $response, [
                    "message" => "CORS request origin is not allowed.",
                ]);
            case CorsAnalysisResultInterface::ERR_METHOD_NOT_SUPPORTED:
                $response = $response->withStatus(405);
                return $this->processError($request, $response, [
                    "message" => "CORS requested method is not supported.",
                ]);
            case CorsAnalysisResultInterface::ERR_HEADERS_NOT_SUPPORTED:
                $response = $response->withStatus(401);
                return $this->processError($request, $response, [
                    "message" => "CORS requested header is not allowed.",
                ]);
            case CorsAnalysisResultInterface::TYPE_PRE_FLIGHT_REQUEST:
                $corsHeaders = $cors->getResponseHeaders();
                foreach ($corsHeaders as $header => $value) {
                    /* Diactoros errors on integer values. */
                    if (false === is_array($value)) {
                        $value = (string)$value;
                    }
                    $response = $response->withHeader($header, $value);
                }
                return $response->withStatus(200);
            case CorsAnalysisResultInterface::TYPE_REQUEST_OUT_OF_CORS_SCOPE:
                return $handler->handle($request);
            default:
                /* Actual CORS request. */
                $response = $handler->handle($request);
                $corsHeaders = $cors->getResponseHeaders();
                $corsHeaders = $this->fixHeaders($corsHeaders);

                foreach ($corsHeaders as $header => $value) {
                    /* Diactoros errors on integer values. */
                    if (false === is_array($value)) {
                        $value = (string)$value;
                    }
                    $response = $response->withHeader($header, $value);
                }
                return $response;
        }
    }

    /**
     * Edge cannot handle multiple Access-Control-Expose-Headers headers
     * @param array $headers
     * @return array
     */
    private function fixHeaders(array $headers): array
    {
        if (isset($headers[CorsResponseHeaders::EXPOSE_HEADERS])) {
            $headers[CorsResponseHeaders::EXPOSE_HEADERS] =
                implode(",", $headers[CorsResponseHeaders::EXPOSE_HEADERS]);
        }
        return $headers;
    }

    private function processError(ServerRequestInterface $request, ResponseInterface $response, array $arguments = null)
    {
        return $this->problemDetailsFactory->createResponse(
            $request,
            $response->getStatusCode(),
            $arguments['message'],
            $response->getReasonPhrase(),
            '',
            []
        );
    }
}
