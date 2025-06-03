<?php

namespace App\MessageHandler;

use App\Message\SyncHolidaysMessage;
use App\Service\JourFerieApiService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;

#[AsMessageHandler]
class SyncHolidaysMessageHandler
{
    public function __construct(
        private readonly JourFerieApiService $holidayService,
        private readonly ?LoggerInterface $logger = null
    ) {}

    public function __invoke(SyncHolidaysMessage $message): void
    {
        try {
            $this->logger?->info('Starting holiday synchronization', [
                'zone' => $message->getZone(),
                'year' => $message->getYear()
            ]);

            $count = $this->holidayService->syncHolidaysForZone(
                $message->getZone(),
                $message->getYear()
            );

            $this->logger?->info('Successfully synchronized holidays', [
                'count' => $count,
                'zone' => $message->getZone(),
                'year' => $message->getYear()
            ]);
            
        } catch (\Exception $e) {
            $this->logger?->error('Failed to synchronize holidays', [
                'error' => $e->getMessage(),
                'zone' => $message->getZone(),
                'year' => $message->getYear()
            ]);
            
            // You might want to throw the exception again to trigger retry logic
            throw $e;
        }
    }
}