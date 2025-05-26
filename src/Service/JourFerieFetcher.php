<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class JourFerieFetcher
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchHolidays(int $year = null, string $zone = 'metropole'): array
    {
        $year = $year ?? date('Y');
        $url = "https://calendrier.api.gouv.fr/jours-feries/{$zone}/{$year}.json";

        $response = $this->client->request('GET', $url);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException("Failed to fetch holidays for year {$year}");
        }

        return $response->toArray();
    }
}
