<?php

declare(strict_types=1);

namespace App\Client\User\Persistence;

use App\Client\User\Persistence\Entity\User;
use App\Client\User\Persistence\Mapper\UserMapperInterface;
use App\Generated\UserDataProvider;
use Cycle\ORM\ORM;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class UserRepository implements UserRepositoryInterface
{
    private UserMapperInterface $userMapper;
    private EntityRepository $entityRepository;

    public function __construct(UserMapperInterface $userMapper, EntityManager $entityManager)
    {
        $this->userMapper = $userMapper;
        $this->entityRepository = $entityManager->getRepository(User::class);
    }

    /**
     * @return UserDataProvider[]
     */
    public function getList(): array
    {
        $userList = [];

        $userEntityList = (array)$this->entityRepository->findAll();
        /** @var  User $user */
        foreach ($userEntityList as $user) {
            if (isset($user)) {
                $userList[] = $this->userMapper->map($user);
            }
        }

        return $userList;
    }

    public function get(string $username): ?UserDataProvider
    {
        $userEntity = $this->entityRepository->findBy([
            'username' => $username
        ]);
        if (isset($userEntity[0])) {
            return $this->userMapper->map($userEntity[0]);
        }
        return null;
    }

    public function getById(int $id):?UserDataProvider
    {
        $userEntity = $this->entityRepository->findBy([
            'id' => $id
        ]);
        if (isset($userEntity[0])) {
            return $this->userMapper->map($userEntity[0]);
        }
        return null;
    }
}
