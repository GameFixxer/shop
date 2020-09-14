<?php

declare(strict_types=1);

namespace App\Client\User\Persistence;

use App\Client\User\Persistence\Entity\User;
use App\Generated\UserDataProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class UserEntityManager implements UserEntityManagerInterface
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    private EntityManager $entityManager;
    private EntityRepository $repository;

    public function __construct(EntityManager $entityManager, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(User::class);
    }

    public function delete(UserDataProvider $user):void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        $this->userRepository->getList();
    }

    public function save(UserDataProvider $user): UserDataProvider
    {
        if ($user->hasId()) {
            $user->setId($this->userRepository->get($user->getUsername())->getId());
        }
        $userEntity = $this->convert($user);


        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();

        $this->userRepository->get($user->getUsername());
        $newUser = $this->userRepository->get($user->getUsername());
        if (! $newUser instanceof UserDataProvider) {
            throw new \Exception('Fatal error while saving/loading', 1);
        }
        return $newUser;
    }
    private function convert(UserDataProvider $userDataProvider):User
    {
        $userEntity = new User();
        $userEntity->setShoppingCardId($userDataProvider->getShoppingCardId());
        $userEntity->setSessionId($userDataProvider->getSessionId());
        $userEntity->setResetPassword($userDataProvider->getResetPassword());
        $userEntity->setUsername($userDataProvider->getUsername());
        $userEntity->setRole($userDataProvider->getRole());
        $userEntity->setPassword($userDataProvider->getPassword());
        if ($userDataProvider->hasAddressId()) {
            $userEntity->setAddressIds($userDataProvider->getAddressId());
        } else {
            $userEntity->setAddressIds("");
        }

        return $userEntity;
    }
}
