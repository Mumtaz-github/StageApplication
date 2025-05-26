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
    private JourFerieFetcher $fetcher;
    private EntityManagerInterface $em;

    public function __construct(JourFerieFetcher $fetcher, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->fetcher = $fetcher;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Updates public holidays from the API')
            ->addArgument('year', InputArgument::OPTIONAL, 'The year to update', date('Y'));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = (int)$input->getArgument('year');
        $data = $this->fetcher->fetchHolidays($year);

        foreach ($data as $date => $name) {
            $existing = $this->em->getRepository(JourFerie::class)->findOneBy(['date' => new \DateTime($date)]);
            if (!$existing) {
                $jourFerie = new JourFerie();
                $jourFerie->setNom($name);
                $jourFerie->setDate(new \DateTime($date));
                $this->em->persist($jourFerie);
            }
        }

        $this->em->flush();
        $output->writeln("Jours fériés {$year} importés.");

        return Command::SUCCESS;
    }
}





//replace this csv with API 

// namespace App\Command;

// use App\Entity\JourFerie;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\Console\Attribute\AsCommand;
// use Symfony\Component\Console\Command\Command;
// use Symfony\Component\Console\Input\InputInterface;
// use Symfony\Component\Console\Output\OutputInterface;
// use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

// #[AsCommand(
//     name: 'import:jour-ferie',
//     description: 'Importe les jours fériés depuis un fichier CSV',
// )]
// class ImportJourFerieCommand extends Command
// {
//     private const BATCH_SIZE = 20;
//     private string $projectDir;

//     public function __construct(
//         private EntityManagerInterface $em,
//         ParameterBagInterface $params,
//         string $name = null
//     ) {
//         parent::__construct($name);
//         $this->projectDir = $params->get('kernel.project_dir');
//     }

//     protected function execute(InputInterface $input, OutputInterface $output): int
//     {
//         $file = $this->projectDir . '/conception/Api Jour Ferie/jours_feries_metropole.csv';

//         if (!file_exists($file)) {
//             $output->writeln("<error>❌ Fichier introuvable : $file</error>");
//             return Command::FAILURE;
//         }

//         $handle = fopen($file, 'r');
//         if (!$handle) {
//             $output->writeln("<error>❌ Impossible d'ouvrir le fichier</error>");
//             return Command::FAILURE;
//         }

//         // Skip header
//         fgetcsv($handle);

//         $count = 0;
//         $batchCount = 0;

//         while (($row = fgetcsv($handle)) !== false) {
//             if (count($row) < 4) continue;

//             [$dateStr, $annee, $zone, $nom] = $row;

//             // Validate date string format (simple check)
//             if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($dateStr))) {
//                 $output->writeln("<error>⚠️ Format de date invalide à la ligne $count: " . trim($dateStr) . "</error>");
//                 continue;
//             }

//             try {
//                 $jourFerie = new JourFerie();
//                 $jourFerie
//                     ->setDate(trim($dateStr))  // Pass date as string now
//                     ->setAnnee(trim($annee))
//                     ->setZone(trim($zone))
//                     ->setNom(trim($nom));

//                 $this->em->persist($jourFerie);

//                 $count++;
//                 $batchCount++;

//                 if ($batchCount >= self::BATCH_SIZE) {
//                     $this->em->flush();
//                     $this->em->clear();
//                     $batchCount = 0;
//                 }

//             } catch (\Exception $e) {
//                 $output->writeln("<error>⚠️ Erreur ligne $count: " . $e->getMessage() . "</error>");
//             }
//         }

//         if ($batchCount > 0) {
//             $this->em->flush();
//             $this->em->clear();
//         }

//         fclose($handle);

//         $output->writeln("<info>✅ $count jours fériés importés avec succès</info>");
//         return Command::SUCCESS;
//     }
// }












