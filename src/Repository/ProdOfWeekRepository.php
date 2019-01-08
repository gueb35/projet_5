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
}
