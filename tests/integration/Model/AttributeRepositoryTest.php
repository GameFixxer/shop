<?php


namespace App\Tests\integration\Model;


use App\Client\Attribute\Persistence\Entity\Attribute;

use App\Service\DoctrineDataBaseManager;
use App\Tests\integration\Helper\ContainerHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;


/**
 * @group Attribute2
 */

class AttributeRepositoryTest extends \Codeception\Test\Unit
{
    private ContainerHelper $container;
    private EntityManager $entityManager;
    private EntityRepository $repository;
    private Attribute $entity;

    public function _before()
    {
        $this->container = new ContainerHelper();
        $this->entityManager = DoctrineDataBaseManager::getEntityManager();
        $this->repository = $this->entityManager->getRepository(Attribute::class);
        $this->createAttributeEntity();
        $this->entityManager->persist($this->entity);
        $this->entityManager->flush();

    }

    public function _after()
    {
        $this->entityManager->remove($this->entity);
        $this->entityManager->flush();
    }

    public function testGetProductWithExistingProduct()
    {
        $attributeRepository = $this->container->getAttributeRepository();

        $productDtoFromRepository = $attributeRepository->get($this->entity->getAttributeKey());

        $this->assertSame($this->entity->getAttributeKey(), $productDtoFromRepository->getAttributeKey());
        $this->assertSame($this->entity->getAttributeValue(), $productDtoFromRepository->getAttributeValue());
        $this->assertSame($this->entity->getId(), $productDtoFromRepository->getAttributeId());
    }

    public function testGetProductWithNonExistingProduct()
    {
        $attributeRepository = $this->container->getAttributeRepository();
        $key = "tester12";
        $tester = $attributeRepository->get($key);
        $this->assertNull($tester);
    }

    public function testGetLastProductOfProductListWithNonEmptyDatabase()
    {
        $attributeRepository = $this->container->getAttributeRepository();

        $productListFromProductRepository = $attributeRepository->getList();

        $lastProductOfProductRepositoryList = end($productListFromProductRepository);

        $this->assertSame($this->entity->getAttributeKey(), $lastProductOfProductRepositoryList->getAttributeKey());
        $this->assertSame($this->entity->getAttributeValue(), $lastProductOfProductRepositoryList->getAttributeValue());
        $this->assertSame($this->entity->getId(), $lastProductOfProductRepositoryList->getAttributeId());
    }

    private function createAttributeEntity() :Attribute
    {
        $this->entity = new Attribute();
        $this->entity->setAttributeKey('mouse');
        $this->entity->setAttributeValue('mouse2');

        return $this->entity;
    }
}
