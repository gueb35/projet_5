<?php

namespace App\Repository;

use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapping;
use App\Entity\Members;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Members|null find($id, $lockMode = null, $lockVersion = null)
 * @method Members|null findOneBy(array $criteria, array $orderBy = null)
 * @method Members[]    findAll()
 * @method Members[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Members::class);
    }

    /**
     * fonction qui fait une requète pour obtenir tous les membres ayant adhéré aux paniers composés
     * 
     * @param string $day
     * 
     * @return Members[] Returns an array of Members objects
     */
    public function listOfMembersByDay($day)
    {
        $rsm = new ResultSetMappingBuilder($this->_em);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Members', 'm');

        $query = $this->_em->createNativeQuery('SELECT * from members m WHERE m.basket_type_bis = :basket and  m.day_of_week = :day', $rsm)
            ->setParameter('basket', 'collectés')
            ->setParameter('day', $day);
            return $query->getResult();

    }
}
