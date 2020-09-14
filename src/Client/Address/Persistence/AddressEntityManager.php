<?php
declare(strict_types=1);

namespace App\Client\Address\Persistence;

use App\Client\Address\Persistence\Entity\Address;
use App\Generated\AddressDataProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class AddressEntityManager implements AddressEntityManagerInterface
{
    /**
     * @var AddressRepositoryInterface
     */
    private AddressRepositoryInterface $addressRepository;
    private EntityManager $entityManager;
    private EntityRepository $repository;


    public function __construct(EntityManager $entityManager, AddressRepositoryInterface $addressRepository)
    {
        $this->addressRepository = $addressRepository;
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Address::class);
    }



    public function delete(AddressDataProvider $address):void
    {
        $this->entityManager->remove($address);
        $this->entityManager->flush();
        $this->addressRepository->getAddressList();
    }

    public function save(AddressDataProvider $address): AddressDataProvider
    {

        $userEntity = $this->convert($address);


        $this->entityManager->persist($userEntity);
        $this->entityManager->flush();

        $newAddress = $this->addressRepository->getAddress($address->getUser(), $address->getType(), $address->getPostcode());
        if (! $newAddress instanceof AddressDataProvider) {
            throw new \Exception('Fatal error while saving/loading', 1);
        }
        return $newAddress;
    }
    private function convert(AddressDataProvider $addressDataProvider):Address
    {
        $address = new Address();
        $address->setUserId($addressDataProvider->getUser()->getId());
        $address->setId($addressDataProvider->getAddress_id());
        $address->setLastName($addressDataProvider->getLastName());
        $address->setFirstName($addressDataProvider->getFirstName());
        $address->setStreet($addressDataProvider->getStreet());
        $address->setCountry($addressDataProvider->getCountry());
        $address->setPostCode($addressDataProvider->getPostcode());
        $address->setTown($addressDataProvider->getTown());
        $address->setType($addressDataProvider->getType());

        return $address;
    }
}
