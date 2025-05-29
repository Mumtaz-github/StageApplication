<?php

namespace App\Service\DateLibrary;

use App\Repository\JourFerieRepository;

class DateService
{
    private JourFerieRepository $jourFerieRepository;

    public function __construct(JourFerieRepository $jourFerieRepository)
    {
        $this->jourFerieRepository = $jourFerieRepository;
    }

    public function getIsoWeekNumber(\DateTimeInterface $date): int
    {
        return (int)$date->format('W');
    }

    public function getFirstDayOfWeek(\DateTimeInterface $date): \DateTimeInterface
    {
        $day = clone $date;
        return $day->modify('Monday this week');
    }

    public function getFirstDayOfMonth(\DateTimeInterface $date): \DateTimeInterface
    {
        $day = clone $date;
        return $day->modify('first day of this month')->setTime(0, 0);
    }

    public function getNextWorkingDay(
        \DateTimeInterface $date,
        string $zone = 'metropole',
        ?int $year = null
    ): \DateTimeInterface {
        $next = clone $date;

        // default to current year if not specified
        if (!$year) {
            $year = (int)$next->format('Y');
        }

        // fetch holidays from DB
        $joursFeries = $this->jourFerieRepository->findBy([
            'annee' => (string)$year,
            'zone' => $zone
        ]);

        $holidayDates = array_map(
            fn($j) => $j->getDate()->format('Y-m-d'),
            $joursFeries
        );

        do {
            $next = $next->modify('+1 day');
        } while (
            in_array($next->format('Y-m-d'), $holidayDates, true) ||
            in_array((int)$next->format('N'), [6, 7]) // weekends
        );

        return $next;
    }

    public function getMonthsBetween(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $start = (clone $start)->modify('first day of this month');
        $end = (clone $end)->modify('first day of this month');

        $months = [];
        while ($start <= $end) {
            $months[] = $start->format('Y-m');
            $start = $start->modify('+1 month');
        }

        return $months;
    }

    public function getWeeksBetween(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $start = (clone $start)->modify('Monday this week');
        $end = (clone $end)->modify('Monday this week');

        $weeks = [];
        while ($start <= $end) {
            $weeks[] = $start->format('Y-\WW');
            $start = $start->modify('+1 week');
        }

        return $weeks;
    }

    public function getHolidaysForYearAndZone(int $year, string $zone = 'metropole'): array
    {
        $joursFeries = $this->jourFerieRepository->findBy([
            'annee' => (string)$year,
            'zone' => $zone
        ]);

        return array_map(
            fn($j) => $j->getDate()->format('Y-m-d'),
            $joursFeries
        );
    }
}

