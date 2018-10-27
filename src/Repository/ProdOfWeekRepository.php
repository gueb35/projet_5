<?php

namespace App\Repository;

use App\Entity\ProdOfWeek;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProdOfWeek|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProdOfWeek|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProdOfWeek[]    findAll()
 * @method ProdOfWeek[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProdOfWeekRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProdOfWeek::class);
    }

//    /**
//     * @return ProdOfWeek[] Returns an array of ProdOfWeek objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProdOfWeek
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
