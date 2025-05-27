<?php

namespace App\Command;

use App\Entity\JourFerie;
use App\Service\JourFerieFetcher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:update-jours-feries')]
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
        $this
            ->setDescription('Updates public holidays from the API')
            ->addArgument('year', InputArgument::OPTIONAL, 'The year to update', date('Y'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = (int)$input->getArgument('year');
        $data = $this->fetcher->fetchAllZones($year);

        foreach ($data as $row) {
            $date = new \DateTime($row['date']);

            // Prevent duplicate import for same date & zone
            $existing = $this->em->getRepository(JourFerie::class)->findOneBy([
                'date' => $date,
                'zone' => $row['zone'],
            ]);

            if (!$existing) {
                $jourFerie = new JourFerie();
                $jourFerie->setNom($row['nom']);
                $jourFerie->setDate($date);
                $jourFerie->setAnnee((string) $year);
                $jourFerie->setZone($row['zone']);
                $this->em->persist($jourFerie);
            }
        }

        $this->em->flush();
        $output->writeln("✅ Jours fériés pour {$year} importés avec succès.");

        return Command::SUCCESS;
    }
}
