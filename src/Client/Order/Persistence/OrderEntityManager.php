<?php
declare(strict_types=1);

namespace App\Client\Order\Persistence;

use App\Client\Order\Persistence\Entity\Order;
use App\Generated\OrderDataProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class OrderEntityManager implements OrderEntityManagerInterface
{
    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;
    private EntityManager $entityManager;
    private EntityRepository $repository;

    public function __construct(EntityManager $entityManager, OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Order::class);
    }



    public function delete(OrderDataProvider $order):void
    {
        $this->entityManager->remove($order);
        $this->entityManager->flush();
        $this->orderRepository->getList();
    }

    public function save(OrderDataProvider $order): OrderDataProvider
    {
        if ($order->hasId()) {
            $order->setId($this->orderRepository->getWithDateAndUserId($order->getUser()->getId(), $order->getDateOfOrder())->getId());
        }
        $userEntity = $this->convert($order);


        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();

        $newOrder = $this->orderRepository->getWithDateAndUserId($order->getUser()->getId(), $order->getDateOfOrder());
        if (! $newOrder instanceof OrderDataProvider) {
            throw new \Exception('Fatal error while saving/loading', 1);
        }
        return $newOrder;
    }
    private function shrinkProductToArticleNumber(array $products):string
    {
        $articleNumber = [];
        foreach ($products as $article) {
            $articleNumber[]=$article->getArticleNumber();
        }
        return implode(',', $articleNumber);
    }

    private function convert(OrderDataProvider $orderDataProvider):Order
    {
        $order = new Order();
        $order->setId($orderDataProvider->getId());
        $order->setUserId($orderDataProvider->getUser()->getId());
        $order->setAddressId($orderDataProvider->getAddress()->getAddress_id());
        $order->setShoppingCardId($orderDataProvider->getShoppingCard()->getId());
        $order->setSum($orderDataProvider->getSum());
        $order->setOrderedProducts($this->shrinkProductToArticleNumber($orderDataProvider->getShoppingCard()->getProduct()));
        $order->setStatus($orderDataProvider->getStatus());
        $order->setDateOfOrder($orderDataProvider->getDateOfOrder());

        return $order;
    }
}
