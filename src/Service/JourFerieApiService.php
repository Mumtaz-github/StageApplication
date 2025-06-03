<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\JourFerie;

class JourFerieApiService
{
    private HttpClientInterface $httpClient;
    private EntityManagerInterface $entityManager;
    private ?LoggerInterface $logger;

    public function __construct(
        HttpClientInterface $httpClient,
        EntityManagerInterface $entityManager,
        ?LoggerInterface $logger = null
    ) {
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    public function syncHolidaysForZone(string $zone, string $year): int
    {
        try {
            $apiUrl = "https://calendrier.api.gouv.fr/jours-feries/{$zone}/{$year}.json";
            $response = $this->httpClient->request('GET', $apiUrl);
            $holidays = $response->toArray();

            $importedCount = 0;
            foreach ($holidays as $date => $name) {
                if (!$this->entityManager->getRepository(JourFerie::class)->findOneBy([
                    'date' => new \DateTime($date),
                    'zone' => $zone
                ])) {
                    $holiday = new JourFerie();
                    $holiday->setDate(new \DateTime($date))
                           ->setNom($name)
                           ->setZone($zone)
                           ->setAnnee($year);
                    
                    $this->entityManager->persist($holiday);
                    $importedCount++;
                }
            }

            $this->entityManager->flush();
            return $importedCount;

        } catch (\Exception $e) {
            $this->logger?->error("Holiday sync failed: " . $e->getMessage());
            throw $e;
        }
    }
}