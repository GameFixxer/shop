<?php
declare(strict_types=1);

namespace App\Client\Attribute\Persistence;

use App\Client\Attribute\Persistence\Entity\Attribute;
use App\Generated\AttributeDataProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;


class AttributeEntityManager implements AttributeEntityManagerInterface
{

    /**
     * @var AttributeRepository
     */
    private AttributeRepository $attributeRepository;
    private EntityManager $entityManager;
    private EntityRepository $repository;

    public function __construct(EntityManager $entityManager, AttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
        $this->repository = $entityManager->getRepository(Attribute::class);
        $this->entityManager = $entityManager;
    }



    public function delete(AttributeDataProvider $attribute):void
    {

        $this->entityManager->remove($attribute);
        $this->entityManager->flush();
        $this->attributeRepository->getList();
    }

    public function save(AttributeDataProvider $attribute): AttributeDataProvider
    {
        if ($attribute->hasAttributeId()) {
            $attribute->setAttributeId(($this->attributeRepository->get($attribute->getAttributeKey())->getAttributeId()));
        }
        $userEntity = $this->convert($attribute);


        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();

        $this->attributeRepository->get($attribute->getAttributeKey());
        $attributeDataProvider = $this->attributeRepository->get($attribute->getAttributeKey());
        if (! $attributeDataProvider instanceof AttributeDataProvider) {
            throw new \Exception('Fatal error while saving/loading', 1);
        }
        return $attributeDataProvider;
    }

    private function convert(AttributeDataProvider $attributeDataProvider):Attribute
    {
        $attribute = new Attribute();
        $attribute->setAttributeKey($attributeDataProvider->getAttributeKey());
        $attribute->setAttributeValue($attributeDataProvider->getAttributeValue());

        return $attribute;
    }
}
