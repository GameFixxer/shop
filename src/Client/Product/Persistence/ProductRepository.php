<?php

declare(strict_types=1);

namespace App\Client\Product\Persistence;


use App\Client\Product\Persistence\Entity\Product;
use App\Client\Product\Persistence\Mapper\ProductMapperInterface;
use App\Generated\ProductDataProvider;
use App\Service\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;

class ProductRepository implements ProductRepositoryInterface
{
    private ProductMapperInterface $productMapper;
    private ObjectRepository $productRepository;

    /**
     * ProductRepository constructor.
     * @param ProductMapperInterface $productMapper
     * @param EntityManager $entityManager
     */
    public function __construct(ProductMapperInterface $productMapper, EntityManager $entityManager)
    {
        $this->productMapper = $productMapper;
        $this->productRepository = $entityManager->getRepository(Product::class);
    }


    /**
     * @return ProductDataProvider[]
     */
    public function getList(): array
    {
        $productList = [];
        $productEntityList = (array)$this->productRepository->findAll();
        /** @var  Product $product */
        foreach ($productEntityList as $product) {
            $productList[] = $this->productMapper->map($product);
        }
        return $productList;
    }

    public function get(string $articleNumber): ?ProductDataProvider
    {
        $productEntity = $this->productRepository->findBy(['articleNumber'=>$articleNumber]);
        if (isset($productEntity)) {
            return $this->productMapper->map($productEntity[0]);
        }
        return null;
    }
}

