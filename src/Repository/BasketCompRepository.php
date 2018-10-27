<?php

namespace App\Repository;

use App\Entity\BasketComp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BasketComp|null find($id, $lockMode = null, $lockVersion = null)
 * @method BasketComp|null findOneBy(array $criteria, array $orderBy = null)
 * @method BasketComp[]    findAll()
 * @method BasketComp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BasketCompRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BasketComp::class);
    }

//    /**
//     * @return BasketComp[] Returns an array of BasketComp objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BasketComp
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
