<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Find users by email.
     *
     * @param string $email
     * @return User[]
     */
    public function findByEmail(string $email): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find users created after a certain date.
     *
     * @param \DateTimeInterface $date
     * @return User[]
     */
    public function findCreatedAfter(\DateTimeInterface $date): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.createdAt > :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find users by name.
     *
     * @param string $name
     * @return User[]
     */
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->getQuery()
            ->getResult();
    }
}