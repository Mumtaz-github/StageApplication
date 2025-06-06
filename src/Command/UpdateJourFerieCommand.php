<?php

// src/Command/ImportJourFerieCommand.php
namespace App\Command;

use App\Entity\JourFerie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use League\Csv\Reader;

#[AsCommand(
    name: 'app:import-jours-feries',
    description: 'Import holidays from CSV file'
)]
class UpdateJourFerieCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private string $projectDir
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'file',
            InputArgument::OPTIONAL,
            'CSV file path',
            'conception/ApiJourFerie/jours_feries_metropole.csv'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = $this->projectDir.'/'.$input->getArgument('file');
        $csv = Reader::createFromPath($filePath);
        $csv->setHeaderOffset(0);

        $imported = 0;
        foreach ($csv as $record) {
            $date = \DateTime::createFromFormat('Y-m-d', $record['date']);
            $year = $date->format('Y');

            if (!$this->em->getRepository(JourFerie::class)->findOneBy([
                'date' => $date,
                'zone' => 'metropole'
            ])) {
                $jourFerie = new JourFerie();
                $jourFerie->setDate($date)
                    ->setNom($record['nom'])
                    ->setZone('metropole');
                    //  ->setAnnee($year);

                $this->em->persist($jourFerie);
                $imported++;
            }
        }

        $this->em->flush();
        $output->writeln("Imported {$imported} holidays");
        return Command::SUCCESS;
    }
}