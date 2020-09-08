<?php


namespace App\Tests\integration\Model;

use App\Client\Category\Persistence\CategoryEntityManagerInterface;
use App\Client\Product\Persistence\ProductEntityManager;
use App\Generated\CategoryDataProvider;
use App\Generated\ProductDataProvider;
use App\Tests\integration\Helper\ContainerHelper;

/**
 * @group Product1
 */

class ProductEntityManagerTest extends \Codeception\Test\Unit
{
    private ProductDataProvider $productDto;
    private ContainerHelper $container;
    private ProductEntityManager $productEntityManager;
    private CategoryEntityManagerInterface $categoryEntityManager;

    public function _before()
    {
        $this->container = new ContainerHelper();

        $this->productEntityManager = $this->container->getProductEntityManager();
        $this->categoryEntityManager = $this->container->getCategoryEntityManager();
        $this->createDto('fu', 'ba');
        self::assertTrue(true);
    }



    public function testCreateProduct()
    {
        $this->productDto = $this->productEntityManager->save($this->productDto);
        $productFromRepository = $this->container->getProductRepository()->get($this->productDto->getArticleNumber());
        $this->assertSame($this->productDto->getName(), $productFromRepository->getName());
        $this->assertSame($this->productDto->getDescription(), $productFromRepository->getDescription());
        $this->assertSame($this->productDto->getId(), $productFromRepository->getId());
    }

    public function testUpdateProduct()
    {
        $this->testCreateProduct();

        $this->productDto->setName('fabulous');
        $this->productDto->setDescription('even more fabulous');
        $this->productDto = $this->productEntityManager->save($this->productDto);
        $productFromRepository = $this->container->getProductRepository()->get($this->productDto->getArticleNumber());
        $this->assertSame($this->productDto->getName(), $productFromRepository->getName());
        $this->assertSame($this->productDto->getDescription(), $productFromRepository->getDescription());
        $this->assertSame($this->productDto->getId(), $productFromRepository->getId());
    }

    public function TestDeleteProduct()
    {
        $this->testCreateProduct();

        $this->productEntityManager->delete($this->productDto);

        $this->assertNull($this->container->getProductRepository()->get($this->productDto->getId()));
    }

    private function createDto(String $name, String $description)
    {
        $this->productDto = new ProductDataProvider();
        $this->productDto->setName($name);
        $this->productDto->setDescription($description);
        $this->productDto->setArticleNumber($this->container->createArticleNumber());
        $this->productDto->setCategory($this->createCategory());
        $this->productDto->setPrice(1);
        $this->productDto->setAttribute([]);
    }

    private function createCategory()
    {
        $category = new CategoryDataProvider();
        $category->setCategoryKey('abc');
        return $this->categoryEntityManager->save($category);
    }
}
