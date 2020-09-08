<?php
declare(strict_types=1);

namespace App\Client\Order\Business;

use App\Client\Order\Persistence\OrderEntityManagerInterface;
use App\Client\Order\Persistence\OrderRepositoryInterface;
use App\Generated\OrderDataProvider;

class OrderBusinessFacade implements OrderBusinessFacadeInterface
{
    private OrderRepositoryInterface $orderRepository;
    private OrderEntityManagerInterface $orderEntityManager;

    public function __construct(OrderRepositoryInterface $orderRepository, OrderEntityManagerInterface $orderEntityManager)
    {
        $this->orderRepository = $orderRepository;
        $this->orderEntityManager = $orderEntityManager;
    }

    public function get(int $orderId): ?OrderDataProvider
    {
        return $this->orderRepository->get($orderId);
    }

    /**
     * @return OrderDataProvider[]
     */

    public function getList():array
    {
        return$this->orderRepository->getList();
    }
    public function save(OrderDataProvider $order):OrderDataProvider
    {
        return $this->orderEntityManager->save($order);
    }
    public function delete(OrderDataProvider $order)
    {
        $this->orderEntityManager->delete($order);
    }
    public function getWithDateAndUserId(int $userId, string $dateOfOrder): ?OrderDataProvider
    {
        return $this->orderRepository->getWithDateAndUserId($userId, $dateOfOrder);
    }
}
