<?php
// src/Service/JourFerieApiService.php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\JourFerie;
use Psr\Log\LoggerInterface;

class JourFerieApiService
{
    private $httpClient;
    private $entityManager;
    private $logger;
    private $apiBaseUrl = 'https://calendrier.api.gouv.fr/jours-feries';

    public function __construct(
        HttpClientInterface $httpClient,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger = null
    ) {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function syncHolidaysForZone(string $zone, int $year): int
    {
        try {
            $response = $this->httpClient->request(
                'GET',
                "{$this->apiBaseUrl}/{$zone}/{$year}.json"
            );

            $holidays = $response->toArray();
            $importedCount = 0;

            foreach ($holidays as $date => $name) {
                $existing = $this->entityManager->getRepository(JourFerie::class)->findOneBy([
                    'date' => new \DateTime($date),
                    'zone' => $zone
                ]);

                if (!$existing) {
                    $jourFerie = new JourFerie();
                    $jourFerie->setDate(new \DateTime($date));
                    $jourFerie->setNom($name);
                    $jourFerie->setZone($zone);
                    $jourFerie->setAnnee($year);

                    $this->entityManager->persist($jourFerie);
                    $importedCount++;
                }
            }

            $this->entityManager->flush();
            return $importedCount;

        } catch (\Exception $e) {
            $this->logger?->error("API Sync Error for zone {$zone}: " . $e->getMessage());
            throw $e;
        }
    }
}