<?php

namespace App\Repository;

use App\Entity\MembersOfBasketCollected;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MembersOfBasketCollected|null find($id, $lockMode = null, $lockVersion = null)
 * @method MembersOfBasketCollected|null findOneBy(array $criteria, array $orderBy = null)
 * @method MembersOfBasketCollected[]    findAll()
 * @method MembersOfBasketCollected[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembersOfBasketCollectedRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MembersOfBasketCollected::class);
    }

    // /**
    //  * @return MembersOfBasketCollected[] Returns an array of MembersOfBasketCollected objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MembersOfBasketCollected
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
