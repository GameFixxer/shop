<?php
declare(strict_types=1);

namespace App\Client\Category\Persistence;

use App\Client\Category\Persistence\Entity\Category;
use App\Generated\CategoryDataProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class CategoryEntityManager implements CategoryEntityManagerInterface
{
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;
    private EntityRepository $entityRepository;
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager, CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
        $this->entityRepository = $entityManager->getRepository(Category::class);
    }



    public function delete(CategoryDataProvider $category):void
    {
        $this->entityManager->remove($category);
        $this->categoryRepository->getCategoryList();
    }

    public function save(CategoryDataProvider $category): CategoryDataProvider
    {
        if ($category->hasCategoryId()) {
            $category->setCategoryId($this->categoryRepository->getCategory($category->getCategoryId())->getCategoryId());
            $categoryEntity = $this->convert($category);
        } else {
            $categoryEntity = $this->convert($category);


        }
        $this->entityManager->persist($categoryEntity);
        $this->entityManager->flush();


        return $this->categoryRepository->getCategoryByKey($category->getCategoryKey());
    }
    private function convert(CategoryDataProvider $categoryDataProvider): Category
    {
        $categoryEntity = new Category();
        $categoryEntity->setKey($categoryDataProvider->getCategoryKey());
        return $categoryEntity;
    }
}
