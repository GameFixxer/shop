<?php


namespace App\Tests\integration\Model;

use App\Client\Attribute\Persistence\Entity\Attribute;
use App\Generated\AttributeDataProvider;
use App\Service\DatabaseManager;
use App\Service\DoctrineDataBaseManager;
use App\Tests\integration\Helper\ContainerHelper;
use Cycle\ORM\Transaction;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;


/**
 * @group Attribute
 */

class AttributeEntityManagerTest extends \Codeception\Test\Unit
{
    private AttributeDataProvider $attributeDto;
    private ContainerHelper $container;
    private EntityManager $entityManager;
    private EntityRepository $repository;


    private $attributeEntityManager;

    public function _before()
    {
        $this->container = new ContainerHelper();
        $this->entityManager = DoctrineDataBaseManager::getEntityManager();
        $this->repository = $this->entityManager->getRepository(Attribute::class);
        $this->attributeEntityManager = $this->container->getAttributeEntityManager();


        $this->createDto('tester', 'testing');

    }



    public function _after()
    {
        $this->entityManager->remove($this->attributeDto);
        $this->entityManager->flush();
    }

    public function testCreateAttribute()
    {
        $this->createDto("tester", "testing");
        $this->attributeDto->setAttributeId(($this->attributeEntityManager->save($this->attributeDto))->getAttributeId());

        $attributeFromRepository = $this->container->getAttributeRepository()->get($this->attributeDto->getAttributeKey());

        $this->assertSame($this->attributeDto->getAttributeKey(), $attributeFromRepository->getAttributeKey());
        $this->assertSame($this->attributeDto->getAttributeValue(), $attributeFromRepository->getAttributeValue());
        $this->assertSame($this->attributeDto->getAttributeId(), $attributeFromRepository->getAttributeId());
    }

    public function testUpdateAttribute()
    {
        $this->attributeDto->setAttributeKey('fabulous');
        $this->attributeDto->setAttributeValue('even more fabulous');
        $this->attributeDto = $this->attributeEntityManager->save($this->attributeDto);
        $attributeFromRepository = $this->container->getAttributeRepository()->get($this->attributeDto->getAttributeKey());

        $this->assertSame($this->attributeDto->getAttributeKey(), $attributeFromRepository->getAttributeKey());
        $this->assertSame($this->attributeDto->getAttributeValue(), $attributeFromRepository->getAttributeValue());
        $this->assertSame($this->attributeDto->getAttributeId(), $attributeFromRepository->getAttributeId());
    }

    public function TestDeleteProduct()
    {
        $this->testCreateAttribute();

        $this->attributeEntityManager->delete($this->attributeDto);

        $this->assertNull($this->container->getProductRepository()->get($this->attributeDto->getAttributeId()));
    }

    private function createDto(String $name, String $description)
    {
        $this->attributeDto = new AttributeDataProvider;
        $this->attributeDto->setAttributeKey($name);
        $this->attributeDto->setAttributeValue($description);
    }
}
