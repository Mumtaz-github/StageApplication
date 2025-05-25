<?php


namespace App\Command;

use App\Entity\JourFerie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'import:jour-ferie',
    description: 'Importe les jours fériés depuis un fichier CSV',
)]
class ImportJourFerieCommand extends Command
{
    private const BATCH_SIZE = 20;
    private string $projectDir;

    public function __construct(
        private EntityManagerInterface $em,
        ParameterBagInterface $params,
        string $name = null
    ) {
        parent::__construct($name);
        $this->projectDir = $params->get('kernel.project_dir');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $this->projectDir . '/conception/Api Jour Ferie/jours_feries_metropole.csv';

        if (!file_exists($file)) {
            $output->writeln("<error>❌ Fichier introuvable : $file</error>");
            return Command::FAILURE;
        }

        $handle = fopen($file, 'r');
        if (!$handle) {
            $output->writeln("<error>❌ Impossible d'ouvrir le fichier</error>");
            return Command::FAILURE;
        }

        // Skip header
        fgetcsv($handle);

        $count = 0;
        $batchCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 4) continue;

            [$dateStr, $annee, $zone, $nom] = $row;

            // Validate date string format (simple check)
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($dateStr))) {
                $output->writeln("<error>⚠️ Format de date invalide à la ligne $count: " . trim($dateStr) . "</error>");
                continue;
            }

            try {
                $jourFerie = new JourFerie();
                $jourFerie
                    ->setDate(trim($dateStr))  // Pass date as string now
                    ->setAnnee(trim($annee))
                    ->setZone(trim($zone))
                    ->setNom(trim($nom));

                $this->em->persist($jourFerie);

                $count++;
                $batchCount++;

                if ($batchCount >= self::BATCH_SIZE) {
                    $this->em->flush();
                    $this->em->clear();
                    $batchCount = 0;
                }

            } catch (\Exception $e) {
                $output->writeln("<error>⚠️ Erreur ligne $count: " . $e->getMessage() . "</error>");
            }
        }

        if ($batchCount > 0) {
            $this->em->flush();
            $this->em->clear();
        }

        fclose($handle);

        $output->writeln("<info>✅ $count jours fériés importés avec succès</info>");
        return Command::SUCCESS;
    }
}












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

//             try {
//                 $date = \DateTime::createFromFormat('Y-m-d', trim($dateStr));
//                 if (!$date) {
//                     throw new \RuntimeException("Invalid date format: " . trim($dateStr));
//                 }

//                 $jourFerie = new JourFerie();
//                 $jourFerie
//                     ->setDate($date)
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

