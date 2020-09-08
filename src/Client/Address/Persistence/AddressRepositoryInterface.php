<?php
declare(strict_types=1);
namespace App\Client\Address\Persistence;

use App\Client\User\Persistence\Entity\User;
use App\Generated\AddressDataProvider;
use App\Generated\UserDataProvider;

interface AddressRepositoryInterface
{
    /**
     * @return AddressDataProvider[]
     */
    public function getAddressList(): array;

    public function getAddress(UserDataProvider $user, string $type, int $postcode): ?AddressDataProvider;

    public function getAddressListFromUser(int $userId):array;

    public function getById(int $addressId):?AddressDataProvider;
}