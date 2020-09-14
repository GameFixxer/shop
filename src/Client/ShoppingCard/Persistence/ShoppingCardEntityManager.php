<?php


namespace App\Client\ShoppingCard\Persistence;

use App\Client\ShoppingCard\Persistence\Entity\ShoppingCard;
use App\Generated\ShoppingCardDataProvider;
use Cycle\ORM\ORM;
use Cycle\ORM\Transaction;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class ShoppingCardEntityManager implements ShoppingCardEntityManagerInterface
{
    /**
     * @var ShoppingCardRepositoryInterface
     */
    private ShoppingCardRepositoryInterface $shoppingCardRepository;
    private EntityManager $entityManager;
    private EntityRepository $repository;


    public function __construct(EntityManager $entityManager, ShoppingCardRepositoryInterface $shoppingCardRepository)
    {
        $this->shoppingCardRepository = $shoppingCardRepository;
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(ShoppingCard::class);
    }



    public function delete(ShoppingCardDataProvider $shoppingCardDataProvider):void
    {
        $this->entityManager->remove($shoppingCardDataProvider);
        $this->entityManager->flush();
    }

    public function save(ShoppingCardDataProvider $shoppingCardDataProvider): ShoppingCardDataProvider
    {
        if ($shoppingCardDataProvider->hasId()) {
            $shoppingCardDataProvider->setId($this->shoppingCardRepository->getByUserId($shoppingCardDataProvider->getUser()->getId())->getId());
        }
        $userEntity = $this->convert($shoppingCardDataProvider);


        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();

        $newUser = $this->shoppingCardRepository->getByUserId($shoppingCardDataProvider->getUser()->getId());
        if (! $newUser instanceof ShoppingCardDataProvider) {
            throw new \Exception('Fatal error while saving/loading', 1);
        }
        return $newUser;
    }
    private function extractArticleNumber(array $products):string
    {
        $articleNumbers = "";
        foreach ($products as $article) {
            $articleNumbers = $articleNumbers.$article->getArticleNumber();
        }
        return $articleNumbers;
    }

    private function convert(ShoppingCardDataProvider $shoppingCardDataProvider):ShoppingCard
    {
        $shoppingCard = new ShoppingCard();
        if ($shoppingCardDataProvider->hasId()) {
            $shoppingCard->setId($shoppingCardDataProvider->getId());
        }
        $shoppingCard->setUserId($shoppingCardDataProvider->getUser()->getId());
        $shoppingCard->setShoppingCard($this->extractArticleNumber($shoppingCardDataProvider->getProduct()));
        $shoppingCard->setSum($shoppingCardDataProvider->getSum());


        return $shoppingCard;
    }
}
