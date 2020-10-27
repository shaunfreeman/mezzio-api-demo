<?php

declare(strict_types=1);

namespace Orders\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Orders\Repository\Pdo\OrderRepository;

final class OrderListHandler implements RequestHandlerInterface
{
    private OrderRepository $ordersRepository;

    public function __construct(OrderRepository $ordersRepository)
    {
        $this->ordersRepository = $ordersRepository;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $orders = $this->ordersRepository->findAll();

        return new JsonResponse([
            'orders' => $orders,
        ]);
    }
}
