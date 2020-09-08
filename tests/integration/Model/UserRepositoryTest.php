<?php


namespace App\Tests\integration\Model;

use App\Client\User\Persistence\Entity\User;
use App\Generated\UserDataProvider;
use App\Service\DatabaseManager;
use App\Service\PasswordManager;
use App\Tests\integration\Helper\ContainerHelper;
use Cycle\ORM\Transaction;

/**
 * @group userrep
 */

class UserRepositoryTest extends \Codeception\Test\Unit
{
    private ContainerHelper $container;
    private Transaction $transaction;
    private \Cycle\ORM\RepositoryInterface $ormUserRepository;
    private PasswordManager $passwordManager;
    private $businessFacade;
    private UserDataProvider $entity;

    public function _before()
    {
        $this->container = new ContainerHelper();

        $databaseManager = new DatabaseManager();

        $this->passwordManager = new PasswordManager();

        $orm = $databaseManager->connect();

        $this->ormUserRepository = $orm->getRepository(User::class);

        $this->transaction = new Transaction($orm);

        $this->businessFacade = $this->container->getUserBusinessFacade();

        $this->entity = $this->businessFacade->save($this->createUser());
    }

    public function _after()
    {
        if ($this->ormUserRepository->findByPK($this->entity->getId()) instanceof User) {
            $this->transaction->delete($this->ormUserRepository->findByPK($this->entity->getId()));
            $this->transaction->run();
        }
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
        $productRepository = $this->container->getProductRepository();

        $this->assertNull($productRepository->get(0));
    }

    public function testGetLastUserOfUserListWithNonEmptyDatabase()
    {
        $userRepository = $this->container->getUserRepository();

        $userListFromUserRepository = $userRepository->getList();
        $lastUserOfUserList = end($userListFromUserRepository);

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

        return $this->entity;
    }
}
