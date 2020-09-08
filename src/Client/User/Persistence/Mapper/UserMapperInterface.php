<?php

namespace App\Client\User\Persistence\Mapper;

use App\Client\User\Persistence\Entity\User;
use App\Generated\UserDataProvider;

interface UserMapperInterface
{
    public function map(User $user): UserDataProvider;
}
