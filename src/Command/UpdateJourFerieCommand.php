<?php

// src/Command/UpdateJourFerieCommand.php
namespace App\Command;

use App\Entity\JourFerie;
use App\Service\JourFerieApiService; // Assurez que cette déclaration d'utilisation existe
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-jours-feries',
    description: 'Mise à jour des jours fériés depuis l api pour toutes les zones françaises '
)]
class UpdateJourFerieCommand extends Command
{
    private JourFerieApiService $apiService;
    private EntityManagerInterface $em;

    public function __construct(
        JourFerieApiService $apiService, 
        EntityManagerInterface $em
    ) {
        // attribuer correctement les services
        $this->apiService = $apiService;
        $this->em = $em;
        
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'year',
            InputArgument::OPTIONAL,
            'The year to update',
            date('Y')
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = (int)$input->getArgument('year');
        $output->writeln("⌛ Updating public holidays for year {$year}...");

        try {
            $zones = [
                'metropole',
                'guadeloupe',
                'guyane',
                'martinique',
                'mayotte',
                'reunion',
                'alsace-moselle'
            ];
            
            $totalImported = 0;

            foreach ($zones as $zone) {
                $output->writeln(" Processing zone: {$zone}");
                $count = $this->apiService->syncHolidaysForZone($zone, $year);
                $totalImported += $count;
                $output->writeln("  → Imported {$count} holidays");
            }

            $output->writeln(" Successfully imported {$totalImported} holidays total");
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $output->writeln("<error> Error: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}