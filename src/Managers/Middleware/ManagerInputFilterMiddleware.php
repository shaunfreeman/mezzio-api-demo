<?php

declare(strict_types=1);

namespace Cms\Managers\Middleware;

use Cms\App\ValueObject\Uuid;
use Cms\Managers\Entity\ManagerEntity;
use Cms\Managers\Repository\ManagerRepositoryInterface;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cms\Managers\Filter\ManagerInputFilter;

final class ManagerInputFilterMiddleware implements MiddlewareInterface
{
    private ProblemDetailsResponseFactory $problemDetailsFactory;

    private ManagerRepositoryInterface $managerRepository;

    public function __construct(
        ProblemDetailsResponseFactory $problemDetailsFactory,
        ManagerRepositoryInterface $managerRepository
    ) {
        $this->problemDetailsFactory    = $problemDetailsFactory;
        $this->managerRepository        = $managerRepository;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $filter = new ManagerInputFilter(
            $request->getParsedBody(),
            $this->managerRepository,
            $request->getAttribute(Uuid::class)
        );

        if (!$filter->isValid()) {
            $data = $filter->getData();

            return $this->problemDetailsFactory->createResponse(
                $request,
                422,
                'Manager Validation Error.',
                '',
                '',
                ['errors' => $data->getErrors()]
            );
        }

        $request = $request->withAttribute(ManagerEntity::class, $filter->getData());

        return $handler->handle($request);
    }
}
