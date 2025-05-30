<?php

// src/Service/JourFerieFetcher.php
namespace App\Service;

use App\Entity\JourFerie;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JourFerieFetcher
{
    private HttpClientInterface $client;
    private EntityManagerInterface $em;
    private ?LoggerInterface $logger;
    private array $zones = [
        'metropole',
        'guadeloupe',
        'guyane',
        'martinique',
        'mayotte',
        'la-reunion',
        'saint-barthelemy',
        'saint-martin',
        'saint-pierre-et-miquelon',
        'wallis-et-futuna',
        'nouvelle-caledonie',
        'alsace-moselle'
    ];

    public function __construct(
        HttpClientInterface $client,
        EntityManagerInterface $em,
        ?LoggerInterface $logger = null
    ) {
        $this->client = $client;
        $this->em = $em;
        $this->logger = $logger;
    }

    public function fetchAllZones(int $year): array
    {
        $allHolidays = [];
        
        foreach ($this->zones as $zone) {
            try {
                $url = "https://calendrier.api.gouv.fr/jours-feries/{$zone}/{$year}.json";
                $response = $this->client->request('GET', $url);

                if ($response->getStatusCode() !== 200) {
                    throw new \RuntimeException("HTTP Error for zone {$zone}: " . $response->getStatusCode());
                }

                $data = $response->toArray();

                foreach ($data as $date => $name) {
                    $allHolidays[] = [
                        'date' => $date,
                        'nom' => $name,
                        'zone' => $zone
                    ];
                }
            } catch (\Throwable $e) {
                $this->logger?->error("Failed to fetch holidays for zone {$zone}: " . $e->getMessage());
                continue;
            }
        }

        return $allHolidays;
    }
}