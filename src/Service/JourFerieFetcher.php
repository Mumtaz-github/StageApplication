<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JourFerieFetcher
{
    private HttpClientInterface $client;
    private ?LoggerInterface $logger;

    public function __construct(HttpClientInterface $client, ?LoggerInterface $logger = null)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function fetchAllZones(int $year): array
    {
        $zones = [
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
            'alsace-moselle',
            'polynesie-francaise',
        ];

        $all = [];

        foreach ($zones as $zone) {
            $url = "https://calendrier.api.gouv.fr/jours-feries/{$zone}/{$year}.json";

            try {
                $response = $this->client->request('GET', $url);

                if ($response->getStatusCode() !== 200) {
                    $content = $response->getContent(false);
                    throw new \RuntimeException("HTTP {$response->getStatusCode()} - Response: {$content}");
                }

                $data = $response->toArray();

                foreach ($data as $date => $name) {
                    $all[] = [
                        'date' => $date,
                        'nom' => $name,
                        'zone' => $zone,
                    ];
                }
            } catch (\Throwable $e) {
                $this->logger?->error("Failed to fetch holidays for zone '{$zone}' and year {$year}: " . $e->getMessage());
            }
        }

        return $all;
    }
}
