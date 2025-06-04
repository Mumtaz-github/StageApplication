<?php
namespace App\Service\DateLibrary;

use App\Repository\JourFerieRepository;
use DateTime;
use DateTimeInterface;

class DateService
{
    private JourFerieRepository $jourFerieRepository;

    public function __construct(JourFerieRepository $jourFerieRepository)
    {
        $this->jourFerieRepository = $jourFerieRepository;
    }

public function getDaysBetween(DateTimeInterface $date1, DateTimeInterface $date2): int
{
    return abs($date1->diff($date2)->days) + 1;
}

/**
 * Get number of days in a given month and year
 */
public function getDaysInMonth(int $month, int $year): int
{
    return cal_days_in_month(CAL_GREGORIAN, $month, $year);
}


    /**
     * Get all months between two dates (format: Y-m)
     */
    public function getMonthsBetweenDates(DateTimeInterface $start, DateTimeInterface $end): array
    {
        $start = (new DateTime())->setTimestamp($start->getTimestamp());
        $end = (new DateTime())->setTimestamp($end->getTimestamp());
        
        $months = [];
        $current = clone $start;
        $current->modify('first day of this month');
        
        while ($current <= $end) {
            $months[] = $current->format('Y-m');
            $current->modify('+1 month');
        }
        
        return $months;
    }

public function getOffsetDaysBetween(DateTimeInterface $start, DateTimeInterface $end): int
{
    return (int) $start->diff($end)->days;
}


    /**
     * Get days from project start to target date
     */
    public function getDaysFromStart(DateTimeInterface $startDate, DateTimeInterface $targetDate): int
    {
        return $startDate->diff($targetDate)->days;
    }

    /**
     * Get next working day (skips weekends and holidays)
     */
    public function getNextWorkday(
        DateTimeInterface $date,
        string $zone = 'metropole',
        ?string $year = null
    ): DateTimeInterface {
        $date = $this->convertToDateTime($date);
        $year = $year ?? $date->format('Y');
        $nextDay = clone $date;

        do {
            $nextDay->modify('+1 day');
        } while (
            $this->isWeekend($nextDay) || 
            $this->isHoliday($nextDay, $zone, $year)
        );

        return $nextDay;
    }

    

    /**
     * Check if date is weekend (Saturday/Sunday)
     */
    private function isWeekend(DateTimeInterface $date): bool
    {
        return in_array($date->format('N'), ['6', '7']);
    }

    /**
     * Check if a date is a holiday
     */
    public function isHoliday(
        DateTimeInterface $date,
        string $zone,
        ?string $year = null
    ): bool {
        $year = $year ?? $date->format('Y');
        
        return $this->jourFerieRepository->createQueryBuilder('j')
            ->where('j.date = :date')
            ->andWhere('j.zone = :zone')
            ->andWhere('j.annee = :year')
            ->setParameter('date', $date->format('Y-m-d'))
            ->setParameter('zone', $zone)
            ->setParameter('year', $year)
            ->getQuery()
            ->getOneOrNullResult() !== null;
    }

    /**
     * Convert DateTimeInterface to DateTime for modification
     */
    private function convertToDateTime(DateTimeInterface $date): DateTime
    {
        return $date instanceof DateTime ? $date : new DateTime($date->format('Y-m-d'));
    }
}