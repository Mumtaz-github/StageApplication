<?php


namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email; 
use Psr\Log\LoggerInterface; // Import the LoggerInterface

class SendMailService
{
    private $mailer;
    private $logger; 

    public function __construct(MailerInterface $mailer, LoggerInterface $logger) // Inject the logger
    {
        $this->mailer = $mailer;
        $this->logger = $logger; // Initialize the logger
    }

   public function send(
    string $from,
    string $to,
    string $subject,
    string $template,
    array $context
): void {
    try {
  
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("emails/$template.html.twig")
            ->context($context);

        $this->mailer->send($email);
        
    } catch (\Exception $e) {
        $this->logger->error('Email sending error: ' . $e->getMessage(), ['exception' => $e]);
        throw $e;
    }
}
}



