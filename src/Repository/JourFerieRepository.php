<?php

namespace App\Repository;

use App\Entity\JourFerie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTimeInterface;

/**
 * @extends ServiceEntityRepository<JourFerie>
 */
class JourFerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JourFerie::class);
    }

    /**
     * Finds holidays within a specific date range
     */
    public function findForDateRange(DateTimeInterface $startDate, DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('j')
            ->where('j.date BETWEEN :start AND :end')
            ->setParameter('start', $startDate->format('Y-m-d'))
            ->setParameter('end', $endDate->format('Y-m-d'))
            ->orderBy('j.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

public function findDistinctAnnees(): array
{
    return $this->createQueryBuilder('j')
        ->select('DISTINCT YEAR(j.date) as year')
        ->orderBy('year', 'ASC')
        ->getQuery()
        ->getSingleColumnResult();
}

public function findByYear(int $year): array
{
    return $this->createQueryBuilder('j')
        ->where('YEAR(j.date) = :year')
        ->setParameter('year', $year)
        ->orderBy('j.date', 'ASC')
        ->getQuery()
        ->getResult();
}
}