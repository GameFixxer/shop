<?php

declare(strict_types=1);

namespace App\Client\User\Persistence;


use App\Client\User\Persistence\Entity\User;
use App\Client\User\Persistence\Mapper\UserMapperInterface;
use App\Generated\UserDataProvider;
use Cycle\ORM\ORM;

class UserRepository implements UserRepositoryInterface
{
    private UserMapperInterface $userMapper;
    private \Cycle\ORM\RepositoryInterface $ormUserRepository;

    public function __construct(UserMapperInterface $userMapper, \Cycle\ORM\ORM $ormUserRepository)
    {
        $this->userMapper = $userMapper;
        $this->ormUserRepository = $ormUserRepository->getRepository(User::class);
    }

    /**
     * @return UserDataProvider[]
     */
    public function getList(): array
    {
        $userList = [];

        $userEntityList = (array)$this->ormUserRepository->select()->fetchALl();
        /** @var  User $user */
        foreach ($userEntityList as $user) {
            $userList[] = $this->userMapper->map($user);
        }

        return $userList;
    }

    public function get(string $username): ?UserDataProvider
    {
        $userEntity = $this->ormUserRepository->findOne([
            'username' => $username
        ]);
        if (isset($userEntity)) {
            return $this->userMapper->map($userEntity[0]);
        }
        return null;
    }

    public function getById(int $id):?UserDataProvider
    {
        $userEntity = $this->ormUserRepository->findByPK($id);
        if (isset($userEntity)) {
            return $this->userMapper->map($userEntity[0]);
        }
        return null;
    }
}
