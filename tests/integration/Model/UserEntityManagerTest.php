<?php


namespace App\Tests\integration\Model;

use App\Client\User\Persistence\UserEntityManager;
use App\Client\User\Persistence\Entity\User;
use App\Generated\UserDataProvider;
use App\Service\DatabaseManager;
use App\Service\DoctrineDataBaseManager;
use App\Service\PasswordManager;
use App\Tests\integration\Helper\ContainerHelper;
use Cycle\ORM\Transaction;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @group userrep2
 */


class UserEntityManagerTest extends \Codeception\Test\Unit
{
    private ContainerHelper $container;
    private PasswordManager $passwordManager;
    private $businessFacade;
    private EntityManager $entityManager;
    private EntityRepository $repository;
    private UserDataProvider $userDto;
    private UserEntityManager $userEntityManager;

    public function _before()
    {
        $this->container = new ContainerHelper();


        $this->passwordManager = new PasswordManager();
        $this->entityManager = DoctrineDataBaseManager::getEntityManager();
        $this->repository =  $this->entityManager->getRepository(User::class);

        $this->businessFacade = $this->container->getUserBusinessFacade();
        $this->userEntityManager = $this->container->getUserEntityManager();
        $this->createDto("test", "tester", "user");
    }

    public function _after()
    {
        $this->entityManager->remove($this->userDto);
        $this->entityManager->flush();
    }

    public function testCreateUser()
    {
        $this->userDto = $this->userEntityManager->save($this->userDto);
        $userFromRepository = $this->container->getUserRepository()->get($this->userDto->getUsername());

        $this->assertSame($this->userDto->getUsername(), $userFromRepository->getUsername());
        $this->assertSame($this->userDto->getPassword(), $userFromRepository->getPassword());
        $this->assertSame($this->userDto->getId(), $userFromRepository->getId());
    }

    public function testUpdateUser()
    {

        $this->userDto->setUsername('fu');
        $this->userDto->setPassword('even more fabulous');

        $this->userDto = $this->userEntityManager->save($this->userDto);

        $userFromRepository = $this->container->getUserRepository()->get($this->userDto->getUsername());
        $this->assertSame($this->userDto->getUsername(), $userFromRepository->getUsername());
        $this->assertSame($this->userDto->getPassword(), $userFromRepository->getPassword());
        $this->assertSame($this->userDto->getId(), $userFromRepository->getId());
    }

    public function testDeleteUser()
    {
        $this->testCreateUser();

        $this->userEntityManager->delete($this->userDto);

        $this->assertNull($this->container->getUserRepository()->get($this->userDto->getUsername()));

        unset($this->userDto);
    }

    private function createDto(String $username, String $password, String $role)
    {
        $this->userDto = new UserDataProvider();
        $this->userDto->setUsername('mate');
        $this->userDto->setPassword('seeyou');
        $this->userDto->setRole('user');
        $this->userDto->setSessionId('');
        $this->userDto->setResetPassword('');
        $this->userDto->setShoppingcardId(0);
        $this->userDto->setAddressId("");
        return $this->userDto;
    }
}
