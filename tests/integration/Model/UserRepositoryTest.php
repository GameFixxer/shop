<?php


namespace App\Tests\integration\Model;

use App\Client\User\Persistence\Entity\User;
use App\Generated\UserDataProvider;
use App\Service\DoctrineDataBaseManager;
use App\Service\PasswordManager;
use App\Tests\integration\Helper\ContainerHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @group userrep
 */

class UserRepositoryTest extends \Codeception\Test\Unit
{
    private ContainerHelper $container;
    private PasswordManager $passwordManager;
    private $businessFacade;
    private UserDataProvider $entity;
    private EntityManager $entityManager;
    private EntityRepository $repository;

    public function _before()
    {
        $this->container = new ContainerHelper();


        $this->passwordManager = new PasswordManager();
        $this->entityManager = DoctrineDataBaseManager::getEntityManager();
        $this->repository =  $this->entityManager->getRepository(User::class);

        $this->businessFacade = $this->container->getUserBusinessFacade();

        $this->entity = $this->businessFacade->save($this->createUser());
    }

    public function _after()
    {
        $this->entityManager->remove($this->entity);
        $this->entityManager->flush();
    }

    public function testGetUserWithExistingUser()
    {
        $userRepository = $this->container->getUserRepository();

        $userDtoFromRepository = $userRepository->get($this->entity->getUsername());
        $this->assertSame($this->entity->getUsername(), $userDtoFromRepository->getUsername());
        $this->assertSame($this->entity->getPassword(), $userDtoFromRepository->getPassword());
        $this->assertSame($this->entity->getId(), $userDtoFromRepository->getId());
    }

    public function testGetUserWithNonExistingUser()
    {
        $repository = $this->container->getUserRepository();

        $this->assertNull($repository->get(0));
    }

    public function testGetLastUserOfUserListWithNonEmptyDatabase()
    {
        $userRepository = $this->container->getUserRepository();

        $userListFromUserRepository = $userRepository->getList();
        $lastUserOfUserList = $userListFromUserRepository[0];

        $this->assertSame($this->entity->getUsername(), $lastUserOfUserList ->getUsername());
        $this->assertSame($this->entity->getPassword(), $lastUserOfUserList->getPassword());
        $this->assertSame($this->entity->getId(), $lastUserOfUserList ->getId());
    }

    private function createUser() :UserDataProvider
    {
        $this->entity = new UserDataProvider();
        $this->entity->setUsername('mate');
        $this->entity->setPassword('seeyou');
        $this->entity->setRole('user');
        $this->entity->setSessionId('');
        $this->entity->setResetPassword('');
        $this->entity->setShoppingcardId(0);
        $this->entity->setAddressId("");

        return $this->entity;
    }
}
