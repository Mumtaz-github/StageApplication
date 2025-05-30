<?php

// src/Command/UpdateJourFerieCommand.php
namespace App\Command;

use App\Entity\JourFerie;
use App\Service\JourFerieFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-jours-feries',
    description: 'Updates public holidays from API for all French zones'
)]
class UpdateJourFerieCommand extends Command
{
    public function __construct(
        private JourFerieFetcher $fetcher,
        private EntityManagerInterface $em
    ) {
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
            $holidays = $this->fetcher->fetchAllZones($year);
            $importedCount = 0;

            foreach ($holidays as $holiday) {
                $date = new \DateTime($holiday['date']);
                
                $existing = $this->em->getRepository(JourFerie::class)->findOneBy([
                    'date' => $date,
                    'zone' => $holiday['zone']
                ]);

                if (!$existing) {
                    $entity = new JourFerie();
                    $entity->setDate($date);
                    $entity->setNom($holiday['nom']);
                    $entity->setZone($holiday['zone']);
                    $entity->setAnnee((string)$year);
                    
                    $this->em->persist($entity);
                    $importedCount++;
                }
            }

            $this->em->flush();
            $output->writeln("✅ Successfully imported {$importedCount} holidays for {$year}");
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>❌ Error: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}