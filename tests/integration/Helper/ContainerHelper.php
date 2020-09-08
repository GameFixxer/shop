<?php


namespace App\Tests\integration\Helper;

use App\Backend\ImportComponent\Loader\CsvImportLoader;
use App\Backend\ImportProduct\Business\Model\Create\Product;
use App\Backend\ImportProduct\Business\Model\Update\ProductAttribute;
use App\Backend\ImportProduct\Business\Model\Update\ProductCategory;
use App\Backend\ImportProduct\Business\Model\Update\ProductImporter;
use App\Backend\ImportProduct\Business\Model\Update\ProductInformation;
use App\Client\Address\Business\AddressBusinessFacade;
use App\Client\Address\Persistence\AddressEntityManager;
use App\Client\Address\Persistence\AddressEntityManagerInterface;
use App\Client\Attribute\Business\AttributeBusinessFacade;
use App\Client\Attribute\Persistence\AttributeEntityManager;
use App\Client\Attribute\Persistence\AttributeRepository;
use App\Client\Category\Business\CategoryBusinessFacade;
use App\Client\Category\Persistence\CategoryEntityManager;
use App\Client\Category\Persistence\CategoryRepository;
use App\Client\Order\Business\OrderBusinessFacade;
use App\Client\Order\Persistence\OrderEntityManager;
use App\Client\Order\Persistence\OrderRepository;
use App\Client\Product\Business\ProductBusinessFacade;
use App\Client\Product\Persistence\ProductEntityManager;
use App\Client\Product\Persistence\ProductRepository;
use App\Client\ShoppingCard\Business\ShoppingCardBusinessFacade;
use App\Client\ShoppingCard\Persistence\Mapper\ShoppingCardMapper;
use App\Client\ShoppingCard\Persistence\ShoppingCardEntityManager;
use App\Client\User\Business\UserBusinessFacade;
use App\Client\User\Persistence\UserEntityManager;
use App\Client\User\Persistence\UserRepository;
use App\Component\SymfonyContainer;
use App\Service\DatabaseManager;
use App\Service\SessionUser;
use Cycle\ORM\ORM;

class ContainerHelper
{
    /**
     * Define custom actions here
     * @param int $id
     * @throws Exception
     */
    private $container;

    public function __construct()
    {
        $this->container = (new SymfonyContainer())->getContainer();
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function createArticleNumber(): string
    {
        return rand(1, 1000).substr(rand(1, 1000), rand(1, 1000));
    }

    public function getProductRepository(): ProductRepository
    {
        return $this->container->get(ProductRepository::class);
    }

    public function getProductEntityManager(): ProductEntityManager
    {
        return $this->container->get(ProductEntityManager::class);
    }

    public function getUserEntityManager(): UserEntityManager
    {
        return $this->container->get(UserEntityManager::class);
    }

    public function getUserRepository(): UserRepository
    {
        return $this->container->get(UserRepository::class);
    }

    public function getCategoryEntityManager(): CategoryEntityManager
    {
        return $this->container->get(CategoryEntityManager::class);
    }

    public function getCategoryRepository(): CategoryRepository
    {
        return $this->container->get(CategoryRepository::class);
    }

    public function getCsvImportLoader(): CsvImportLoader
    {
        return $this->container->get(CsvImportLoader::class);
    }

    public function getOrmProductRepository()
    {
        return ($this->container->get(DatabaseManager::class))->connect();
    }

    public function getCreateProduct()
    {
        return $this->container->get(Product::class);
    }

    public function getUpdateProductCategory()
    {
        return $this->container->get(ProductCategory::class);
    }

    public function getUpdateProductInformation()
    {
        return $this->container->get(ProductInformation::class);
    }

    public function getUpdateImport()
    {
        return $this->container->get(ProductImporter::class);
    }

    public function getAttributeRepository()
    {
        return $this->container->get(AttributeRepository::class);
    }

    public function getUpdateAttribute()
    {
        return $this->container->get(ProductAttribute::class);
    }

    public function getAttributeEntityManager()
    {
        return $this->container->get(AttributeEntityManager::class);
    }
    public function getUserSession()
    {
        return$this->container->get(SessionUser::class);
    }

    public function getOrderRepository()
    {
        return $this->container->get(OrderRepository::class);
    }

    public function getOrderEntityManager()
    {
        return$this->container->get(OrderEntityManager::class);
    }

    public function getAddressEntityManager()
    {
        return $this->container->get(AddressEntityManager::class);
    }

    public function getShoppingCardEntityManager()
    {
        return $this->container->get(ShoppingCardEntityManager::class);
    }
    public function getAttributeBusinessFacade()
    {
        return$this->container->get(AttributeBusinessFacade::class);
    }

    public function getUserBusinessFacade()
    {
        return $this->container->get(UserBusinessFacade::class);
    }

    public function getProductBusinessFacade()
    {
        return $this->container->get(ProductBusinessFacade::class);
    }

    public function getAddressBusinessFacade()
    {
        return $this->container->get(AddressBusinessFacade::class);
    }

    public function getShoppingCardBusinessFacade()
    {
        return $this->container->get(ShoppingCardBusinessFacade::class);
    }

    public function getOrderBusinessFacade()
    {
        return $this->container->get(OrderBusinessFacade::class);
    }

    public function getCategoryBusinessFacade()
    {
        return$this->container->get(CategoryBusinessFacade::class);
    }

    public function getShoppingCardMapper()
    {
        return $this->container->get(ShoppingCardMapper::class);
    }
}
