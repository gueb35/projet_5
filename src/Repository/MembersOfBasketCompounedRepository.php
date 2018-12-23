<?php

namespace App\Repository;

use App\Entity\MembersOfBasketCompouned;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MembersOfBasketCompouned|null find($id, $lockMode = null, $lockVersion = null)
 * @method MembersOfBasketCompouned|null findOneBy(array $criteria, array $orderBy = null)
 * @method MembersOfBasketCompouned[]    findAll()
 * @method MembersOfBasketCompouned[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembersOfBasketCompounedRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MembersOfBasketCompouned::class);
    }

    // /**
    //  * @return MembersOfBasketCompouned[] Returns an array of MembersOfBasketCompouned objects
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
    public function findOneBySomeField($value): ?MembersOfBasketCompouned
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
