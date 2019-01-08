<?php

namespace App\Repository;

use App\Entity\ProdBaskComp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProdBaskComp|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProdBaskComp|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProdBaskComp[]    findAll()
 * @method ProdBaskComp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProdBaskCompRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProdBaskComp::class);
    }
}
