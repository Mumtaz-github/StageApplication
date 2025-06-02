<?php

namespace App\Repository;

use App\Entity\JourFerie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JourFerie>
 */
class JourFerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JourFerie::class);
    }

public function findDistinctZones(): array
{
    return $this->createQueryBuilder('j')
        ->select('DISTINCT j.zone')
        ->orderBy('j.zone', 'ASC')
        ->getQuery()
        ->getSingleColumnResult();
}

public function findDistinctAnnees(): array
{
    return $this->createQueryBuilder('j')
        ->select('DISTINCT j.annee')
        ->orderBy('j.annee', 'ASC')
        ->getQuery()
        ->getSingleColumnResult();
}
}
