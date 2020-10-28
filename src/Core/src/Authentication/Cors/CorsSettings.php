<?php

declare(strict_types=1);

namespace Core\Authentication\Cors;

use Mezzio\Router\RouteResult;
use Neomerx\Cors\Strategies\Settings;
use Psr\Http\Message\ServerRequestInterface;

final class CorsSettings extends Settings
{
    /**
     * Build settings.
     * @param ServerRequestInterface $request
     * @param array $options
     * @return CorsSettings
     */
    public function buildSettings(ServerRequestInterface $request, array $options): CorsSettings
    {
        $this->enableCheckHost();
        $this->enableAddAllowedMethodsToPreFlightResponse();
        $this->enableAddAllowedHeadersToPreFlightResponse();
        $this->setAllowedOrigins($options["origin"]);
        $this->setPreFlightCacheMaxAge($options['cache']);
        $this->setExposedHeaders($options["headers.expose"]);

        /** @var RouteResult $routeResult */
        $routeResult = $request->getAttribute(RouteResult::class);
        $this->setAllowedMethods((array) $routeResult->getAllowedMethods());

        $headers = array_change_key_case($options["headers.allow"], CASE_LOWER);
        $this->setAllowedHeaders($headers);

        if (true === $options["credentials"]) {
            $this->setCredentialsSupported();
        }

        $url = parse_url($options['origin.server'] ?? sprintf(
            '%s://%s:%s',
            isset($_SERVER['HTTPS']) ? 'https' : 'http',
            $_SERVER['HTTP_HOST'],
            $_SERVER['SERVER_PORT']
        ));
        $this->setServerOrigin(
            $url['scheme'],
            $url['host'],
            $url['port']
        );
        $this->enableCheckHost();

        return $this;
    }
}
