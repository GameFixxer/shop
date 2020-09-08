<?php
declare(strict_types=1);

namespace App\Client\User\Business;

use App\Client\User\Persistence\Entity\User;
use App\Generated\UserDataProvider;

interface UserBusinessFacadeInterface
{
    public function get(string $username): ?UserDataProvider;

    /**
     * @return UserDataProvider[]
     */
    public function getList(): array;

    public function save(UserDataProvider $user): UserDataProvider;

    public function delete(UserDataProvider $user);

    public function getById(int $id):?UserDataProvider;
}